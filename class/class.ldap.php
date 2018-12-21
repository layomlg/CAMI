<?php

class LDAP {
	
	protected $host = ''; 
	protected $port = ''; 
	protected $user = ''; 
	protected $pass = ''; 
	protected $domain = ''; 
	protected $baseDN = ''; 
	protected $connection; 
	protected $groups 		= array();
	protected $admin_groups = array();
	protected $bind = FALSE;
	
	public	$error = array();
	
	function __construct(){
		 if (!function_exists('ldap_connect')) {
		 	$this->set_error("No LDAP Support on server.", ERR_AD_CONN, 3 );
            throw new Exception("No LDAP Support on server.");
			die(); 
        } 
		$this->connect(); 
	} 
	
	
	/***
	 * connect 
	 * Connects to configured LDAP host
	 * 
	 */ 
	protected function connect(){ 
		try {
			global $Settings;

			$LDAP_settings = $Settings->get_settings_option('LDAP');
			
			$this->host = $LDAP_settings['ldap_host'];
			$this->port = $LDAP_settings['ldap_port'];
			$this->user = $LDAP_settings['ldap_user'];
			$this->pass = base64_decode( $LDAP_settings['ldap_pass'] );			
			$this->domain = $LDAP_settings['ldap_domain'];
			$this->baseDN = $LDAP_settings['ldap_baseDN'];

			$this->connection = ldap_connect( $this->host, $this->port );
			
			if ( $this->connection ){
				ldap_set_option($this->connection, LDAP_OPT_PROTOCOL_VERSION, 	3);
				ldap_set_option($this->connection, LDAP_OPT_REFERRALS, 		 	0);
				ldap_set_option($this->connection, LDAP_OPT_NETWORK_TIMEOUT,  	10);
				ldap_set_option($this->connection, LDAP_OPT_TIMELIMIT, 			20); 
				return TRUE;
			} else {
				$this->set_error("LDAP Server " . $server . " unreacheble.", ERR_AD_CONN, 3 );
				return FALSE; 
			} 
		} catch (Exception $e){
		 	$this->set_error("Could not stablish a connection with the LDAP Server " . $server . ".", ERR_AD_CONN, 3 ); 
			return FALSE;
		}
	}
	
	protected function bind( $user , $pwd ){
		return ldap_bind($this->connection, $user, html_entity_decode($pwd)); 
	} 
	
	public function get_user_info( $user = '' , $pass = '' ){
		if( empty($user) || empty($pass) ){
			return  false;
		}
		$filter	="(mail=".$user.")";
		$result = array();
		try {
			if ( $this->bind(  $this->user, $this->pass ) ){
				$sr		= ldap_search( $this->connection,  $this->baseDN, $filter);
				$result = ldap_get_entries($this->connection, $sr);
				if ( $result && count($result) > 1 ){
					if ( $this->bind(  $user, $pass ) ){
						$response = $result[0]; 
						$info = $this->format_user_info($response);
						$this->unbind();
						return $info;
					}else{
						$this->set_error( "LDAP Error: Unable to bind to LDAP Server. Auth Invalid".ldap_error($this->connection) , 'class', 1 );
						$response = array('error'=>1,'msg'=>'Datos de acceso incorrectos');
					}
				}else{
					$this->set_error( "Can not find the user".$user , 'class', 1 );
					$response = array('error'=>2,'msg'=>'Empty results in AD');
				}
			} else {
				$this->set_error( "LDAP Error: Unable to bind to LDAP Server. Auth Conf".ldap_error($this->connection) , 'class', 1 ); 
				$response = array('error'=>3,'msg'=>'Unable to acces to AD by conf');
			}
		} catch (Exception $e ){
			$this->set_error( "LDAP Error: " . $e, LOG_INFO_ERR, 1 );
			$response = array('error'=>4,'msg'=>'Can not bind by conf');
		}
		return $response;
	}

	public function get_user_info_all( $user ){ 
		$filter	="(|(samaccountname=" . $user . ")(mail=".$user."*))";  
		$result = array();
		try { 
			if ($this->bind( $this->user, $this->pass ) ){
				$sr		= ldap_search( $this->connection, $this->baseDN, $filter);
				$result[] = ldap_get_entries($this->connection, $sr);
				$this->unbind( );
			}
			return $result;
		} catch (Exception $e ){
			$this->set_error( "LDAP Error: " . $e, ERR_AD_QRY, 1 ); 
			return FALSE;
		} 
	}
	
	/**
	 * login() 
	 * 
	 * @access 	public
	 * @param	String $user 
	 * @param	String $pass
	 * @return 	Boolean TRUE on success; FALSE otherwise 
	 */
	public function login( $user, $pwd ){
		try {  
			$success = $this->bind( $user, $pwd ); 
			if ($success) {
				$this->set_msg("LOGIN Successful (" . $user . ")." );  
				return TRUE;
			} else {				
				$extended_error = ldap_error( $this->connection ); 
				$this->set_error("Invalid User (" . $user . ") / Password: " . $extended_error, LOGIN_BADLOGIN, 2 );  
				return FALSE;
			} 
		}  catch (Exception $e){
			$this->set_error("An error occured when trying to login to LDAP with User: " . $user . ". " . $e, ERR_AD_CONN, 2 );
			return FALSE;
		}
	}
	
	
	
	public function format_user_info( $info ){
		return array(
			'user' => $info['samaccountname'][0],
			'displayname' => $info['cn'][0],
			'name' => $info['cn'][0],
			'email' => isset($info['mail']) ? $info['mail'][0] : '',
			'dn'	=> $info['dn']
		);
	}
	
	protected function clean_memberof( $array ){
		$resp = ""; 
		for ( $i = 0; $i<$array['count']; $i++ ){
			$str = $array[$i]; 
			$arr_str = explode(",", $str);
			$grp = str_replace('CN=', '',  $arr_str[0]);
			//if ( in_array($grp, $this->groups))
				$resp .= $grp . "#";
		} 
		return $resp;
	}
	
	public function get_autocomplete_options( $string ){
		$resp = array(); 
		if ( trim($string) != '' ) {
			$niddle = trim( $string ); 
			$filter_name = "(cn=*" . $niddle . "*)"; 
			$filter = "(&(objectClass=user)" . $filter_name . ")";
			$attr 	= array("samaccountname", "givenname", "title", "mail", "displayname", "mobile", "telephonenumber", "cn");
			$result = array();
			try {
				if ( !$this->bind ){
					$this->connect(); 
					$this->bind	= $this->bind( $this->host->user, $this->host->pwd ); 
				}
				if ( $this->bind ){
					$sr			= ldap_search($this->connection, $this->host->baseDN, $filter, $attr);
					$result 	= ldap_get_entries($this->connection, $sr);
					
				} else { 
					$this->set_error(" Could not bind to server. ", ERR_AD_CONN ,2);
					return FALSE;
				}
			} catch (Exception $e ){
				$this->set_error(" Could not search in LDAP. ", ERR_AD_QRY ,2);
				return FALSE;
			} 
			$response = array();
			foreach ( $result as $key => $inf ){
				if ( is_numeric($key) 
						&& isset( $inf['mail'] ) 
						&& $inf['mail'][0] != ''
						&& isset( $inf['cn'] ) 
						&& $inf['cn'][0] != '' 
				){ 
					$resp[] = array( 
											'id' 	=> $inf['mail'][0],
											'text' 	=> $inf['cn'][0] . " - " . $inf['mail'][0] 
										); 
				}
			} 
			return $resp; 
		} 
		return $resp;
	}
	
	/**
	 * search 
	 * 
	 * @param 	$field - String. Field to search for 
	 * @param	$niddle - String. String to search for  
	 */
	public function search( $field = "samaccountname", $niddle = ''){
		//$filter_name ="(|(sn=$niddle*)(givenname=$niddle*)(mail=$niddle*)(samaccountname=$niddle*))"; 
		$filter_name = "(" . $field . "=*" . $niddle . "*)"; 
		$filter = "(&(objectClass=user)(objectCategory=person)" . $filter_name . ")";
		$attr 	= array("samaccountname", "givenname", "title", "mail", "displayname", "mobile", "telephonenumber", "memberof", "cn");
		$result = array(); 
		try {
			if ( !$this->bind ){
				$this->connect(); 
				$this->bind	= $this->bind( $this->host->user, $this->host->pwd );
			}
			if ( $this->bind ){
				$sr		= ldap_search($this->connection, $this->host->baseDN, $filter, $attr);
				$result = ldap_get_entries($this->connection, $sr);
				 
			} else { 
				$this->set_error(" Could not bind to server. ", ERR_AD_CONN ,2);
				return FALSE;
			}
		} catch (Exception $e ){
			$this->set_error(" Could not search in LDAP. ", ERR_AD_QRY ,2);
			return FALSE;
		}
		
		$response = array();
		foreach ( $result as $key => $inf ){
			if ( is_numeric($key) 
					&& isset( $inf['mail'] ) 
					&& $inf['mail'][0] != '' 
			){
				$user = $this->format_user_info( $inf ); 
				$response[] = $user; 
			}
		} 
		return $response;
	}

	public function search_branch( $branch = "" ){
		
		$filter_name = ""; 
		$filter = "(&(objectClass=user)(objectCategory=person)" . $filter_name . ")";
		$attr 	= array("samaccountname", "givenname", "title", "mail", "displayname", "mobile", "telephonenumber", "memberof");
		$result = array(); 
		$dn = $branch . "," . $this->host->branch  ;
		 
		try {
			if ( !$this->bind ){
				$this->connect(); 
				$this->bind	= $this->bind( $this->host->user, $this->host->pwd );
			}
			if ( $this->bind ){
				$sr	= ldap_search($this->connection, $dn, $filter, $attr); 
				if ( $sr ){
					$result = ldap_get_entries($this->connection, $sr);
				} 
			} else { 
				$this->set_error(" Could not bind to server. ", ERR_AD_CONN ,2);
				return FALSE;
			}
		} catch (Exception $e ){
			$this->set_error(" Could not search in LDAP. ", ERR_AD_QRY ,2);
			return FALSE;
		}
		
		$response = array();
		foreach ( $result as $key => $inf ){
			if ( is_numeric($key) 
					&& isset( $inf['mail'] ) 
					&& $inf['mail'][0] != '' 
			){
				$user = $this->format_user_info( $inf ); 
				if ( $user->memberof != ''){
					$response[] = $user;
				}
			}
		}
		 
		return $response;
	}
	

	public function get_group_members( $group ){  
		$filter_name = "(memberOf=" . $group .")";
		
		$filter = "(&(objectClass=user)(objectCategory=person)" . $filter_name . ")";
		$attr 	= array("samaccountname", "givenname", "title", "mail", "displayname", "memberof", "manager");
		$result	 = array(); 
		
		try {   
			$sr			= ldap_search($this->connection, $this->host->branch, $filter, $attr);
			$result 	= ldap_get_entries($this->connection, $sr); 
		} catch (Exception $e ){
			$this->set_error(" Could not search in LDAP. ", ERR_AD_QRY ,2);
			return FALSE;
		} 
		return $result; 
	} 
 
	protected function unbind(){ 
		return ldap_unbind( $this->connection );  
	}
	
	protected function set_error( $err, $type, $lvl = 1 ){
		global $Log;
		$this->error[] = $err;
		$Log->write_log(  " ERROR @ Class LDAP: " . $err , $type, $lvl);
	} 
	
	protected function set_msg( $msg , $echo = '' ){
		global $Log;
		global $mensaje;
		$Log->write_log( " MSG @ Class LDAP: " . $msg );
		if ( $echo != '') $mensaje .= $echo . " <br/> ";
	}
}
?>