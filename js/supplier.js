
function action_supplier( obj ){
	
	var id_supplier = obj.id.replace( 'inp_supplier_actions_', ''); 
	switch( obj.value ){
		case '1': 
			info_supplier( id_supplier );
			$('#' + obj.id ).val('0');
			break;
		case '2':
			edit_supplier( id_supplier );
			$('#' + obj.id ).val('0');
			break;
		case '3': 
			delete_supplier( id_supplier );
			$('#' + obj.id ).val('0');
			break;
		default: 
			//nothing to do
			return;
	}
}

function edit_supplier( id_supplier ){
	location.href = '?rsrc=' + frm_supplier + '&cb=' + current + '&id=' + id_supplier ;
}

function delete_supplier( id_supplier ){
	if ( confirm('¿Está seguro que desea eliminar al proveedor?') ){
		
	}
}

function info_supplier( id_supplier ){
	
}

function edit_supplier_address( id_address ){
	
}

function delete_supplier_address( id_address ){
	if ( confirm('¿Está seguro que desea eliminar la dirección?') ){
		
	}
}

