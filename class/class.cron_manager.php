
<?php
/**
* CronManager CLass
* 
*/ 

class CronManager extends Object {
	
	private $jobs = array(); 
	public $error = array();
	
	/**
	* CronManager()    
	* Creates a User object from the DB.
	*  
	* @param	$id_contact (optional) If set populates values from DB record. 
	* 
	*/  
	function __construct(){
		global $obj_bd;
		$this->error = array(); 
		$this->jobs  = array(); 
		
		$query = "SELECT "
						. " id_cron_script, cs_script, cs_last_timestamp, cs_frequency " 
					. " FROM " . PFX_MAIN_DB . "cron_script " 
				. " WHERE cs_status > 0 "; 
		$jobs = $obj_bd->query( $query );
		
		if ( $jobs !== FALSE ){
			foreach ($jobs as $k => $job) {
				
				$last = $job['cs_last_timestamp'];
				$freq = $job['cs_frequency'];
				
				if ( $last + $freq < time() ){
					if ( file_exists( DIRECTORY_CRON . $job['cs_script'] ) ){
						$j = new stdClass;
						$j->id 		= $job['id_cron_script'];
						$j->script 	= $job['cs_script'];
						$this->jobs[] = $j;
					} else {
						$this->set_error("Invalid script: '" . $job['cs_script'] . "'", ERR_FILE_NOT_FOUND);
					}
				} 
			}  
		} else {
			$this->clean();
			$this->set_error( "An error ocurred while querying the Data Base. ", ERR_DB_QRY, 2 );
		}  
	}
 
	/**
	 * execute()
	 * 
	 */
	 public function execute(){
	 	$start = time();
	 	$this->set_msg("START", "Cron starting: " . count($this->jobs) . " jobs on queue. " );
		
		foreach ($this->jobs as $k => $job) {
			try {
				if ( file_exists( DIRECTORY_CRON . $job->script ) ){
					include DIRECTORY_CRON . $job->script;
					
					$this->update_script( $job->id );
				} else
					$this->set_error(" Script not found!! '" . $job['cs_script'] . "'", ERR_FILE_NOT_FOUND);
			} catch ( Exception $e ){
				$this->set_error(" Error in script!! " . $e , ERR_CRON_EXEC ); 
			} 
		}		
		$time = time() - $start;
		$this->set_msg("END", "Cron ended! Total time: "  . $time . " seconds. ");
	 }

	/**
	 * update_script()
	 * 
	 * @access	private
	 * @param	$id Integer script id  
	 */
	 private function update_script( $id ){
	 	if ( $id > 0 ){
	 		global $obj_bd;
			$when = time();
			$query = " UPDATE " . PFX_MAIN_DB . "cron_script SET "
					. " cs_last_timestamp = :time "
					. " WHERE id_cron_script = :id_script ";
			$resp = $obj_bd->execute($query, array( ':time' => $when, ':id_script' => $id ));
			if ( $resp !== FALSE ){
				$this->set_msg("UPDATE", " Script ID " . $id . " executed " . $when . ". ");
				return TRUE;
			} else {
				
			}  
	 	} else {
	 		$this->set_error("Invalid script Id to update '" . $id . "'. ", ERR_VAL_INVALID);
			return FALSE;
	 	}
	 }
	
	/**
	 * get_array()
	 * returns an Array with contact information
	 * 
	 * @return	$array Array width Jobs information
	 */
	 public function get_array( $full = FALSE ){
	 	return array( 'jobs' =>	$this->jobs ); 
	 }
	
	/**
	* clean()    
	* Cleans all parameters and resets all objects
	*  
	*/  
	public function clean(){
		$this->jobs = array();
		$this->error = array();
	}
}
?>