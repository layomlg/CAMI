<?php
/**
 * Class Chart
 * 
 */
 class Chart extends Object {

	protected $type;  
	public $div_id;
	
	public $title;
	public $options; 
	public $columns;
	public $rows; 
	public $colors;
	public $chartObject;  
	
	/**
	 * __construct
	 * 
	 * @param $type	String defines the chart source to use
	 * @param $div_id	(Optional) String ID of the container where the chart will load, if not set #type will be used
	 */
	function __construct($type, $id = ''){
		$this->class = "Chart";
		$this->type = $type; 
		$this->set_chart();
		if ( $id != '' )
			$this->div_id = $id;
		else 
			$this->div_id = 'chart_' . $this->type;
		
		$this->options = new stdClass;
		$this->options->title	= "";
		$this->options->height 	= 500;
		$this->options->width 	= 600; 
		
		$this->colors = array(
							'#3366CC', // Azul
							'#FF0000', // Rojo
							'#109618', // Verde
							'#FF9900', // Amarillo
							'#990099', // Morado
							'#5bc0de', // Azul Claro 
							'#999999', // Gris
							'#E67E22', // Naranja
							'#34495E', // Azul Marino
							'#1ABC9C', // tuquesa
						);
	}
	
	
	/**
	 * set_chart
	 * sets chart type data 
	 * 
	 * @access 	public 
	 */
	public function set_chart( ){
		switch ( $this->type ) {
			case 'pie':
				$this->type = "pie";
				$this->chartObject = "PieChart";
				break;
			case 'line':
				$this->type = "line";
				$this->chartObject = "LineChart";
				break;
			case 'bar': 
				$this->type = "bar";
				$this->chartObject = "BarChart";
				break;
			case 'stepped_area': 
				$this->type = "stepped_area";
				$this->chartObject = "SteppedAreaChart";
				break;
			case 'area': 
				$this->type = "area";
				$this->chartObject = "AreaChart";
				break;
			case 'column':
			default:
				$this->type = "column";
				$this->chartObject = "ColumnChart";
				break;
		}
	}
	
	/**
	 * set_rows()
	 * Sets the Chart Datatable Rows
	 * 
	 * @access	public
	 * @param	Array $rows 
	 * @return 	TRUE on success | False Otherwise
	 */
	 public function set_rows( $rows ){
	 	$this->rows = array();
		if ( is_array( $rows ) && count($rows) > 0 ){ 
			foreach ( $rows as $k => $row ){
				$line = array();
				$line[0] = $row[0] . '';
				
				for ($i=1; $i < count($row); $i++) { 
					$line[] = $row[$i] + 0;
				} 
				$line[] =  $this->colors[abs( count($this->colors) - $k )];
				$this->rows[] = $line; 
			}   
			
		} else { 
			$this->set_error("Invalid rows array", ERR_VAL_INVALID); 
		}
	 }
	 
	 
	/**
	 * set_columns()
	 * Sets Chart Datatable Columns
	 * 
	 * @access	public
	 * @param	Array $columns
	 * @return 	TRUE on success | False Otherwise
	 */
	 public function set_columns( $columns ){
	 	$this->columns = array();
		if ( is_array( $columns ) && count($columns) > 0 ){ 
			foreach ( $columns as $k => $col ){ 
				$this->columns[] = $col;
			}   
		} else { 
			$this->set_error("Invalid columns array", ERR_VAL_INVALID); 
		}
	 }
	
	/**
	 * get_data_array_string
	 * 
	 * @access 	public
	 * @return	String google.visualization.arrayToDataTable  
	 */
	 public function get_data_array_string(){
	 	if ( count($this->columns) > 0 && count($this->rows) > 0){
	 		
			$resp = "[";
			//header
			$resp .= "[";
			foreach ($this->columns as $k => $col) {
				$resp .= ( $k>0 ? "," : "" ) . "'" . $col . "'";
			}
			$resp .= ", { role: 'style' }]";
			
			//data
			foreach ($this->rows as $k => $row) {
				$resp .= ",[";
				foreach ($row as $j => $val) {
					$resp .= ( $j>0 ? "," : "" ) . "" . ( is_numeric($val) ? $val : "'" . $val . "'" ) . "";
				}
				$resp .= "]"; 
			}
			$resp .= "]";
			return $resp;
	 	} 
	 	else return "[]";
	 }
	
	
	/**
	 * draw_chart
	 * 
	 * @access	public
	 */
	public function draw_chart(){
		$data_string = $this->get_data_array_string();
		$resp =  " <div id='" . $this->div_id . "'></div>"
			. "<script type='text/javascript'>" 
				. " var data_array_" . $this->div_id . " = " . $data_string . "; "
				. " var chart_options = {	'title': '" . $this->options->title . "', " 
									. " 'width': " . ($this->options->width + 0) . ", " 
									. " 'height':" . ($this->options->height + 0) . "  " 
					. " }; "
				// Load the Visualization API and the piechart package. 
				. "google.load('visualization', '1.0', {'packages':['corechart']});"  
				// Set a callback to run when the Google Visualization API is loaded. 
				. "google.setOnLoadCallback(drawChart);" 
				// Callback that creates and populates a data table, 
				// instantiates the pie chart, passes in the data and draws it. 
				. "function drawChart() { " 
					. " var data = google.visualization.arrayToDataTable( data_array_" . $this->div_id . " ); " 
					// Set chart options 
			        // Instantiate and draw our chart, passing in some options.
			        . " var chart = new google.visualization." . $this->chartObject . "(document.getElementById('" . $this->div_id . "')); "
			        . " chart.draw(data, chart_options); "
				. "}"
		    . "</script>";
		return $resp;
	}	

	/**
	 * ajax_response
	 * 
	 * @access 	public
	 * @return 	array containing ajax information to draw the chart
	 */
	 public function ajax_response(){
	 	$response = array();
	 	$response['data_array']	= $this->get_data_array_string(); 
		$response['options']	= array( 
									'title'   => $this->options->title,
									'width'   => $this->options->width,
									'height'  => $this->options->height  
								); 
		$response['options']	= $this->div_id;
		$response['type']		= $this->chartObject;
		
		return $response;
	 }
}
?>