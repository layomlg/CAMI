<?php
/**
* Class Validate
*  
* @package		MLG - Base
* @since        11/09/2018 
*/ 
class Validate {
	
	function __construct(){
		
	}
	
	/**
	* is_color()    
	* Validates if input is a HEX color
	*  
	* @param	$val 
	*  
	* @return 	TRUE if $val is a HEX color; FALSE otherwise
	*/ 
	public function is_color( $val ){ 
		return (preg_match('/^#[a-f0-9]{6}$/i', $val)); //hex color is valid 
	}
	
	/**
	* is_number()    
	* Validates if input is a number
	*  
	* @param	$val 
	*  
	* @return 	TRUE if $val is a numer; FALSE otherwise
	*/ 
	public function is_number( $val ){
		return is_numeric( $val );
	}
	
	/**
	* is_integer()    
	* Validates if input is integer
	*  
	* @param	$val 
	*  
	* @return 	TRUE if $val is a Integer; FALSE otherwise
	*/ 
	public function is_integer( $val ){ 
		return ctype_digit($val);
	}
	
	/**
	* is_email()    
	* Validates if input is an email
	*  
	* @param	$string String  
	*  
	* @return 	TRUE if $string is an email; FALSE otherwise
	*/ 
	public function is_email( $string ){
		return filter_var($string, FILTER_VALIDATE_EMAIL); 
	}
		
	/**
	* is_rfc()    
	* Validates if input is a valid Mexican RFC
	*  
	* @param	$string String  
	*  
	* @return 	TRUE if $string is correct; FALSE otherwise
	*/ 
	public function is_rfc( $string ){
		$valor = str_replace(array("-", " "), "", trim($string));
        $cuartoValor = substr($valor, 3, 1);
        //RFC Persona Moral.
        if (ctype_digit($cuartoValor) && strlen($valor) == 12) {
            $letras = substr($valor, 0, 3);
            $numeros = substr($valor, 3, 6);
            $homoclave = substr($valor, 9, 3);
            if (ctype_alpha($letras) && ctype_digit($numeros) && ctype_alnum($homoclave)) {
                return true;
            }
        //RFC Persona Física.
        } else if (ctype_alpha($cuartoValor) && strlen($valor) == 13) {
            $letras = substr($valor, 0, 4);
            $numeros = substr($valor, 4, 6);
            $homoclave = substr($valor, 10, 3);
            if (ctype_alpha($letras) && ctype_digit($numeros) && ctype_alnum($homoclave)) {
                return true;
            }
        }else {
            return false;
        }
    } 

	/**
	* is_ip()    
	* Validates if input is an IP
	*  
	* @param	$string String
	*  
	* @return 	TRUE if $string is an IP; FALSE otherwise
	*/ 
	public function is_ip( $string ){
		return filter_var($string, FILTER_VALIDATE_IP);
	}
	
	/**
	* is_url()
	* Validates if input is an URL
	*  
	* @param	$string String
	*  
	* @return 	TRUE if $string is an URL; FALSE otherwise
	*/ 
	public function is_url( $string ){
		return filter_var($string, FILTER_VALIDATE_URL);
	}
	
	/**
	* is_int_between()    
	* Validates if input is an between two values
	*  
	* @param	$val
	* @param	$min
	* @param	$max 
	*  
	* @return 	TRUE if $val is between $min and $max; FALSE otherwise
	*/ 
	public function is_int_between( $val, $min, $max){
		return filter_var($val, FILTER_VALIDATE_INT, 
						array(
							    'options' => array(
							                      'min_range' => $min,
							                      'max_range' => $max,
							                      )) 
								);
	} 
	
	/**
	* is_unique()    
	* Validates if value is unique in DB table column. 
	*  
	* @param	$table (Haystack table) 
	* @param	$column (Column of table)
	* @param	$value	(Value to look for)
	* @param	$id_col (Name of the ID column to exclude from query)
	* @param	$id_val (Value of the id to exclude from query)
	*  
	* @return 	TRUE if $val is between $min and $max; FALSE otherwise
	*/ 
	public function is_unique( $table, $column, $value, $id_col, $id_val, $parent_col = FALSE, $parent_val = FALSE ) {
		global $obj_db;
		$query = "SELECT " . $column 
				. " FROM " . PFX_MAIN_DB . $table  
				. " WHERE " . $column . " = '" . $value . "' " 
					. " AND NOT " . $id_col . " = '" . $id_val . "' "
					. ( ( $parent_col ) ? " AND " . $parent_col . " = '" . $parent_val . "' " : " " ) ; 
		$result = $obj_db->query( $query ); 
		if ( $result !== FALSE ) {
			if ( count($result)  > 0 ) {
				return FALSE;
			} else {
				return TRUE;
			}
		} else {
			return FALSE;
		}
	}
	
	/**
	* exists()    
	* Validates if value exists on the DB.  
	*  
	* @param	$table (Haystack table) 
	* @param	$column (Column of table)
	* @param	$value	(Value to look for) 
	*  
	* @return 	TRUE if $val is between $min and $max; FALSE otherwise
	*/ 
	public function exists( $table, $column, $value ){
		global $obj_db;
		$query = "SELECT " . $column 
				. " FROM " . PFX_MAIN_DB . $table 
				. " WHERE " . $column . " = '" . $value . "' " ;
		$result = $obj_db->query( $query );
		if ( $result !== FALSE ){
			if ( count($result)  > 0 ){
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		} 
	}
	
	/**
	 * valid_file_extension()
	 * Validates if File is a valid file extension from the DB
	 * 
	 * @param	String $file Name of the File
	 * @param 	Integer allowed File type
	 * 
	 * @return 	TRUE on success; FALSE otherwise 
	 */
	public function valid_file_extension( $file, $type = 0 ){
		if ( $file != '' ){ 
			$data = explode(".", $file); 
			$ext = $data[(count($data)-1)];
			if(!$ext) {
				return FALSE;
			} 
			global $obj_db;
			$query = "SELECT id_file_type, ft_extentions FROM " . PFX_MAIN_DB . "file_type ";
			$values = FALSE;
			if ( $type > 0 ){
				$values = array( ':id_file_type' => $type );
				$query .= " WHERE id_file_type = :id_file_type ";
			}
			$resp = $obj_db->query( $query, $values ); 
			foreach ( $resp as $k => $e ){ 
				$exts = explode(";", $e['ft_extentions']);
				if ( in_array( $ext , $exts)){
					return $e['id_file_type'];
				}
			}
			return FALSE;
		} 
		else return FALSE;	
	}
	
	/**
	 * uploaded_is_image()
	 * Validates if uploaded file is image with valid $size, $width & $height 
	 * 
	 * @param 	$file uploaded file
	 * @param 	$size (optional) Size in bits
	 * @param	$width (optional) Width for the image to validate; 0 if no width validation
	 * @param	$height (optional) Width for the image to validate; 0 if no height validation
	 * 
	 * @return 	TRUE if valid; FALSE otherwise
	 */
	 public function uploaded_is_image( $file, $size = 200000, $width = 0, $height = 0 ){
	 	if ( $file['name'] != '' && $file['tmp_name'] != ''){
	 		$tmp = $file['tmp_name']; 
		    if (file_exists($tmp)) {
		        $imagesizedata = getimagesize($tmp);
		        if ($imagesizedata === FALSE) {
		            return "Not an image.";
		        } else { 
					/*TODO: properties validation*/
					$allowedExts = array("gif", "jpeg", "jpg", "png");
					$extension = end(explode(".", $file["name"]));
					if ((  ( 	$file["type"] == "image/gif")
							|| ($file["type"] == "image/jpeg")
							|| ($file["type"] == "image/jpg")
							|| ($file["type"] == "image/png")
							) 
					){ 
						if ( $file["size"] > $size ){
							return "File size larger than " . $size . " ";
						} else {
							return TRUE;
						}
					} else {
						return "Invalid image format.";
					}
		        }
		    } else {
		        return "No image found.";
		    } 
	 	} 
	 	else return FALSE;
	 } 
}
?>