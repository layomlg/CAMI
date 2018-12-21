<?php
require_once DIRECTORY_CLASS . 'PhpSpreadsheet/Spreadsheet.php';

/**
 * Class SQL_Server_DataTable
 */
abstract class SQL_Server_DataTable {

	public $page;
	public $rows; 
	public $table_id;
	public $sord;
	public $sidx;
	public $fidx;
	public $fval;
	public $exfidx;
	public $exfval;
	public $extra;
	public $acciones;
	public $idioma = 1;
	public $title;
	public $total_pages = 0; 
	public $total_records = 0;
	protected $which; 
	protected $query;
	protected $query_count;
	protected $where; 
	protected $group; 
	protected $sort; 
	protected $limit; 
	protected $columns = array();
	protected $template = "";
	protected $template_ul = "";
	protected $template_header = "";
	protected $template_foot = "";
	protected $cols = array(); 
	protected $showing_template = " Mostrando %s - %s de %s registros. ";
	protected $sel_all_option = FALSE;
	protected $sel_all_function = '';
	protected $sel_all_fcn_params = array();
	protected $dates = FALSE; 
	protected $since = TRUE; 
	protected $end = TRUE;
	public $error = array();


	/**
	 * __construct
	 * 
	 * @param	 String $which list id
	 * @param 	 String $table_id Id for the html container where the table will be placed (Default to which)
	 */
	public function __construct($which = '', $table_id = '') {
		$this->which = $which; 
		$this->class = "SQL_Server_DataTable";
		$this->page  = isset($_REQUEST['page'])  && $_REQUEST['page'] > 0 ? $_REQUEST['page'] : 1; 
		$this->rows  = isset($_REQUEST['rows'])  && is_numeric($_REQUEST['rows']) ? $_REQUEST['rows'] : 25; 
		$this->sord  = isset($_REQUEST['sord'])  && $_REQUEST['sord'] != '' ? $_REQUEST['sord'] : "ASC"; 
		$this->sidx  = isset($_REQUEST['sidx'])  && $_REQUEST['sidx'] != '' ? $_REQUEST['sidx'] : "id";
		$this->extra = isset($_REQUEST['extra']) && $_REQUEST['extra'] != '' ? $_REQUEST['extra'] : "";
		if (
				( isset($_REQUEST['since']) && $_REQUEST['since'] 	!= '' ) 
			|| 	( isset($_REQUEST['end']) 	&& $_REQUEST['end'] 	!= '' )
		){
				$this->dates = TRUE;
		}
		
		if ($table_id != '') 
			$this->table_id = $table_id;
		else 
			$this->table_id = $which;

		$this->set_search(); 
	}

	protected function set_list($which, $table_id = ''){
		$this->which = $which; 
		if ($table_id != '') 
			$this->table_id = $table_id; 
		else 
			$this->table_id = $which;
	}

	public function set_filter($col, $val, $signo = '=', $modo = 'AND', $open = '', $close = ''){
          if ($signo == 'LIKE')
              $this->where .= " " . $modo . " " . $open . " " . ($col) . " " . $signo . " '%" . ($val) . "%' " . $close . " ";
          else if($signo == 'IN')
              $this->where .= " " . $modo . " " . $open . " " . ($col) . " " . $signo . "  " . ($val) . "  " . $close . " ";
          else
              $this->where .= " " . $modo . " " . $open . " " . ($col) . " " . $signo . "  '" . ($val) . "'  " . $close . " ";
              
          $this->fidx = $col;
          $this->fval = $val;
	}

	public function set_title($title) {
		$this->title = $title;
	}

	public function set_search() {
		if (isset($_REQUEST['searchField']) && $_REQUEST['searchField'] != '' && isset($_REQUEST['searchString']) && $_REQUEST['searchString'] != '') {
			$sfield = $_REQUEST['searchField']; 
			$sstr = $_REQUEST['searchString']; 
			$this->where .= " AND " . $sfield . " LIKE '%" . ($sstr) . "%' ";
		}
	}
	
	public function set_dates( $colname = 'date'){
		
		if ( isset( $_REQUEST['since'] ) && $_REQUEST['since'] != '' ){
			 
			try {
				list( $Y, $m, $d ) = explode("-", $_REQUEST['since']);
				$this->since = mktime( 0,0,0,$m, $d, $Y );
			} catch (Exception $e){ 
				$this->since = mktime( 0,0,0,date('m'), 1, date('Y') );
			}  
		} else {
			$this->since = mktime( 0,0,0,date('m'), date('d'), date('Y') );
			$this->since = $this->since - (86400 * date('t'));
		}
		if ( isset( $_REQUEST['end'] ) && $_REQUEST['end'] != '' ){
			try {
				list( $Y, $m, $d ) = explode("-", $_REQUEST['end']);
				$this->end = mktime( 11,59,59,$m, $d, $Y );
			} catch (Exception $e){ 
				$this->end = mktime( 11,59,59,date('m'), date('t'), date('Y') );
			}  
		} else {
			$this->end = mktime( 11,59,59,date('m'), date('d'), date('Y') );
		} 
		
		$this->where .= " AND " . $colname . " >= '" . date( 'Y-m-d H:i:s', $this->since ) . "' "
						. " AND " . $colname . " <= '" . date( 'Y-m-d H:i:s', $this->end ) . "' ";
	}

	public function set_select_all_function($function, $params) {
		$this->sel_all_option = TRUE; 
		$this->sel_all_function = $function; 
		$this->sel_all_fcn_params = $params;
	} 
	  
	public function get_ul_list_html( $ajax = FALSE ){
		if (count($this->error) == 0 && $this->query != '' && $this->template_ul != "" ){
			global $Settings, $obj_db; 
			$query = $this->query 
						. " " . $this->where 
						. " " . $this->group 
						. " " . $this->sort;
			//echo $query;
			$q_cuantos = "SELECT count(*) as RecordCount FROM (" . $this->query_count . ") as cuenta ";
			$record = $obj_db->query($q_cuantos); 
			if ($record === FALSE) {
				$this->set_error('Ocurrió un error al contar los registros en la BD. ', LOG_DB_ERR, 1); 
				return FALSE; 
			}
			$this->total_records = (int) $record[0]["RecordCount"];
			$start = (($this->page - 1) * $this->rows); 
			if ($this->rows > 0) {
				$this->total_pages = ceil($this->total_records / $this->rows);
			} else {
				$this->total_pages = 0;
			}
			
			$limit = ""; 
			$result = $obj_db->query($query . $limit);
			if ($result !== FALSE) {
				if (!$ajax) 
					$this->get_list_header_html();
				if (count($result) > 0) {
                      $resp = "";
                      foreach ($result as $k => $record) {
						ob_start(); 
						require $this->template_ul;
						$resp .= ob_get_clean(); 
					} 
				} else {
					$resp = "<li> <div> No se encontraron registros. </div> <li>";
				} 
				if ($ajax) {
					return $resp; 
				} else {
                  echo $resp;
                  $this->get_list_foot_functions();
				}
			} else {
				$this->set_error('Ocurrió un error al obtener los registros de la BD', LOG_DB_ERR, 2);
				return false; 
			}
		}
	}  

	public function get_list_html($ajax = FALSE, $dates = FALSE ) {
		if (count($this->error) == 0 && $this->query != '' && $this->template != '') {
			global $Settings, $Session, $obj_db; 
			$query = $this->query 
						. " " . $this->where 
						. " " . $this->group;
						//. " " . $this->sort;
			//echo $query;
			$flag = TRUE;
			$q_cuantos = "SELECT count(*) as RecordCount FROM (" . $this->query_count . $this->where. ") as cuenta ";
			$record = $obj_db->query($q_cuantos);
			if ($record === FALSE) {
				$this->set_error("Ocurrió un error al contar los registros en la BD. " , ERR_DB_QRY, 1); 
				$flag = FALSE;
			}
			$this->total_records = (int) $record[0]["RecordCount"];
			$start = (($this->page - 1) * $this->rows);
			if ($this->rows > 0) {
				$this->total_pages = ceil($this->total_records / $this->rows); 
			} else {
				$this->total_pages = 0;
			} 
			$limit = " ) a WHERE a.row > ".$start." and a.row <= ".($this->rows * $this->page); 
			//$limit = ""; 
			$limit .= " " . $this->sort;
			$pre = " SELECT * FROM ( ";
			//echo $pre . $query . $limit;
			
			if (!$ajax) 
				$this->get_header_html( $dates );
			
			if ( $flag )
				$result = $obj_db->query( $pre . $query . $limit);
			else 
				$result = FALSE;
			 
			if ($result !== FALSE){ 
				if (count($result) > 0) {
                      $resp = "";
                      foreach ($result as $k => $record) {
                          ob_start();
                          require $this->template;
                          $resp .= ob_get_clean();
                      }
				} else {
                      $resp = "<tr> <td align='center' colspan='" . count($this->columns) . "'> No se encontraron registros. </td> <tr>";
				}
				if ($ajax) {
					return utf8_encode($resp); 
				} else {
                      echo utf8_encode($resp);
                      $this->get_foot_functions();
				}
			} else {
				 if ( !$ajax ){
				 	echo utf8_encode($resp);
                    $this->get_foot_functions();
				}
				$this->set_error('Ocurrió un error al obtener los registros de la BD. ' , ERR_DB_QRY, 2); 
				return false;
			}
		}
	}


	public function get_div_list_html($ajax = FALSE ) {
		if (count($this->error) == 0 && $this->query != '' && $this->template != '') {
			global $Settings, $Session, $obj_db; 
			$query = $this->query 
						. " " . $this->where 
						. " " . $this->group;
						//. " " . $this->sort;
			//echo $query;
			$flag = TRUE;
			$q_cuantos = "SELECT count(*) as RecordCount FROM (" . $this->query_count . $this->where. ") as cuenta ";
			$record = $obj_db->query($q_cuantos);
			if ($record === FALSE) {
				$this->set_error("Ocurrió un error al contar los registros en la BD. " , ERR_DB_QRY, 1); 
				$flag = FALSE;
			}
			$this->total_records = (int) $record[0]["RecordCount"];
			$start = (($this->page - 1) * $this->rows);
			if ($this->rows > 0) {
				$this->total_pages = ceil($this->total_records / $this->rows); 
			} else {
				$this->total_pages = 0;
			} 
			$limit = " ) a WHERE a.row > ".$start." and a.row <= ".($this->rows * $this->page); 
			//$limit = ""; 
			$limit .= " " . $this->sort;
			$pre = " SELECT * FROM ( ";
			//echo $pre . $query . $limit;
			
			if (!$ajax) 
				$this->get_header_div( );
			
			if ( $flag )
				$result = $obj_db->query( $pre . $query . $limit);
			else 
				$result = FALSE;
			
			
			$resp = "";
			if ($result !== FALSE){ 
				if (count($result) > 0) {
                      $resp = "";
                      foreach ($result as $k => $record) {
                          ob_start();
                          require $this->template;
                          $resp .= ob_get_clean();
                      }
				} else {
                      $resp = "<div class='art-item text-center'> No se encontraron registros. </div>";
				}
				if ($ajax) {
					return utf8_encode($resp); 
				} else {
                      echo utf8_encode($resp);
                      $this->get_foot_div_functions();
				}
			} else {
				 if ( !$ajax ){
				 	echo utf8_encode($resp);
                    $this->get_foot_div_functions();
				}
				$this->set_error('Ocurrió un error al obtener los registros de la BD. ' , ERR_DB_QRY, 2); 
				return false;
			}
		}
	}


	public function get_html_search( $list = FALSE) {
	?> 	<select id="inp_<?php echo $this->table_id ?>_srch_idx"> <?php
          foreach ($this->columns as $k => $col) {
              if ($col['searchable']) {
                  echo "<option value='" . $col['idx'] . "'>" . $col['lbl'] . "</option>";
              }
          }
	?> 	</select>
		<input type="text" id="inp_<?php echo $this->table_id ?>_srch_string">
		<button class="btn btn-default" onclick="<?php 
			echo "reset_table_page('" . $this->table_id . "');";
			echo ($list ) ? "load_next_page('" . $this->table_id . "', true);" : "reload_table('" . $this->table_id . "');" ;
			?>"><i class="fa fa-search"></i></button>
	<?php
	}

	private function get_header_html( $dates = FALSE ){
		if ( is_array($this->columns) ) { ?>
		<thead>
			<tr> <td colspan="<?php echo count($this->columns) ?>"> 
					<div class="row"> 
						<div class="col-xs-12 text-center"> <h4 id='lbl_title'><?php echo $this->title ?></h4> </div> 
						<div class="col-xs-6"> 
							Buscar 
							<?php $this->get_html_search(); ?>
						</div> 
						<div class="col-xs-6 text-right"> 
							<span id='<?php echo $this->table_id ?>_lbl_foot' > 
								<?php echo $this->get_foot_records_label(); ?>
							</span> 
							<select id="inp_<?php echo $this->table_id ?>_rows" name="<?php echo $this->table_id ?>_rows" onchange="reload_table('<?php echo $this->table_id ?>');"> 
								<option value="25"  <?php $this->rows == 25 ? "selected='selected'" : "" ?>>25</option> 
								<option value="50"  <?php $this->rows == 50 ? "selected='selected'" : "" ?>>50</option> 
								<option value="100" <?php $this->rows == 100 ? "selected='selected'" : "" ?>>100</option>   
							</select> 
							registros por página. 
							<button class="btn btn-default" onclick="reload_table('<?php echo $this->table_id ?>');"><i class="fa fa-refresh"></i></button>
							
							<button class="btn btn-default" onclick="export_table_xls('<?php echo $this->table_id ?>');"><i class="fa fa-download"></i></button> 
						</div>
					</div>
					<div class="row">
						<?php if ( $this->dates ){ ?>
						<div class="col-xs-6 col-sm-2 ">
							<span> Desde </span>
							<input type="date" pattern="yyyy-mm-dd" id="inp_<?php echo $this->table_id ?>_since" name="since" value="<?php echo date('Y-m-d', $this->since) ?>" 
									class=" form-control" style="padding: 0 5px; width:100px; " /> 
						</div>
						<div class="col-xs-6 col-sm-2 ">
							<span> Hasta </span>
							<input type="date" pattern="yyyy-mm-dd" id="inp_<?php echo $this->table_id ?>_end" name="end" value="<?php echo date('Y-m-d', $this->end) ?>" 
									class=" form-control" style="padding: 0 5px; width:100px; " /> 
						</div> 
						<?php } ?>
						<div class=""></div>
						<?php //$this->get_select_all_functions() ?>
					</div>
				</td>
			</tr>
			<tr>
			<?php
              foreach ($this->columns as $k => $col) {
                  $sort_cls = "";
                  $sort_func = "";
                  if ($col['sortable']) {
                      $sort_cls = "sortable sorting";
                      $sort_dir = "ASC";
                      if ($this->sidx == $col['idx']) {
                          $sort_cls .= ( $this->sord == 'DESC') ? "_asc" : "_desc";
                          $sort_dir = ( $this->sord == 'DESC') ? "ASC" : "DESC";
                      }
                      $sort_func = "onclick='sort_table(\"" . $this->table_id . "\", \"" . $col['idx'] . "\", \"" . $sort_dir . "\" )'";
                  }
                  echo " <th id='" . $this->table_id . "_hd_" . $col['idx'] . "' class='" . $this->table_id . "_head " . $sort_cls . "' " . $sort_func
                  . ( isset($col['width']) && $col['width'] != '' ? " style='width:" . $col['width'] . ";'" : "" )
                  . " > " . $col['lbl']
                  . "</th>";
              }
			?></tr>
			</thead> 
			<tbody id="<?php echo $this->table_id ?>_tbody" > <?php
		}
	}

	/**
	 * get_header_div
	 * writes the HTML for the Div List Header
	 *  
	 */
	public function get_header_div( ){
		if ( is_array($this->columns) ) { ?>
			<div class="art-item"> <?php 
              foreach ($this->columns as $k => $col) {
                  $sort_cls = "";
                  $sort_func = "";
                  if ($col['sortable']) {
                      $sort_cls = "sortable sorting";
                      $sort_dir = "ASC";
                      if ($this->sidx == $col['idx']) {
                          $sort_cls .= ( $this->sord == 'DESC') ? "_asc" : "_desc";
                          $sort_dir = ( $this->sord == 'DESC') ? "ASC" : "DESC";
                      }
                      $sort_func = "onclick='sort_table(\"" . $this->table_id . "\", \"" . $col['idx'] . "\", \"" . $sort_dir . "\" )'";
                  }
                  echo " <div id='" . $this->table_id . "_hd_" . $col['idx'] . "' " 
                  		. " class='art-" . $col['cls'] . " " . $this->table_id . "_head " . $sort_cls . "' " . $sort_func
                  		. ( isset($col['width']) && $col['width'] != '' ? " style='width:" . $col['width'] . ";'" : "" )
                  		. " > <p> " . $col['lbl'] . " </p> "
                  	. "</div>";
              } ?>  
				<input type="hidden" id="inp_<?php echo $this->table_id ?>_page" name="<?php echo $this->table_id ?>_page" value="<?php echo $this->page ?>" />
				<input type="hidden" id="inp_<?php echo $this->table_id ?>_sord" name="<?php echo $this->table_id ?>_sord" value="<?php echo $this->sord ?>" />
				<input type="hidden" id="inp_<?php echo $this->table_id ?>_sidx" name="<?php echo $this->table_id ?>_sidx" value="<?php echo $this->sidx ?>" />
				<input type="hidden" id="inp_<?php echo $this->table_id ?>_fval" name="<?php echo $this->table_id ?>_fval" value="<?php echo $this->fval ?>" />
				<input type="hidden" id="inp_<?php echo $this->table_id ?>_fidx" name="<?php echo $this->table_id ?>_fidx" value="<?php echo $this->fidx ?>" />
				<input type="hidden" id="inp_<?php echo $this->table_id ?>_exfval" name="<?php echo $this->table_id ?>_exfval" value="<?php echo $this->exfval ?>" />
				<input type="hidden" id="inp_<?php echo $this->table_id ?>_exfidx" name="<?php echo $this->table_id ?>_exfidx" value="<?php echo $this->exfidx ?>" />
				<input type="hidden" id="inp_<?php echo $this->table_id ?>_extra" name="<?php echo $this->table_id ?>_extra" value="<?php echo $this->extra ?>" />
				<input type="hidden" id="inp_<?php echo $this->table_id ?>_rows" name="<?php echo $this->table_id ?>_rows" value="<?php echo $this->rows ?>" />
				<input type="hidden" id="inp_<?php echo $this->table_id ?>_list" name="<?php echo $this->table_id ?>_list" value="<?php echo $this->which ?>" />
				<input type="hidden" id="inp_<?php echo $this->table_id ?>_cols" name="<?php echo $this->table_id ?>_cols" value="<?php echo count($this->columns) ?>" />
				<input type="hidden" id="inp_<?php echo $this->table_id ?>_tpages" name="<?php echo $this->table_id ?>_tpages" value="<?php echo $this->total_pages ?>" />
			</div>
	<?php }  
	}


	private function get_list_header_html(){
		if ( is_array($this->columns) ) { ?>
		<section id="<?php echo $this->table_id ?>_header">
			<div class="row"> 
				<div class="col-xs-12 text-center"> <h4 id='lbl_title'><?php echo $this->title ?></h4> </div> 
				<div class="col-xs-6"> 
					Buscar 
					<?php $this->get_html_search( TRUE ); ?>
					<input type="hidden" id="inp_<?php echo $this->table_id ?>_page" name="<?php echo $this->table_id ?>_page" value="<?php echo $this->page ?>" />
					<input type="hidden" id="inp_<?php echo $this->table_id ?>_sord" name="<?php echo $this->table_id ?>_sord" value="<?php echo $this->sord ?>" />
					<input type="hidden" id="inp_<?php echo $this->table_id ?>_sidx" name="<?php echo $this->table_id ?>_sidx" value="<?php echo $this->sidx ?>" />
					<input type="hidden" id="inp_<?php echo $this->table_id ?>_fval" name="<?php echo $this->table_id ?>_fval" value="<?php echo $this->fval ?>" />
					<input type="hidden" id="inp_<?php echo $this->table_id ?>_fidx" name="<?php echo $this->table_id ?>_fidx" value="<?php echo $this->fidx ?>" />
					<input type="hidden" id="inp_<?php echo $this->table_id ?>_exfval" name="<?php echo $this->table_id ?>_exfval" value="<?php echo $this->exfval ?>" />
					<input type="hidden" id="inp_<?php echo $this->table_id ?>_exfidx" name="<?php echo $this->table_id ?>_exfidx" value="<?php echo $this->exfidx ?>" />
					<input type="hidden" id="inp_<?php echo $this->table_id ?>_extra" name="<?php echo $this->table_id ?>_extra" value="<?php echo $this->extra ?>" />
					<input type="hidden" id="inp_<?php echo $this->table_id ?>_rows" name="<?php echo $this->table_id ?>_rows" value="<?php echo $this->rows ?>" />
					<input type="hidden" id="inp_<?php echo $this->table_id ?>_list" name="<?php echo $this->table_id ?>_list" value="<?php echo $this->which ?>" />
					<input type="hidden" id="inp_<?php echo $this->table_id ?>_cols" name="<?php echo $this->table_id ?>_cols" value="<?php echo count($this->columns) ?>" />
					<input type="hidden" id="inp_<?php echo $this->table_id ?>_tpages" name="<?php echo $this->table_id ?>_tpages" value="<?php echo $this->total_pages ?>" />
				</div> 
			</div> 
		</section> 
		<section id="<?php echo $this->table_id ?>_body" ><?php
		}
	}

	/**
	 * get_foot_div_functions 
	 * 
	 */
	private function get_foot_div_functions(){
		
	}

	private function get_list_foot_functions() {
	?>	</section> <?php
	}

	private function get_select_all_functions() {
		if ($this->sel_all_option === TRUE) { ?>
			<div class="col-xs-6 text-right">
				Seleccionar Todo&nbsp;&nbsp;
				<input type="checkbox" id="inp_select_all_<?php echo $this->table_id ?>"
					onchange="<?php 
						echo $this->sel_all_function . " ("; 
						$this->sel_all_fcn_params[] = "'inp_select_all_" . $this->table_id . "'"; 
						$params = implode(',', $this->sel_all_fcn_params);
						echo $params . ")"
						?>" />
			</div><?php
          }
	}

	public function get_foot_records_label() {
          $start = (($this->page - 1) * $this->rows);
          $stop = $start + $this->rows;
          $stop = ( $stop <= $this->total_records ) ? $stop : $this->total_records;
          return sprintf($this->showing_template, $start + 1, $stop, $this->total_records);
	}

	private function get_foot_functions() {
	?>	</tbody> 
		<tfoot> 
			<tr>
				<td colspan="<?php echo count($this->columns) ?>">
					<div class="row"> 
						<div class="col-xs-6" style="margin-top: 10px;" > 
							<input type="hidden" id="inp_<?php echo $this->table_id ?>_sord" name="<?php echo $this->table_id ?>_sord" value="<?php echo $this->sord ?>" />
							<input type="hidden" id="inp_<?php echo $this->table_id ?>_sidx" name="<?php echo $this->table_id ?>_sidx" value="<?php echo $this->sidx ?>" />
							<input type="hidden" id="inp_<?php echo $this->table_id ?>_fval" name="<?php echo $this->table_id ?>_fval" value="<?php echo $this->fval ?>" />
							<input type="hidden" id="inp_<?php echo $this->table_id ?>_fidx" name="<?php echo $this->table_id ?>_fidx" value="<?php echo $this->fidx ?>" />
							<input type="hidden" id="inp_<?php echo $this->table_id ?>_exfval" name="<?php echo $this->table_id ?>_exfval" value="<?php echo $this->exfval ?>" />
							<input type="hidden" id="inp_<?php echo $this->table_id ?>_exfidx" name="<?php echo $this->table_id ?>_exfidx" value="<?php echo $this->exfidx ?>" />
							<input type="hidden" id="inp_<?php echo $this->table_id ?>_extra" name="<?php echo $this->table_id ?>_extra" value="<?php echo $this->extra ?>" />
							<input type="hidden" id="inp_<?php echo $this->table_id ?>_rows" name="<?php echo $this->table_id ?>_rows" value="<?php echo $this->rows ?>" />
							<input type="hidden" id="inp_<?php echo $this->table_id ?>_list" name="<?php echo $this->table_id ?>_list" value="<?php echo $this->which ?>" />
							<input type="hidden" id="inp_<?php echo $this->table_id ?>_cols" name="<?php echo $this->table_id ?>_cols" value="<?php echo count($this->columns) ?>" />
							<input type="hidden" id="inp_<?php echo $this->table_id ?>_tpages" name="<?php echo $this->table_id ?>_tpages" value="<?php echo $this->total_pages ?>" /> 
						</div> 
						<div class="col-xs-6 text-right"> 
							<div class="datatable-paginate"> 
								<ul class="pagination"> 
									<li <?php echo ( $this->page > 1 ) ? "" : "class=''"; ?> > 
										<a href="#" onclick="move_page('<?php echo $this->table_id ?>','f');"><i class="fa fa-angle-double-left"></i></a>
									</li> 
									<li <?php echo ( $this->page > 1 ) ? "" : "class=''"; ?> >
										<a href="#" onclick="move_page('<?php echo $this->table_id ?>','p');"><i class="fa fa-angle-left"></i></a>
									</li> 
									<li> 
										<a href="#">
											<span>
                                              Página <input id="inp_<?php echo $this->table_id ?>_page" name="page" value="<?php echo $this->page; ?>"  />
                                              <button style='margin-left: -5px;' onclick="reload_table('<?php echo $this->table_id ?>');"><i class="fa fa-gear"></i></button> de 
                                              <span id="<?php echo $this->table_id ?>_lbl_tpages"><?php echo $this->total_pages; ?></span>
											</span> 
										</a> 
									</li> 
									<li <?php echo ( $this->page < $this->total_pages ) ? "" : "class='disabled'"; ?> > 
										<a href="#" onclick="move_page('<?php echo $this->table_id ?>','n');"><i class="fa fa-angle-right"></i></a>
									</li>
									<li <?php echo ( $this->page < $this->total_pages ) ? "" : "class='disabled'"; ?> >
										<a href="#" onclick="move_page('<?php echo $this->table_id ?>','l');"><i class="fa fa-angle-double-right"></i></a>
									</li> 
								</ul> 
							</div> 
						</div>
					</div>
				</td>
			</tr>
			<?php if( $this->sel_all_option == TRUE ){ ?>
			<tr id="foot-actions">
				<td colspan="<?php echo count($this->columns) ?>">
					<a href="javascript:void(0);" class="select_all_rows" 
						onclick="select_all_rows( this );">Seleccionar todo</a>  
				</td>
			</tr>	
			<?php } ?>
		</tfoot> <?php
	}

	public function get_list_xml() {
		if (count($this->error) == 0 && $this->query != '' && $this->template != '') {
			global $Settings, $obj_db;
			$consulta = $this->query 
						. " " . $this->where 
						. " " . $this->group 
						. " " . $this->sort; 
			$cuantos = $obj_db->query("SELECT count(*) as RecordCount FROM (" . $this->query_count . " " . $this->where . ") as cuenta");
			$many = $cuantos[0]; 
			$total = (int) $many["RecordCount"];
			if ($total > 0) { $total_pages = ceil($total / $this->rows); }
			else { $total_pages = 0; } 
			
			$start = (($this->page - 1) * $this->rows);
			$limit = " OFFSET " . $start . " ROWS FETCH NEXT " . $this->rows. "ROWS ONLY";
			$limit = "";

			$result = $obj_db->query($consulta . $limit);
			if ($result !== FALSE) {
				$this->set_template(true); 
				$this->set_xml_header(); 
				echo '<?xml version="1.0" encoding="utf-8"?>' . "\n"; 
				echo "<rows>\n"; 
				echo "<page>" . $this->page . "</page>\n"; 
				echo "<total>" . $total_pages . "</total>\n"; 
				echo "<records>" . $total . "</records>\n";
				foreach ($result as $k => $record) {
					require $this->template; 
				} 
				echo "</rows>";
			}
		}
	}

	public function get_list_xls() { 
		if (count($this->error) == 0 && $this->query != '') {
			global $Session,$Settings,$obj_db;
			
			$query = $this->query 
					. " " . $this->where 
					. " " . $this->group 
					. " " . $this->sort;
					//. " LIMIT 0,5";			

			$result = $obj_db->query($query);

			if ($result !== FALSE){
				$xls = new PHPExcel();
				// Set document properties
				$xls->getProperties()->setCreator(SYS_TITLE) 
						->setTitle($this->title) 
						->setSubject($this->title) 
						->setDescription($this->title . " al día " . date('Y-m-d')) 
						->setKeywords("") 
						->setCategory($this->title);
				// Rename worksheet
				$xls->getActiveSheet()->setTitle($this->title); 
				$xls->getDefaultStyle() 
						->getAlignment() 
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$xls->setActiveSheetIndex(0);
 
				/*    CABECERA    */ 
				$last_letter = "A"; 
				$last_row = 1;
				foreach ($this->columns as $k => $col ) {
					$ltr = $this->get_column_letter( $k );
					$width = isset( $col['width'] ) && is_numeric( $col['width'] ) ? $col['width'] : 30 ; 
					
					$xls->getActiveSheet()->getColumnDimension( $ltr )->setWidth($width);
					$xls->getActiveSheet()->setCellValue( $ltr . '1', $col['lbl']);
					
					$last_letter = $ltr;
				} 
				$columna = "A1:" . $last_letter . "1"; 
				$estilo_header = $this->get_estilo_xls('header');
				$xls->getActiveSheet()->setSharedStyle($estilo_header, $columna);			
	  
				foreach ($result as $k => $val) {
                  	$r = $k + 2; 
					foreach ($this->columns as $k => $col) {
						
						$value = $val[$col['idx']];
						 
						switch ( $col['format'] ) {
							case 'Date':
								 	//PHPExcel_Shared_Date::PHPToExcel($timestamp)
									//$xls->getActiveSheet()->setCellValueExplicit($this->get_column_letter($k) . $r, $val[$col['idx']], PHPExcel_Cell_DataType::TYPE_STRING); 
									list( $Y, $m, $d ) = explode('-',$value ); 
									$t_date   = PHPExcel_Shared_Date::FormattedPHPToExcel($Y, $m, $d);
									$time = mktime( 8,0,0, $m, $d, $Y ); 
									$clm = $this->get_column_letter($k);									
									if ( $time > 0 ){
										//$xls->getActiveSheet()->setCellValueByColumnAndRow( $clm , $r, $t_date );
										$xls->getActiveSheet()->setCellValueExplicit( $clm . $r, $t_date , PHPExcel_Cell_DataType::TYPE_NUMERIC);  
									}  
									break;
							case 'Integer':
							case 'Float':
							case 'Money':
							case 'Percentage':
								$xls->getActiveSheet()->setCellValueExplicit($this->get_column_letter($k) . $r, $val[$col['idx']], PHPExcel_Cell_DataType::TYPE_NUMERIC);
								break;
							default:
								$xls->getActiveSheet()->setCellValueExplicit($this->get_column_letter($k) . $r, utf8_encode($val[$col['idx']]), PHPExcel_Cell_DataType::TYPE_STRING); 
								break;
						} 
						// $objPHPExcel->getActiveSheetIndex()->getStyle('A1')->getNumberFormat()->setFormatCode('dd-mm-yyyy');
						$last_row = $last_row < $r ? $r : $last_row;
					}
					
					//set date formats
					foreach ($this->columns as $k => $col ) {
						$ltr = $this->get_column_letter( $k );
						$range = $ltr . '2:' . $ltr . $last_row;
						switch  ( $col['format'] ){
							case 'Date': 
								$xls->getActiveSheet()->getStyle($range)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14 );
								break;
							case 'Money':
								$xls->getActiveSheet()->getStyle($range)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE );
								break;
							case 'Percentage':
								$xls->getActiveSheet()->getStyle($range)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE );
								break;
						}
					}
					
				}

				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="' . str_replace(array(" ","/"), "_", strtolower( $this->title )) . '.xlsx"');
				header('Cache-Control: max-age=0');
				$objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel2007');
				$objWriter->save('php://output');
				die();
			}
		}
	}
 
	private function get_column_letter( $k = 0 ){
		$letters = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"); 
		$pre = "";
		$times = floor( $k / count( $letters ) );
		if ( $times > 0 ){
			$pre = $letters[ $times - 1 ];
		}
		$idx = $k % count( $letters );
		$ltr = $pre . $letters[ $idx ]; 
		return $ltr;
	}
 
	private function set_header_xls($xls) {
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="reporte-prospectos.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel2007');
		$objWriter->save('php://output');
		die();
	}

	private function get_estilo_xls($cual) {
		$resp = new PHPExcel_Style(); 
		if ($cual == 'header') {
			$resp->applyFromArray(
						array(	'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('argb' => 'CCCCCC')),
								'font' => array('bold' => true, 'color' => array('argb' => '000000'), 'size' => '11'),
								'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)
                      )
			);
		} else {
			$resp->applyFromArray( 
						array(	'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('argb' => 'FFFFFF')),
								'font' => array('bold' => false, 'color' => array('argb' => '000000'), 'size' => '11'), 
								'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)
			));
		} 
		return $resp;
	}

	private function set_header_xml() {
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
		header("Cache-Control: no-cache, must-revalidate");
		header("Pragma: no-cache");
		header("Content-type: text/xml");
	}

	public function get_array($query = "") {
		if(empty($query)){
			if (count($this->error) == 0 && $this->query != '') {
				$query = $this->query 
					. " " . $this->where 
					. " " . $this->group 
					. " " . $this->sort;
			}
		}
		global $Settings, $obj_db; 
		$results = $obj_db->query($query);
		if ($results !== FALSE) {
			return $results;
		}
		else {
			$this->set_error('Ocurrió un error al obtener los registros de la BD', LOG_DB_ERR, 2);
			return FALSE;
		}
	}

	public function clean() {
		$this->where = ""; 
		$this->columns = array(); 
		$this->error = array();
	}

	public function get_errors($break = "<br/>") {
		$resp = ""; 
		if (count($this->error) > 0) {
			foreach ($this->error as $k => $err) 
				$resp .= " ERROR @ Class DataTable: " . $err . $break;
		} 
		return $resp;
	}

	private function set_error($err, $type, $lvl = 1) {
		global $Log;
		$this->error[] = $err;
		$Log->write_log(" ERROR @ Class DataTable : " . $err, $type, $lvl);
	}
}
?>