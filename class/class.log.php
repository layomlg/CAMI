<?php
/**
* Log CLass
* 
* @package		MLG - Base
* @since        11/09/2018 
*/ 
class Log{
	
	private $filename = ""; 
	protected $handle;
	
	function __construct( $name = '' ){
		$this->filename = $name != '' ? $name : LOG_FILE ;
		$this->check_file(); 
	}
	
	/**
	* check_file
	* Checks if the file is created, attempts to create it if not and opens the file 
	*
	*/
	private function check_file(){
		try {
			if ( file_exists( LOG_DIR . $this->filename ) ){
				if ( filesize( LOG_DIR . $this->filename ) >= LOG_MAX_SIZE ){
					$success = rename(LOG_DIR . $this->filename, LOG_DIR . $this->filename . "_" . date("YmdHis") ); 
					if ( !$success ){
						throw new Exception("ERROR: No se ha podido respaldar el archivo de Log");
						die("ERROR: No se ha podido respaldar el archivo de Log");
					}
				} 
			} 
			$this->handle = fopen( LOG_DIR . $this->filename , 'a'); //implicitly creates file  	
		}
		catch (Exception $e){
			throw new Exception("ERROR: No se ha podido crear el archivo de Log. ". $e );
			die( "ERROR: No se ha podido crear el archivo de Log. ". $e ); 
		}
		
	}
	
	
	/**
	* write_log 
	* Writes a line to the log file 
	*
	* @param 	(String) $string information to write in file.
	* @param 	(Integer) $type The type of information beeing written 
	* @param 	(Integer) $level defines the level of severity of the information being written 
	*/
	public function write_log( $string  , $type = 0, $level = 1){
		
		try { 
			$user = isset( $_SESSION[ PFX_SYS . 'user'] ) ?  $_SESSION[ PFX_SYS . 'user'] : "NULL";
			$addr = isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : "NULL";
			$formatted 	= sprintf( LOG_TMPLT , date('Y-m-d H:i:s') , $user, $addr , $string . "\n"); 
			
			$success 	= fwrite($this->handle, $formatted );

			if ( !$success )
				die("ERROR: No se ha podido escribir en el archivo de Log. ". $success );

			if ( $type > 0 )
				$this->save_log( $type, $level, $string );
				
		} catch (Exception $e){
			throw new Exception("ERROR: No se ha podido escribir en el archivo de Log. ". $e );
			die("ERROR: No se ha podido escribir en el archivo de Log. ". $e ); 
		} 
	}
	
	/**
	* write_api_log
	* Writes a line to the API log file.
	*
	* @param 	(String) $user API user 
	* @param	(String) $from Location of the API call 
	* @param	(String) $string Line to write
	* @param 	(Integer) $type The type of information beeing written 
	* @param 	(Integer) $level defines the level of severity of the information being written 
	*/
	public function write_api_log( $user, $from, $string , $type = 3, $level = 3 ){
		try {
			$formatted 	= sprintf( LOG_TMPLT , date('Y-m-d H:i:s') , $user, $from, $string . "\n"); 
			$success 	= fwrite($this->handle, $formatted );
			if ( !$success ){
				die("ERROR: No se ha podido escribir en el archivo de Log. ". $success ); 
			}
			$this->save_log( $type, $level, $string );
		} 
		catch (Exception $e){
			throw new Exception("ERROR: No se ha podido escribir en el archivo de Log. ". $e );
			die("ERROR: No se ha podido escribir en el archivo de Log. ". $e ); 
		}
	}
	
	/**
	* save_log 
	* Saves line to DB. 
	* 
	* @param 	(Integer) $type The type of information beeing written 
	* @param 	(Integer) $level defines the level of severity of the information being written 
	* @param	(String) $string Line to write
	*/
	private function save_log( $type, $level, $string ){
		/* TODO */
		return TRUE;  
	}
	
}

?>