<?php
/**
* Objecto CLass
* 
* @package		CAMI			
* @since        09/11/2018
* @author		Manuel Fernández
*/ 

abstract class Objecto{
	
	protected 	$class;
	public 		$error = array(); 
	
	function __construct(){
		$error = array();
	}
	
	/**
	* set_error()
	* Adds the error to the errors array and writes the error to Log
	* 
	* @param         $err String 
	* @param 		 $type Integer
	* @param 		 $lvl Integer (optional) 
	* 
	*/  
	protected function set_error( $err , $type, $lvl = 1, $id = 0 ){
		global $Log;
		$this->error[] = $err;
		$Log->write_log( $type . " ERROR @ Class " . $this->class . ( $id > 0 ? " (" . $this->id_contact . "): " : " " ) . $err, $type, $lvl );
	} 
	
	/**
	* get_errors()
	* Adds the error to the errors array and writes the error to Log
	* 
	* @param        $html Boolean
	* 
	* @return		String of concatenated errors.  
	*/  
	public function get_errors( $html = true ){
		$resp = ""; 
		if ( count( $this->error ) > 0 ) {
			foreach ( $this->error as $key => $err_str ) {
				$resp .= $err_str . ( ( $html ) ? "<br/>" : "\n");
			}
		}
		return $resp;
	}
	
	/**
	* set_msg()
	* Writes the message to the Log, optionally adds the message to the global message string.
	* 
	* @param         $action String 
	* @param 		 $msg String
	* @param 		 $echo String (optional) if set adds message to global variable.  
	* 
	*/  
	protected function set_msg( $action , $msg , $echo = '', $id = 0){
		global $Log;
		global $mensaje;
		$Log->write_log( $action . " @ Class " . $this->class . ( $id > 0 ? " (" . $id . "): " : " " ) . $msg );
		if ( $echo != '') $mensaje .= $echo . " <br/> ";
	} 
}

?>