<?php
/**
 * class Meta
 * General Class for Meta information structure
 * 
 */
class Meta extends Objecto {
	
	public $options = array();
	public $error 	= array();
	
	protected $type = "";
	protected $template = "";
	
	function __construct( ) {
		
	}
	
	/**
	 * get_option 
	 * 
	 * @param 	String $id for the requested option (Required)
	 * @return  Mixed stdClass $option MetaOption information; False if option is not found. 
	 */	 
	public function get_option( $id ){
		foreach ($this->options as $k => $option) {
			if ( $option->id_option == $id )
				return $option;
		}
		return FALSE;
	}
	
	/**
	 * get_frm_control 	
	 * returns the html text for the meta option form control 
	 * 
	 * @param	$option stdClass $option MetaOption information (Required) 
	 * @param 	$pfx String prefix for the html control id
	 * @param 	$css String CSS class to use for the control  
	 * 
	 * @return 	String html for the control 
	 */
	public function get_frm_control( $option, $pfx = "inp_", $css = "" ){
		$resp = "";
		switch ( $option->id_data_type ){
			case 1: //Binary 
				$resp .= "<div class='row'><div class='col-xs-12'><div class='input-group col-xs-5 pull-left'><span class='input-group-addon'> "
							. " <input type='radio' name='" . $this->type . "_option_" . $option->id_option . "' id='" . $pfx . "" . $this->type . "_option_" . $option->id_option . "_t' "
							. " value='true' " . ( $option->value ? "checked='checked'" : "" ) . "  " . ( $option->required ? "required=''required" : "" ) . " />" 
						. "</span><label class='form-control'> Verdadero </label></div>" 
					  	. "<div class='input-group col-xs-5 pull-right'><span class='input-group-addon'> "
							. " <input type='radio' name='" . $this->type . "_option_" . $option->id_option . "' id='" . $pfx . "" . $this->type . "_option_" . $option->id_option . "_f' "
							. " value='false' " . ( $option->value === false ? "checked='checked'" : "" ) . " " . ( $option->required ? "required=''required" : "" ) . " />" 
						. " </span><label class='form-control'> Falso </label></div></div></div>";
				break;
			case 2: //Text
			case 3: //Number
			case 4: //Date
			case 5: //Email
			case 6: //Float
				$resp = "<input type='" . strtolower( $option->data_type ) . "' id='" . $pfx . "" . $this->type . "_option_" . $option->id_option . "' name='" . $this->type . "_option_" . $option->id_option . "' "
								. " class='form-control " . $css . "' value='" . $option->value . "'  " . ( $option->required ? "required=''required" : "" ) . " />";
				break;
			case 7: //RadioOption
				$opts = explode( ';', $option->options );
				foreach ($opts as $k => $op) {
					$resp .= "<div class='input-group'><span class='input-group-addon'> "
								. " <input type='radio' name='" . $this->type . "_option_" . $option->id_option . "' id='" . $pfx . "" . $this->type . "_option_" . $option->id_option . "_" . $k . "' "
								. " value='" . $op . "' " . ( $option->value ? "checked='checked'" : "" ) . " " . ( $option->required ? "required=''required" : "" ) . " />" 
							. " </span><label class='form-control'>" . $op . "</label></div>"; 
				}  
				break;
			case 8: //CheckOption
				$opts = explode( ';', $option->options );
				$vals = explode( ';', $option->value );
				foreach ($opts as $k => $op) {
					$resp .= "<div class='input-group'><span class='input-group-addon'> "
								. " <input type='checkbox' name='" . $this->type . "_option_" . $option->id_option . "[]' id='" . $pfx . "" . $this->type . "_option_" . $option->id_option . "_" . $k . "' "
										. " value='" . $op . "' " . ( in_array($option->value, $opts) ? "checked='checked'" : "" ) . " />"  
							. " </span><label class='form-control'>" . $op . "</label></div>"; 
				}   
				break;
			case 9: //Select Option
				$opts = explode( ';', $option->options );
				$resp = "<select id='" . $pfx . "" . $this->type . "_option_" . $option->id_option . "' name='" . $this->type . "_option_" . $option->id_option . "'"
						. " class='form-control " . $css . "'  " . ( $option->required ? "required=''required" : "" ) . "  >";
				$resp .= "<option value='' " . ( $option->value == '' ? "selected='selected' " : "" ) . "> --- </option>";
				foreach ($opts as $k => $op) {
					$resp .= "<option value='" . $op . "' " . ( $option->value == $op ? "selected='selected' " : " " ) . ">" . $op . "</option>";
				}
				$resp .= "</select>";
				break;
			default:
				$resp = "";
				break;
		} 	
		return $resp;
	}
	
	/***
	 * get_from_html
	 * Builds the html for all the Meta Options in the class 
	 *    
	 * @param 	$pfx String prefix for the html controls ids
	 * @param 	$div_css String CSS class to use for the form div container
	 * @param 	$div_css String CSS class to use for the controls of the form 
	 * 
	 * @return 	String html for the form   
	 */
	public function get_frm_html( $pfx = "inp_", $div_css = "row", $inp_css = "" ){
		$resp = "";
		$last = count( $this->options ) - 1;
		foreach ($this->options as $key => $option) {
			$resp .= (($key%2 == 0) ? "<div class='row'>" : "") 
					. "<div class='" . $div_css . "' >"
					. "<label class='control-label'>" . $option->label .  "</label>"
					. $this->get_frm_control( $option, $pfx, $inp_css ) 
					. "</div>"
					. (( $key%2>0 || $key == $last ) ? "</div>" : "") ;
		}
		return $resp;
	}
	
	/**
	 * get_options_list_html
	 * returns an html list for the Meta options
	 * 
	 * @param 		$edit Boolean if TRUE edition buttons will be added to the HTML li, 
	 * 
	 * @return 		String HTML list  
	 */
	public function get_options_list_html( $edit = FALSE ){
		$resp = "";
		$li_css = "row";
		global $Session ; 
		$allow = $Session->permit ? true : false; 
		foreach ($this->options as $k => $option) {
			$resp .= "<li class='list-group-item  $li_css '> 
						<div class='col-xs-12 col-sm-3'>
							<div class='row'>
								<label class='col-xs-12' >Etiqueta:</label> 
								<span  class='col-xs-12' >  $option->label </span>
							</div>
						</div> 
						<div class='col-xs-12 col-sm-3'>
							<div class='row'>
								<label class='col-xs-12' >Tipo de dato:</label>
								<span  class='col-xs-12' >  $option->data_type </span>
							</div> 
						</div>
						<div class='col-xs-12 col-sm-3'>
							<div class='row'>
								<label class='col-xs-12' >Opciones:</label>
								<span class='col-xs-12' >  $option->options </span> 
							</div>
						</div> 
						<div class='col-xs-12 col-sm-3 text-center'>"; 
						if ($edit && $allow ){ 
							$resp .= "<button onclick='edit_option( $option->id_option )' class='btn btn-default'  ><i class='fa fa-edit'></i> Editar </button> 
							<button onclick='delete_option( $option->id_option )' class='btn btn-default'><i class='fa fa-trash-o'></i> Borrar </button>
							<br/>";
						 }  
					$resp .= " </div>
					</li>";
		} 
		return $resp;
	}
	
	/**
	 * get_values_list 
	 * returns an HTML list of options and values pairs 
	 * 
	 * @param 	$div_css String CSS class to be used for the list container  
	 * @param 	$lbl_css String CSS class to be used for the option label string  
	 * @param	$val_css String CSS class to be used for the option value string  
	 * 
	 */
	public function get_values_list( $div_css = 'col-xs-12 col-sm-6', $lbl_css = 'col-xs-4', $val_css = ''  ){
		$resp = "";
		$li_css = "row";
		foreach ($this->options as $k => $option) {
			$resp .= " <div class='" . $div_css . "'><p>"
					. "<label class='" . $lbl_css . "'>" . $option->label .  ":</label>"
					. "<span class='" . $val_css . "'>" . $option->value .  "</span>"
					. "</p></div>"; 
		} 
		return $resp;
	}
}
?>