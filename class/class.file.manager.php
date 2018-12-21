<?php 
/**
* FileManager CLass
* 
* @package		SF Tracker			
* @since        18/05/2014 
*/ 
class FileManager extends Objecto{
	
	private $id_proyect; 
	private $pfx;
	
	/**
	* FileManager()    
	* Creates a User object from the DB.
	*   
	*/  
	function FileManager( ){
		global $Session;
		$this->class = 'FileManager'; 
	}
	
	/**
	* save_uploaded()
	* Saves an uploaded file to the File System
	*  
	* @param	Array $file Uploaded File
	* @param	String $target location and name
	* @param	Integer $type file type
	* @param	Integer $max_size file max size
	* 
	* @return 	stClass File on success; FALSE otherwise
	*/
	public function save_uploaded( $file, $target, $type = 0, $max_size = 20485760 ){
		global $Session;
		global $Validate;
		if ( !$Session->is_admin() ){
			$this->set_error('Restricted Access', SES_RESTRICTED_ACCESS, 3);
			return FALSE;
		} 
		if ( !($target != '') ){
			$this->set_error('Invalid Target location', ERR_VAL_EMPTY, 3);
			return FALSE;
		} 
		if ( !is_array($file) ){
			$this->set_error('Invalid File Format', ERR_FILE_INVALID, 3);
			return FALSE;
		}  
		if ( !is_uploaded_file($file['tmp_name'])) {
			$this->set_error('Could not upload File to Server.', ERR_FILE_UPLOAD, 3);
			return FALSE;
		}
		if ( $file['size'] < 0 || $file['size'] > $max_size ){
			$this->set_error('Invalid file size.', ERR_FILE_UPLOAD, 3);
			return FALSE;
		} 
		$id_file_type = $Validate->valid_file_extension( $file['name'], $type );
		if ( $id_file_type === FALSE){
			$this->set_error('Invalid file extension.', ERR_FILE_INVALID, 3);
			return FALSE; 
		} 
		if( move_uploaded_file( $file['tmp_name'], $target )) { 
			$response = new stdClass;
			$response->id_file_type = $id_file_type;
			$response->name = $file['name'];
			$response->location = $target; 
			return $response; 
		} else {
			$this->set_error('Could not save file to server.', ERR_FILE_UPLOAD, 3);
			return FALSE; 
		} 
	}

	
	/**
	* save_to_file()
	* Saves a binary string to file to the File System, and the evidence record on the DB
	*  
	* @param	String $content File 
	* @param	String $name path to File
	* @param	Integer $type File Type ID
	* 
	* @return 	Boolean
	*/
	public function save_to_file( $content, $filename  ){
		if ( trim($content) != '' && $filename != ''){ 
			$content = stripslashes(trim($content)); 
			if ( file_put_contents( $filename, $content ) ){
				return TRUE;  
			} else {
				$this->set_error( "An error ocurred while trying to save the evidence to the File System. ", ERR_DB_EXEC, 3 );
				return FALSE;
			}
		} else { 
	 		$this->set_error("Empty content. ", ERR_VAL_EMPTY);
	 		return FALSE;
	 	}
	} 
	
	/**
	* save_to_file()
	* Saves a binary string to file to the File System, and the evidence record on the DB
	*  
	* @param	String $content File 
	* @param	String $name path to File
	* @param	Integer $type File Type ID
	* 
	* @return 	Boolean
	*/
	public function save_binary_to_file( $content, $filename  ){
		if ( trim($content) != '' && $filename != ''){ 
			//$content = stripslashes(trim($content)); 
			if ( file_put_contents( $filename, $content ) ){
				return TRUE;  
			} else {
				$this->set_error( "An error ocurred while trying to save the evidence to the File System. ", ERR_DB_EXEC, 3 );
				return FALSE;
			}
		} else { 
	 		$this->set_error("Empty content. ", ERR_VAL_EMPTY);
	 		return FALSE;
	 	}
	} 
	
	
	

	/**
	 * set_proyect()
	 * sets the proyect related to the file rquested 
	 * 
	 * @param 	Integer $id_proyect 
	 */
	 public function set_proyect( $id_proyect = 0 ){
	 	if ( !( $id_proyect > 0 ) ) { 
			global $Session;
			$id_proyect = $Session->get_proyect(); 
	 	}  
	 	if ( $id_proyect && is_numeric($id_proyect) && $id_proyect > 0 ) {
	 		$this->id_proyect = $id_proyect;
	 		$this->pfx = PFX_MAIN_DB . "pr" . $this->id_proyect . "_";
	 	} else { 
	 		$this->set_error("Invalid Proyect ID. ", ERR_VAL_INVALID);
	 		return FALSE;
	 	}
	 } 
	
	/**
	 *  output_file()
	 * 
	 *  @param 	Integer $id_file
	 *  @param  String $type of file  
	 */
	public function output_file( $id_file, $type = "" ) { 
		switch ($type) {
			case 1: //invoice XML
				$this->output_invoice_xml( $id_file );
				break;
			case 2: //invoice PDF
				$this->output_invoice_pdf( $id_file );
				break;
			case 3: //Acknowledgment PDF
				$this->output_acknowledgment_pdf( $id_file );
				break;
			case 'ev':	$this->output_evidence( $id_file ); 	break;
			case 'mf':	$this->output_media_file( $id_file ); 	break;
			default:  	$this->output_error(); 					break;
		} 
	}
	
	
	/**
	 * output_invoice()
	 * 
	 */
	 public function output_invoice_xml( $uuid ){
	 	
		global $obj_bd;
		$query = "SELECT * FROM " . PFX_MAIN_DB . "invoice WHERE in_uuid = :uuid ";
		$resp =  $obj_bd->query( $query , array( ':uuid' => $uuid ) );
		
		if ( $resp ){
			$data = $resp[0]; 
			$name = "invoice_" . str_pad( $data['in_or_id_order'] , 7, "0", STR_PAD_LEFT) . "_" . $data['in_folio'] . ".xml";
			$filename = DIRECTORY_INVOICE . $name; 
			if ( file_exists( $filename )) {
				$this->output_headers( "xml", $name ); 
				$content = file_get_contents($filename);
				if ( $content )
					echo $content;
				else {
					$this->set_error("An error occured while reading the file ( Invoice: " . $filename . " ) ", ERR_FILE_INVALID );
					$this->output_error();
				} 
			}
			else {
				$this->set_error("File not found ( " . $this->id_proyect . " , " . $id_file . " ) ", ERR_FILE_NOT_FOUND);
				$this->output_error(); 
			} 
		} 
		
	 }
	
	
	/**
	 * output_invoice()
	 * 
	 */
	 public function output_invoice_pdf( $uuid ){
	 	
		global $obj_bd;
		$query = "SELECT * FROM " . PFX_MAIN_DB . "invoice WHERE in_uuid = :uuid ";
		$resp =  $obj_bd->query( $query , array( ':uuid' => $uuid ) );
		 	
		if ( $resp ){
			$data = $resp[0]; 
			
			$name = "invoice_" . str_pad( $data['in_or_id_order'] , 7, "0", STR_PAD_LEFT) . "_" . $data['in_folio'] . ".pdf";
			$filename = DIRECTORY_INVOICE . $name; 
			if ( file_exists( $filename )) {
				$this->output_headers( "pdf", $name ); 
				$content = file_get_contents($filename);
				if ( $content )
					echo $content;
				else {
					$this->set_error("An error occured while reading the file ( Invoice: " . $filename . " ) ", ERR_FILE_INVALID );
					$this->output_error();
				} 
			}
			else {
				$this->set_error("File not found ( " . $this->id_proyect . " , " . $id_file . " ) ", ERR_FILE_NOT_FOUND);
				$this->output_error(); 
			} 
		} 
		
	 }
	
	/**
	 * output_invoice()
	 * 
	 */
	 public function output_acknowledgment_pdf( $id_invoice  ){
	 	
		global $obj_bd;
		$query = "SELECT * FROM " . PFX_MAIN_DB . "invoice WHERE id_invoice = :id_invoice ";
		$resp =  $obj_bd->query( $query , array( ':id_invoice' => $id_invoice ) );
		 	
		if ( $resp ){
			$data = $resp[0]; 
			$name = "acknowledgment_" . $data['in_folio'] . ".pdf";
			$filename = DIRECTORY_UPLOADS . $name; 
			if ( file_exists( $filename )) {
				$this->output_headers( "pdf", $name ); 
				$content = file_get_contents($filename);
				if ( $content )
					echo $content;
				else {
					$this->set_error("An error occured while reading the file ( Invoice: " . $filename . " ) ", ERR_FILE_INVALID );
					$this->output_error();
				} 
			}
			else {
				$this->set_error("Acknowledgment file not found ( " . $id_file . " ) ", ERR_FILE_NOT_FOUND);
				$this->output_error(); 
			} 
		} 
		
	 }
	
	private function output_evidence($id_file)
	{
		global $obj_bd;
		$query = "SELECT * FROM " . PFX_MAIN_DB . "evidence WHERE id_evidence = :id_file ";
		$values = array( ":id_file" => $id_file );
		$resp = $obj_bd->query($query, $values ); 
		if ( $resp ) {
			$data = $resp[0];
			
			$file = new stdClass;
			$file->name  = 'evidence_'.$id_file;//$data['ev_text'];
			$file->route = $data['ev_route'];
			$file->type  = 1;//$data['mf_ft_id_file_type'];
			
			if ( file_exists( $file->route ))
			{
				$this->output_headers( $file->type, $file->name );
				
				$content = file_get_contents($file->route);
				if ( $content )
					echo $content;
				else
				{
					$this->set_error("An error occured while reading the file ( " . $this->id_proyect . " , " . $id_file . " ) ", ERR_FILE_INVALID );
					$this->output_error();
				} 
			}
			else
			{
				$this->set_error("File not found ( " . $this->id_proyect . " , " . $id_file . " ) ", ERR_FILE_NOT_FOUND);
				$this->output_error(); 
			} 
		}
		else {
			$this->set_error("Media File not found ( " . $this->id_proyect . " , " . $id_file . " ) ", ERR_DB_NOT_FOUND);
			$this->output_error();
		} 
	}
	
	/**
	 * output_media_file()
	 * 
	 * @param 	$id_file 
	 */
	private function output_media_file( $id_file ){
		
		if ( !($this->id_proyect > 0) ){
			$this->set_proyect();
		}
		
		if ( $this->id_proyect > 0 && $id_file > 0 ){
			global $obj_bd;
			$query = "SELECT * FROM " . $this->pfx . "media_file WHERE id_media_file = :id_file ";
			$values = array( ":id_file" => $id_file );
			$resp = $obj_bd->query($query, $values );
			if ( $resp ){
				$data = $resp[0];
				
				$file = new stdClass;
				$file->name  = $data['mf_name'];
				$file->route = $data['mf_route'];
				$file->type  = $data['mf_ft_id_file_type'];
				
				if ( file_exists( $file->route )){
					$this->output_headers( $file->type, $file->name );
					
					$content = file_get_contents($file->route);
					if ( $content )
						echo $content;
					else {
						$this->set_error("An error occured while reading the file ( " . $this->id_proyect . " , " . $id_file . " ) ", ERR_FILE_INVALID );
						$this->output_error();
					} 
				} else {
					$this->set_error("File not found ( " . $this->id_proyect . " , " . $id_file . " ) ", ERR_FILE_NOT_FOUND);
					$this->output_error(); 
				} 
			} else {
				$this->set_error("Media File not found ( " . $this->id_proyect . " , " . $id_file . " ) ", ERR_DB_NOT_FOUND);
				$this->output_error();
			} 
		}
	}
	
	/**
	 * output_headers()
	 * 
	 * @param	Integer $type	
	 * @param 	String $name output file's name
	 */
	private function output_headers( $type, $name ){ 
		switch ($type) {
			case 1: //image
				header('Content-Type: image/jpeg');
				//header('Content-Disposition: attachment;filename=' . $name . '' ); 
				break;
			case 2: //video
			
				break;
			case 6:  //XLS 
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename=' . $name . '' );
				header('Cache-Control: max-age=0'); 
				break; 
			default:
				header('Content-Disposition: attachment;filename=' . $name . '' );
				header('Cache-Control: max-age=0');
				break;
		} 
	}
	
	/**
	 * output_error()
	 * 
	 */
	private function output_error(){
		/* TODO: Output error */
	}
}
?>