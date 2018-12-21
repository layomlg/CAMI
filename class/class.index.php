<?php 
/**/ 
class Index {
	var $js;
	var $content;
	var $title;
	var $css;
	var $ajax;
	var $link;
	var $command;
	var $modals;
	
	function __construct() {
		$this->js 		= array();
		$this->content 	= "";
		$this->title 	= "";
		$this->css		= array();
		$this->modals	= array();
		$this->ajax		= "";
		$this->command	= "";
	}

	function get_title() {
		global $Session;
		return " CAMI | " . $this->title;
	}

	function get_content(){
		return $this->content;
	}
 
	function get_css(){
		$resp = "";
		if ( is_array( $this->css ) && count( $this->css ) > 0 )
			foreach ($this->css as $k => $file) {
				$resp .= "<link href='css/".$file."' type='text/css' rel='stylesheet' />"; 
			}
		return $resp;
	}

	function get_js(){
		$resp = "";
		if ( is_array( $this->js ) && count( $this->js ) > 0 ){
			foreach ($this->js as $k => $file) {
				$script = "";
				if ( stripos( $file , "admin" ) ) {
					global $Session;
					if ( $Session->is_admin() )
						$script = "<script src='js/" . $file . "' type='text/javascript'></script>";  	 
				} else {
					$script = "<script src='js/" . $file . "' type='text/javascript'></script>";
				}
				$resp .= $script;
			}
		}
		return $resp;
	}
	
	function get_modals(){ 
		if ( is_array( $this->modals ) && count( $this->modals ) > 0 ){
			foreach ($this->modals as $k => $file) { 
				if ( stripos( $file , "admin" ) ) {
					global $Session;
					if ( $Session->is_admin() )
						require_once( DIRECTORY_VIEWS . "modals/"  . $file ); 
				} else {
					require_once( DIRECTORY_VIEWS . "modals/"  . $file );
				} 
			} 
		}
	}

	function get_ajax(){
		return $this->ajax;
	}
	
	function get_command(){
		return $this->command;
	}
	
	function get_menu(){
		global $obj_db,$Session;
		
		$mod_perms = $Session->getMdlsPermissions();
		
		if( !empty($mod_perms) ){
			
			$query_modules  = "SELECT * "
				."FROM ".PFX_MAIN_DB."modules ";

			if( isset($mod_perms[0]) && $mod_perms[0] == 0 ){
				$query_modules .= "WHERE mdl_status = 1 ";
			}else{
				$query_modules .= "WHERE mdl_status = 1 AND id_module IN (".implode(",",array_keys($mod_perms)).") ";
			}
			
			$query_modules .= "ORDER BY mdl_parent ASC, mdl_order ASC";

			$res_modules = $obj_db->query( $query_modules );
			
			if(!empty($res_modules)){
				$modules = array();
				foreach ( $res_modules as $key_r => $value_r ) {
					$modules[$value_r['id_module']] = $value_r;
				}
				$modules_ok = array();
				
				foreach ( $modules as $key_m => $value_m ) {
					if( empty($value_m['mdl_parent']) ){
						if( !empty($modules_ok[$value_m['id_module']]) ){
							$modules_ok[$value_m['id_module']] = array_merge($value_m,$modules_ok[$value_m['id_module']]);
						}else{
							$modules_ok[$value_m['id_module']] = $value_m;
						}
					}else{
						if( !empty($modules_ok[$value_m['mdl_parent']]) ){
							$modules_ok[$value_m['mdl_parent']]['childs'][$value_m['id_module']] = $value_m;
						}else{
							$modules_ok[$modules[$value_m['mdl_parent']]['mdl_parent']]['childs'][$value_m['mdl_parent']]['childs'][$value_m['id_module']] = $value_m;
						}
					}
				}
				
				$html = "<ul class='sidebar-nav'>";
				$menu = array();
				$html .= $this->createMenu( $modules_ok ); 
				$html .= "</ul>";
				return $html;
			}
			return 'Modules config missing';
		}else{
			return 'Without permissions';
		}
	}
	
	function createMenu( $menu = array() ){
		if( empty($menu) ){
			return 'Empty';
		}
		$html = '';
		foreach ($menu as $key_e => $value_e ) {
			
			if( !empty($value_e['childs']) ){
				
				$html .= "<li class='dropdown'>"
							."<a href='javascript:void(0);' class='dropdown-toggle '>" 
								.$value_e['mdl_name']
							."</a>"
							."<ul class='dropdown-menu' > ";
				$html .= $this->createMenu( $value_e['childs'] );
				$html .= "</ul> </li>";
			}else{
				
				$html .= "<li>
							<a href='index.php?rsrc=" . $value_e['mdl_path'] . "'  >" 
								.$value_e['mdl_name']
							."</a>"
						."</li>";
			}
		}
		return $html;
	}
	 
	function loop_menu( $link ){ 
		$resp = "";
		global $Session;
		global $uiCommand;

		if ( is_array( $link ) && count( $link ) > 0 ){
			if ( $link['cmd'] == 'root' ){
				$resp .= "<ul class='sidebar-nav'>"; 
				foreach ($link['lnk'] as $k => $lnk){
					$resp .= $this->loop_menu( $lnk );
				}
			} else {
				if ( $link['cmd'] == '#' && in_array($Session->get('profile'), $link['prf'] ) ) {
					$active = $this->is_active_link( $link );
					$resp .= "<li class='dropdown'>"
							. "<a href='#' class='dropdown-toggle " . ( $active ? "active-parent active" : "$active" ) . "'>" 
							. "<i class='fa " . $link['ico'] . "'></i> <span class='hidden-xs'>" . $link['lbl'] . "</span></a>"
							. "<ul class='dropdown-menu' " . ( $active ? " style='display:block;' " : "" ) . "> ";
							foreach ($link['lnk'] as $k => $lnk){
								$resp .= $this->loop_menu( $lnk );
							} 
					$resp .= "</ul> </li>"; 
				}
				else if(  $link['cmd'] != '#' && in_array( $Session->get('profile'), $uiCommand[ $link['cmd'] ][0] ) ) { 
					$active  = ( $this->command == strtok($link['cmd'], '&') ) ? TRUE : FALSE;
					$resp .= "<li><a href='index.php?cmd=" . $link['cmd'] . "' " . ( $active ? "class='active'" : "" ) . " >" 
								. "<i class='fa " . $link['ico'] . "'></i> <span class='hidden-xs'>" . $link['lbl'] . "</span></a>"
							. "</li>";
				}  
			} 
			$resp .= ( $link['cmd'] != 'root' ) ? "</li>" : "</ul>";
		}
		return $resp;
	}
	
	function is_active_link( $link ){
		if ( count( $link ) > 0 ){
			if ( strtok($link['cmd'], '&') == $this->command ){
				return TRUE;
			} else {
				if ( count( $link['lnk'] ) > 0 ){
					foreach ($link['lnk'] as $k => $lnk){
						if ( $this->is_active_link($lnk) )
							return TRUE;
					} 
				} 
			}
		}
		return FALSE;
	}

	function logic( $command ){
		global $uiCommand;
        global $Session;

		if($Session->logged_in() && !isset($uiCommand[$command])) {
           $command = ERR_404; 
        }
        if(!in_array($Session->get('profile'),$uiCommand[$command][0])) { 
			$command = HOME; 
        }
		
		$this->title      = $uiCommand[$command]['1'];
		$this->content    = $uiCommand[$command]['2'];
		$this->js         = $uiCommand[$command]['3'];
		$this->css        = $uiCommand[$command]['4'];
        $this->ajax       = $uiCommand[$command]['5'];
        $this->modals     = $uiCommand[$command]['6'];
        $this->command    = $command;
		
		$this->link 	= "index.php?command=" . $command ;
	}
}
?>