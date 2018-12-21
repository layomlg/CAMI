<?php
/**
* Class Login
*  
* @package		MLG - Base
* @since        11/09/2018 
*/ 
class Login extends Objecto {
	public $user;
	public $nombre; 
	public $profile;
	public $type;
	public $id; 

	function __construct(){
	}
	
	public function get( $param = '' ){
		if( empty($param) ){
			return false;
		}
		return $this->$param;
	}
	
	/**
	* log_in 
	* Checks credentials and log user in if access is valid
	* 
	* @param 	$usuario String
	* @param 	$password String
	* 
	* @return 	Boolean TRUE on success; FALSE otherwis
	*/
	function log_in( $user = '', $pass = '' ) {
		if( empty($user) || empty($pass)){
			return false;
		}
		
		if ( ! class_exists( 'LDAP' ) )
			require_once DIRECTORY_CLASS . "class.ldap.php";
		
		$connect_ad = new LDAP();

		$ctrl_accs = $connect_ad->get_user_info( $user, utf8_encode($pass));

		if( empty($ctrl_accs['error']) ){
			if( !empty($this->ldap_login( $ctrl_accs )) ){
				return array('success' => true, 'msg' => 'Logged' );
			}else{
				return array(
					'success' => false, 
					'msg' => 'Can not login in application, Contact to support or administrator.'
				);
			}
		}else{
			if( $ctrl_accs['error'] == 2 ){
				$login_bd = $this->bd_login( $user, $pass );
				if( $login_bd['success'] == true ){ 
					return array('success' => true, 'msg' => 'Logged by DB.');
				}else{
					return array(
						'success' => false,
						'msg' => $login_bd['msg']
					);
				}	
			}else{
				return array('success' => false, 'msg' => $ctrl_accs['msg']);
			}
		}
	}

	/**
	* bd_login
	* Checks credentials against DB and log user in if exists
	* 
	* @access 	private  
	* @param 	$user String
	* @param 	$pass String 
	* @return 	Boolean TRUE on success; FALSE otherwis
	*/
	public function bd_login( $user = '', $pass = '' ){
		if( empty($user) || empty($pass)){
			return false;
		}
		global $obj_db;
		$query = "SELECT usr_user, id_user, CONCAT(usr_name,' ',usr_lastname ) name, "
				."upt_profile, upt_ust_id_user_type "
				."FROM ".PFX_MAIN_DB."user "
				."INNER JOIN ".PFX_MAIN_DB . "user_parent_type ON id_user = upt_usr_id_user "
				."WHERE usr_user = :user AND usr_pass = :pwd ";
		
		$usr_info = $obj_db->query( $query, array( ':user' => $user, ':pwd' => md5($pass)) );

		if ( $usr_info !== FALSE ){
			if ( count($usr_info) > 0 ){
				
				$this->user = $usr_info[0]['usr_user'];
				$this->name = $usr_info[0]['name'];
				$this->profile = $usr_info[0]['upt_profile'];
				$this->type = $usr_info[0]['upt_ust_id_user_type'];
				$this->id = $usr_info[0]['id_user'];
				
				global $Session;

				$Session->create_session(
					array(
						'user' => $usr_info[0]['usr_user'],
						'name' => $usr_info[0]['name'],
						'profile' => $usr_info[0]['upt_profile'],
						'type' => $usr_info[0]['upt_ust_id_user_type'],
						'id' => $usr_info[0]['id_user']
					)
				);
				
				$sql  = "UPDATE " . PFX_MAIN_DB . "user SET usr_last_login = :lastlogin WHERE usr_user = :user "; 
				$resp = $obj_db->execute( $sql, array( ':lastlogin' => time(),  ':user' => $user ) ); 

				return array(
					'success' => true,
					'msg' => "Logged by DB"
				); 
			} else {
				$this->set_error( "No results for user.", ERR_VAL_INVALID );
				return array(
					'success' => false,
					'msg' => "Datos de acceso incorrectos. Comuniquese con su contacto en MLG para activar su usuario o validar su información de accesso."
				); 
			} 	
		} else {
			$this->set_error( "Unable to get user info from DB query.", ERR_VAL_INVALID );
			return array(
				'success' => false,
				'msg' => "Unable to get user information."
			); 
			return FALSE;
		}
	}

	/**
	* ldap_login
	* Checks credentials against Active Directory and log user in if bind is successful
	* 
	* @access 	private  
	* @param 	$info stdClass
	* @return 	Boolean TRUE on success; FALSE otherwis
	*/
	public function ldap_login( $info = false ){
		if( empty($info) ){
			return false;
		}
		$usr = $this->get_user_data( $info );
		
		if ( !empty($usr) ){
			$this->name 	= $usr['name'];
			$this->user 	= $usr['user'];
			$this->profile 	= $usr['profile'];
			$this->type 	= $usr['type'];
			$this->id 		= $usr['id'];
			
			global $Session;
			$Session->create_session( $usr );
			 
			return true; 
		} else {
			$this->set_error("Unable to get user info from DB.", ERR_VAL_INVALID);
			return false;
		}
	}
	
	/**
	 * get_user_data
	 * 
	 * @access	private
	 * @param	$info stdClass Object with user information
	 * @return	stdClass with registered user information 
	 **/
	private function get_user_data( $info = '' ){
		if ( empty($info) || empty($info['user']) ){
			return false;
		}

		global $obj_db;
		$query_profile = "SELECT * FROM ".DB_CTRL.CTRL_PFX."user_profile_system "
			."WHERE uf_user = :usr AND uf_sy_id_system = 1 AND uf_status = 1";

		$resp = $obj_db->query( $query_profile , array( ':usr' => $info['user']  ));

		if ( $resp !== FALSE ){ 
			if ( count( $resp ) > 0 ){
				$profile =  $resp[0]['uf_id_profile'];
				
				$query_user = "SELECT * FROM ".PFX_MAIN_DB."user "
					."WHERE usr_user = :mail AND usr_status = 1";
				
				$response = $obj_db->query( $query_user , array( ':mail' => $info['email']  ));
				if ( $response !== FALSE ){ 
					if ( count( $response ) > 0 ){
						return array(
							'id' => $response[0]['id_user'],
							'user' => $response[0]['usr_user'],
							'name' => $response[0]['usr_name']." ".$response[0]['usr_lastname'],
							'profile' => $profile,
							'type' => 1
						);
					}else{
						$this->set_error("No user rows found.", ERR_VAL_INVALID);
						return false;
					}
				}else{
					$this->set_error("Unable to get user info from DB query.", ERR_VAL_INVALID);
					return false;
				}
			}else{
				$this->set_error("No user rows found in control.", ERR_VAL_INVALID);
				return false;
			}
		} else {
			$this->set_error("Unable to get user info from DB Control query.", ERR_VAL_INVALID);
			return false;
		}  
	}

	function Forgot($email) { 
		/* TODO */ 
	} 
}
?>
