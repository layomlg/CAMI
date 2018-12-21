<?php
/**
 * Class Session 
 * 
 * @package		MLG - Base
 * @since        20/11/2018 
 */
class Session extends Objecto{
	
	var $name;
	var $profile;
	var $type;
	var $user;
	var $id;
	
	/**
	 * __construct 
	 * 
	 */
	function __construct(){
		global $Debug; 
		$this->class = 'Session';
		$this->user = "";
		$this->name = "";
		$this->profile = 0;
		$this->id = "";

		if ( $this->set_from_session() ) {
            return TRUE;
		} else if ( $this->set_from_cookie() ){
			return TRUE;
		} else{
			$this->end_session();
			return FALSE;
		}
	}

	/**
	 * create_session 
	 * creates a PHP Session form the info provided
	 * 
	 * @param 	$info Object with user information 
	 */
	public function create_session( $user_info ){
		
		$_SESSION[PFX_MLG . 'user']	= $_SESSION[PFX_SYS . 'user'] = $user_info['user'];
		$_SESSION[PFX_SYS . 'name']		= $user_info['name'];
		$_SESSION[PFX_SYS . 'profile']	= $user_info['profile'];
		$_SESSION[PFX_SYS . 'type']	= $user_info['type'];
		$_SESSION[PFX_SYS . 'id']		= $user_info['id']; 
		
		session_write_close(); 
		
		$this->name 	= $user_info['name'];
		$this->profile 	= $user_info['profile'];
		$this->profile 	= $user_info['type'];
		$this->user 	= $user_info['user'];
		$this->id 		= $user_info['id'];
	}
	
	/**
	 * set_from_session 
	 * sets the class information from the current active session 
	 * 
	 */
	private function set_from_session(){
		if ( 
			!empty($_SESSION[ PFX_SYS . 'profile']) &&
			!empty($_SESSION[ PFX_SYS . 'type']) &&
			!empty($_SESSION[ PFX_SYS . 'user']) &&
			!empty($_SESSION[ PFX_SYS . 'name']) && 
			!empty($_SESSION[ PFX_SYS . 'id']) ) { 
            $this->name 	= $_SESSION[ PFX_SYS . 'name'];
			$this->profile 	= $_SESSION[ PFX_SYS . 'profile'];
			$this->type 	= $_SESSION[ PFX_SYS . 'type'];
			$this->user 	= $_SESSION[ PFX_SYS . 'user'];
			$this->id 		= $_SESSION[ PFX_SYS . 'id']; 
			 
			return TRUE;
		} else if ( isset($_SESSION[ PFX_MLG . 'user']) && ($_SESSION[ PFX_MLG . 'user'] != "") ) {
			return $this->set_from_global_session(); 
		} else {
			return FALSE;
		}
	}
	
	
	/**
	 * set_from_global_session
	 * sets the class information from the current active Global session
	 * 
	 * @return 	TRUE on success; FALSE otherwise
	 */
	private function set_from_global_session(){
		if ( !empty($_SESSION[ PFX_MLG . 'user']) ){ 
			if ( ! class_exists( 'Login' ) )
				require_once DIRECTORY_CLASS . "class.login.php";
			
			$login = new Login();

			$user = $_SESSION[ PFX_MLG . 'user'];
			/*echo '<pre>';
			print_r($_SESSION);
			echo '</pre>';
			var_dump( $login->log_in( array('user'=>$user) ) );*/
			if( $login->ldap_login( array('user'=>$user) ) ){ 
				return true;
			} else {
				$this->set_error( "An error occured while trying to query for global MLG user ( $user ). " , ERR_DB_QRY);
				return FALSE;
			} 
		} else {
			$this->set_error("Invalid global session info.", SES_INVALID_ACCESS );
			return FALSE;
		} 
	}
	
	/**
	 * set_from_cookie 
	 * Sets the class information and creates a Session from cookie information 
	 * 
	 */
	private function set_from_cookie(){
		 /* TODO */
		 return false;
	}

	public function logged_in() {
		return ($this->id != "");
	}
	
	public function get( $param = '' ){
		if( empty($param) ){
			return false;
		}
		return $this->$param;
	}
	
	/**
	 * get_session_var
	 * returns the requested varname value from the Session 
	 * 
	 * @return 	(mixed) requested value 
	 */
	public function get_session_var( $varname ) {
		return ( isset($_SESSION[$varname]) ? $_SESSION[$varname] : "" );
	}
	
	/**
	 * set_session_var
	 * Sets the $value in the requested $varname  
	 * 
	 * @param 	$varname
	 * @param 	$value 
	 */
	public function set_session_var( $varname, $value ) {
		$_SESSION[$varname] = $value;
	}
	
	/**
	 * ens_session 
	 * ends the current PHP session, cleans cookies, and resets Class values
	**/
	public function end_session() {
		
		$_SESSION[PFX_MLG . 'user'] 	= "";
		
		$_SESSION[PFX_SYS . 'name'] 	= "";
		$_SESSION[PFX_SYS . 'user'] 	= "";
		$_SESSION[PFX_SYS . 'id'] 		= "";
		$_SESSION[PFX_SYS . 'profile'] 	= 0; 
		
		setcookie(PFX_SYS . "user",		'', time() - 3600 );
		setcookie(PFX_SYS . "token",	'', time() - 3600 );
		
		session_destroy();
		session_start();
		
		$this->user = "";
		$this->name = "";
		$this->profile = 0;
		$this->id = "";
	}
	
	public function validPermissions(){
		$permissions = $this->getMdlsPermissions();
		if( isset($permissions[0]) && $permissions[0] == 0  ){
			return true;
		}else if( !empty($permissions) ){
			$mdl_info = $this->getMdlByPath( strip_tags(trim($_GET['rsrc'])) );
			if( array_key_exists ($mdl_info['id_module'], $this->getMdlsPermissions()) ){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	public function getMdlsPermissions(){
		global $Session, $obj_db;
		
		$query_mod_perms  = "SELECT ump_mod_perm "
				."FROM ".PFX_MAIN_DB."user_modules_permissions "
				."WHERE ump_usr_id_user = :id";
		
		$res_mod_perms = $obj_db->query( $query_mod_perms, array( ':id' => $Session->get('id') ));
		
		if( !empty( $res_mod_perms ) ){
			return json_decode($res_mod_perms[0]['ump_mod_perm'],true);
		}else{
			return false;
		}
	}
	
	public function getMdl( $id_module = 0 ){
		if( empty($id_module) ){
			return false;
		}
		global $obj_db;
		
		$query_mod  = "SELECT * "
				."FROM ".PFX_MAIN_DB."modules "
				."WHERE id_module = :id";
		
		$res_mdl = $obj_db->query( $query_mod, array( ':id' => $id_module ));
		
		if( !empty( $res_mdl ) ){
			return $res_mdl[0];
		}else{
			return false;
		}
	}
	
	public function getMdlByPath( $path = '' ){
		if( empty($path) ){
			return false;
		}
		global $obj_db;
		
		$query_mod  = "SELECT * "
				."FROM ".PFX_MAIN_DB."modules "
				."WHERE mdl_path = :path";
		
		$res_mdl = $obj_db->query( $query_mod, array( ':path' => $path ));
		
		if( !empty( $res_mdl ) ){
			return $res_mdl[0];
		}else{
			return false;
		}
	}

}
?>