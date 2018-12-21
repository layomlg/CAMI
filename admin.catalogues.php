<?php
require_once 'init.php'; 
global $Session;
if ( $Session->is_admin() ){
	$action = $_POST['action'];
	switch( $action ){
		case 'edit_catalogue_admin':
			$catalogue 	= ( !empty( $_POST['catalogue'] ) ) ? $_POST['catalogue'] : '';
			$id 		= ( !empty( $_POST['id_catalogue'] ) ) ? $_POST['id_catalogue'] : 0;
			$value	 	= ( !empty( $_POST['cat_value'] )  ) ? utf8_decode($_POST['cat_value']) : '';
			$id_parent 	= ( !empty( $_POST['cat_id_parent'] ) ) ? $_POST['cat_id_parent'] 	: 0;
			if ( $catalogue != '' ){ 
				if ( $value == '' ){
					header("Location: index.php?err=" . urlencode( "Valor inválido." ));
				} 
				require_once DIRECTORY_CLASS . "class.admin.catalogue.php";
				$cat = new CatalogueAdmin( $catalogue, $id );
				$cat->value = $value;
				if ( $cat->parent ){
					$cat->id_parent = $id_parent;
				}
				$resp = $cat->save();
				if ( $resp === TRUE ){
					$str_err = "";
					if  ( count( $cat->error) > 0 ){
						$str_err = "&err=" . urlencode( $cat->get_errors() );
					} 
					header("Location: index.php?command=" . ADMIN_CATALOGUE . "&cat=" . $catalogue . "&msg=" . urlencode( "El registro se guardó exitosamente." ) . $str_err );				
				} else { 
					header("Location: index.php?command=" . ADMIN_CATALOGUE . "&cat=" . $catalogue . "&err=" . urlencode( $cat->get_errors() ) ); 
				} 
			} else {
				header("Location: index.php?err=" . urlencode( "Catálogo inválido." ));	
			}
			break;
		case 'edit_program':
			if ( ! class_exists( 'Program' ) )
				require_once DIRECTORY_CLASS . "class.program.php";

			$ext_id_program = !empty($_POST['ext_id_program']) ? $_POST['ext_id_program'] : '';
			$name = !empty($_POST['name']) ? $_POST['name'] : '';
			$assigned_to = !empty($_POST['id_assigned_to']) ? $_POST['id_assigned_to'] : '';
			$id_program = !empty($_POST['id_program']) ? $_POST['id_program'] : '';
			if( !empty($ext_id_program) && !empty($name) && !empty($assigned_to) ){
				$program = new Program( $id_program );
				$program->name = $name;
				$program->program = $ext_id_program;
				$program->mlg_contact = $assigned_to;
				if( $program->save() ){
					header("Location: index.php?command=lst_program&msg=" . urlencode( "El registro se guardó exitosamente." ) . $str_err );
				}else{
					header("Location: index.php?command=lst_program&err=" . urlencode( "No se púdo guardar." ));
				}
			}else{
				header("Location: index.php?command=lst_program&err=" . urlencode( "Faltan valores." ));
			}
			break;
		case 'edit_subdepartment':
			if ( ! class_exists( 'Subdepartment' ) )
				require_once DIRECTORY_CLASS . "class.subdepartment.php";

			$name = !empty($_POST['name']) ? $_POST['name'] : '';
			$assigned_to = !empty($_POST['id_assigned_to']) ? $_POST['id_assigned_to'] : '';
			$id_department = !empty($_POST['department']) ? $_POST['department'] : '';
			$id_subdepartment = !empty($_POST['id_subdepartment']) ? $_POST['id_subdepartment'] : 0;
			if( !empty($name) && !empty($id_department) && !empty($assigned_to) ){
				$subdepartment = new Subdepartment( $id_subdepartment );
				$subdepartment->subdepartment = $name;
				$subdepartment->id_department = $id_department;
				$subdepartment->id_mlg_contact = $assigned_to;
				if( $subdepartment->save() ){
					header("Location: index.php?command=lst_subdepartment&msg=" . urlencode( "El registro se guardó exitosamente." ) . $str_err );
				}else{
					header("Location: index.php?command=lst_subdepartment&err=" . urlencode( "No se púdo guardar." ));
				}
			}else{
				header("Location: index.php?command=lst_subdepartment&err=" . urlencode( "Faltan valores." ));
			}
			break;
		default: 
			header("Location: index.php?err=" . urlencode( "Acción inválida." ));
			break;
	}
} else {
	header("Location: index.php?err=" . urlencode( "Acción restringida." ));
}
?>