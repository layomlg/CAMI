<?php

require 'PHPMailer/PHPMailerAutoload.php';

/**
 * Mailer class
 * Implements PHPMailer 
 * 
 */
class Mailer extends Objecto {

	private $address;
	private $head;
	private $body;
	private $foot;
	private $credentials;	
	private $mail;
	
	public $title;
	
	/**
	* constructor
	*
	* @access	public
	* @param	boolean $smtp wheather or not to send through SMTP 
	*/
	function __construct( $smtp = TRUE ){ 
		$this->class = 'Mailer';
		$this->error = array();
		$this->email = new PHPMailer();
		$this->email->isHTML(true); 
		
		$this->email->From 		= SYS_MAIL; //address
		$this->email->FromName 	= "MLG | SAI"; //SYS_TITLE; 
		 
		$this->email->CharSet	= 'UTF-8';
		
		if ( $smtp ){ 
			$this->set_SMTP( $smtp ); 
		} 
	}
	
	
	/**
	 * set_SMTP
	 * sets SMTP configured parameters 
	 * 
	 * @access 	private 
	 * @return 	boolean 
	 */
	private function set_SMTP( $smtp = true ){
		global $Settings;
		
		if ( $smtp === true ){
			$host = $Settings->get_settings_option("smtp_host");
			$port = $Settings->get_settings_option("smtp_port");
			$user = $Settings->get_settings_option("smtp_user");
			$pass = $Settings->get_settings_option("smtp_pass");
		} else {
			$host = $Settings->get_settings_option( $smtp . "_smtp_host");
			$port = $Settings->get_settings_option( $smtp . "_smtp_port");
			$user = $Settings->get_settings_option( $smtp . "_smtp_user");
			$pass = $Settings->get_settings_option( $smtp . "_smtp_pass");
		}
		if ( $host != NULL && $port != NULL  && $user != NULL  && $pass != NULL ){
			$this->email->isSMTP(); 			// Set mailer to use SMTP
			$this->email->Host 		= $host;
			$this->email->Port 		= $port; 	// TCP port to connect to
			$this->email->SMTPAuth 	= true; 	// Enable SMTP authentication
			$this->email->Username 	= $user; 	// SMTP username
			$this->email->Password 	= base64_decode($pass);	// SMTP password
			$this->email->SMTPSecure= 'tls';	// Enable TLS encryption, `ssl` also accepted
			
			$this->email->From 		= $user; //address 
			return TRUE;
		} else {
			$this->set_error("Invalid SMTP configuration.", ERR_VAL_INVALID);
			return FALSE;
		}
	}
	
	/**
	 * add_address
	 * adds an e-mail address to send the mail to
	 * 
	 * @access 	public
	 * @param	string $address email to send the mail to
	 * @param	string $name (Optional) The name of the reciepient
	 * @return	boolean 
	 */
	public function add_address( $address, $name = FALSE ){
		global $Validate; 
		if ( $Validate->is_email( $address )){
			if ( $name && $name != '' ){
				$this->email->addAddress( $address, $name );
			} else {
				$this->email->addAddress( $address );
			}
			return TRUE;
		} else {
			$this->set_error( "Invalid E-mail address ('" . $address . "').", ERR_VAL_INVALID );
			return FALSE;
		} 
	}
	
	/**
     * add_bcc_address
	 * adds configured addresses to forward as bcc addresses of the email
	 *  
     * @access 	private
	 * 
     */
	private function add_bcc_address(){ 
		global $Settings;
		global $Validate;
		$addresses = $Settings->get_settings_option("sale_forwarding_emails");
		if ( $addresses && $addresses != ''){ 
			$array = explode(';',$addresses);
			if ( count($array) > 0 ){
				foreach ($array as $k => $addr) {
					if ( $Validate->is_email( $addr )){
						$this->email->addBCC( $addr );
					}
				} 
			} 
		} 	 
	}
	
	/**
     * Attaches a file to the email 
     * @access public
     * @param string $file Route to the file
     * @return boolean
     */
	public function add_attachment( $file, $name = FALSE ){
		if ( file_exists( $file ) ){
			if ( $name && $name != '' ){
				$resp = $this->email->addAttachment($file, $name );    // Optional name
			} else {
				$resp = $this->email->addAttachment( $file );
			}
			if ( $resp )
				$this->set_msg("ATTACH", "File '" . $file . "' attached to email. ");
			else 
				$this->set_error( "Could not attach file '" . $file . "'", ERR_FILE_404 );
			return $resp;
		} else {
			$this->set_error( "Attached file does not exist!", ERR_FILE_404 );
			return FALSE;
		}
	}
	
	/**
     * Sets the Subject for the Email 
     * @access public
     * @param string $subject  
     */
	public function set_subject( $subject ){
		$this->title = $subject; 
		$this->email->Subject = $subject; 
	}
	
	/**
     * Sets the Content of the Email 
     * @access public
     * @param string $body  
     */
	public function set_body( $body ){
		if ( $body && $body != "" ){
			$this->body = $body;
		} 
	}
	
	/**
     * Appends content to the Email 
     * @access public
     * @param string $subject  
     */
	public function add_body( $body ){
		$this->body .= $body;
	}
	
	/**
	 * builds the content of the Email
	 * @access private
	 * @return string 
	 */
	 private function get_content(){
		
		return $this->body;
	 }
	
	/**
	 * get_header 
	 * builds the header of the Email
	 * @access private
	 * @return string 
	 */
	 private function get_header(){
		$html = "";  
	/*	ob_start(); 
		require_once DIRECTORY_VIEWS . "email/mail.header.php"; 
		$html .= ob_get_contents(); 
		ob_end_clean(); 
	*/	return str_replace(array("\n", "\t"), "", $html); 
	 }
	
	/**
	 * get_footer 
	 * builds the footer of the Email
	 * @access private
	 * @return string 
	 */
	 private function get_footer(){
		$html = "";  
	/*	ob_start(); 
		require_once DIRECTORY_VIEWS . "email/mail.footer.php"; 
		$html .= ob_get_contents(); 
		ob_end_clean(); 
	*/	return str_replace(array("\n", "\t"), "", $html);
	 }
	
	
	/**
     * Sets the Subject for the Email 
     * @access public
	 * @return boolean  
     */
	public function send(){
		$content = $this->get_header() . $this->body . $this->get_footer();
//		$this->add_bcc_address();
		$this->email->Body = $content;
		
		if(!$this->email->send()) {
			//var_dump( $this->email );
			$this->set_error("Message could not be sent. Error: " . $this->email->ErrorInfo, ERR_VAL_INVALID);
		    return FALSE;
		} else {
			$this->set_msg("SEND", "Message sent.");
		    return TRUE;
		} 			
	}
}
?>