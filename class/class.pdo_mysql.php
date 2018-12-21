<?php
/***
 * PDO MySQL DB Class
 *  
 */
 
 
class PDOMySQL{
	var $connection; 	//PDO connection ID
	var $error; 		//Error array
	
	/**
	* PDOMySQL()
	* Class Constructor
	* @param 		$bd_host: 		DB Host
	*				$bd_user. 		DB User
	*				$bd_pasword. 	DB Password
	*				$bd_data_base. 	DB Data Base
	* @return		$bd_conexion 	DB Connection 
	*/
	 
	function __construct($bd_host, $bd_user, $bd_password, $bd_data_base){
		try {
			$this->connection = new PDO("mysql:host=$bd_host;dbname=$bd_data_base", $bd_user, $bd_password);
			$this->connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		} catch ( PDOException $e ){
			die( 'Could not connect to '.$bd_host.' server ' );
		} 
	}
	 
	/**
	* execute() 	Executes a query in the DataBase
	* @param       	$query String: Query to excute
	 * 				$values Mixed: Default FALSE, just execute query
	* @return        -> false if an error occurred
	*				 -> true if query was successfull
	*/
	public function execute( $query, $keys = FALSE, $values = FALSE ){
		try {
			$qry = $this->connection->prepare( $query );
			
			if ( $keys && is_array($keys)){
				if ( $values && is_array($values) ){ //
					
					if ( is_array( $values[0] ) ){
						$response = TRUE;
						foreach ( $values as $k => $vals ) {
							$response = ($response && $qry->execute( $vals ));
						}
						return $response;
					} else {
						return $qry->execute( $values );
					} 
					
				} else { 
					return $qry->execute( $keys );
				}
			} else {
				return $qry->execute();
			}
			
		} catch ( PDOException $err ){
			$this->error[] = $err;
			return FALSE;
		}
	}
	
	/**
	* clean_query()  Queries the DataBase
	* @param       	$inQuery String: Consulta a realizar
	* @return        -> false if an error occurred
					 -> records array
	*/
	private function clean_query( $query ){
		$query = trim( $query );
		if (substr( strtolower($query), 0, 7 ) === "select "){ 
			$query = str_replace( array('insert', 'update', 'delete', 'truncate', 'drop', 'create'), '', $query);
			return $query;
		} else {
			return "SELECT 0";
		}
	}
	
	/**
	* query()  		Queries the DataBase
	* @param       $inQuery String: Consulta a realizar
	* @return        -> false if an error occurred
	*				 -> records array
	*/ 
	public function query( $query, $params = FALSE, $method = PDO::FETCH_ASSOC ){
		try {
			$qry = $this->connection->prepare( $query ); 
			if ( $params && is_array($params)){ 
				$result = $qry->execute( $params ); 
			} else {
				$result = $qry->execute();
			} 
			if ( !$result ){
				$this->error[] = $this->connection->errorInfo();
				return FALSE;
			}
			$qry->setFetchMode( $method ); 
			$response = array();
			while($row = $qry->fetch()) {
			    $response[] = $row;
			} 
			$qry->closeCursor(); 
			return $response; 
		} catch ( PDOException $e ){
			
			$this->error[] = $e;
			return FALSE; 
		}
	} 
	
	/**
	* get_error()	Returns DB Error
	* @param		NULL
	* @return  		error array
	*/
	function get_error( $all = FALSE ){
		$error = $this->connection->errorInfo();
		$this->error[] = $error;
		return ( $all ) ? $this->error : $error[2];
	}
	
	/**
	* get_last_id()   	Regresa el ultimo ID insertado en la BD
	* @param	      	NULL
	* @return       		-> ultimo id inertado
	*/
	function get_last_id(){  
		return $this->connection->lastInsertId();
	} 
	
	
} 
?>