<?php
////////////////////////////////////////////////////////////
	
// This Class contains functions for interacting with database operations.
	  
////////////////////////////////////////////////////////////
 
//_________define class_________________________// 
	class DbClass {	
		var $CONN;
		
		function dbclass() { //constructor
			$conn = mysql_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD);	
			if(!$conn){	
				$this->error("Connection attempt failed");
			}
			if(!mysql_select_db(DB_DATABASE,$conn)) {	
				$this->error("Database Selection failed");		
			}
			$this->CONN = $conn;
			return true;
		}
		//_____________close connection____________//
		function close(){
			$conn = $this->CONN ;
			$close = mysql_close($conn);
			if(!$close){
			  $this->error("Close Connection Failed");	}
			return true;
		}
	    //______________catch error__________________//
		function error($text) {
			
			$no = mysql_errno();
			$msg = mysql_error();
			$msg2 = "<hr><font face=verdana size=2>";
			$msg2 .= "<b>Custom Message :</b> $text<br><br>";
			$msg2 .= "<b>Error Number :</b> $no<br><br>";
			$msg2 .= "<b>Error Message	:</b> $msg<br><br>";
			$msg2 .= "<hr></font>";
	
			require_once("mail.cls.php");
			$mailObj = new NicMail();
			$mailObj->to = "darshan@codepixapp.com";
			$mailObj->from = "darshan@codepixapp.com";
			$mailObj->subject = "Error: Click Webservice";
			$mailObj->body = $msg2;
			$mailObj->send();
		
			$reslt = array();
			$method = 'index';
			$reslt['webservice_services_'.$method]['index']['response'] = $msg2;
			$reslt['webservice_services_'.$method]['index']['status'] = "Fail";
			$json_result = json_encode($reslt);	
			echo $json_result;
			exit;
		}
		//_____________select records___________________//
		function select ($sql=""){
		
			if(empty($sql)) { return false; }
			if(!preg_match("/^select/i",$sql)){	
			  echo "Wrong Query<hr>$sql<p>";
					return false;		} 
			if(empty($this->CONN)) { return false; }
			$conn = $this->CONN;
			$results = @mysql_query($sql,$conn);			
			if((!$results) or empty($results))	{	return false;		}
			$count = 0;
			$data  = array();
			while ( $row = mysql_fetch_array($results))	{	
				$data[] = $row;
				$count++;		}
			mysql_free_result($results);
			return $data;
		}
	    //__________total rows affected______________________//
	    function affected($sql="")	{
			if(empty($sql)) { return false; }
			if(!preg_match("/^select/i",$sql)){
			  	echo "Wrong Query<hr>$sql<p>";
					return false;		}
			if(empty($this->CONN)) 	{ 	return false; 	}
				
			$conn = $this->CONN;
			$results = @mysql_query($sql,$conn);
			if( (!$results) or (empty($results)) ) 
				{	return false;	}
			$tot=0;
			$tot=mysql_affected_rows();
			return $tot;
		}
	    //________insert record__________________//
		function insert($sql=""){ 
			if(empty($sql)) { 
				return false; 
			}
			if(!preg_match("/^insert/i",$sql)){	
				return false;		
			}
			if(empty($this->CONN)){	
				return false;		
			}
			$conn = $this->CONN;			
			
			$results = @mysql_query($sql,$conn);			
			
			if(!$results){
				$this->error("Insert Operation Failed..<hr>$sql<hr>");
				return false;		
			}
			$id = mysql_insert_id();
			return $id;
		}
		
		function saveData($insData,$tableName){ 
			if(empty($insData)) { 
				return false; 
			}
			if(empty($this->CONN)){	
				return false;		
			}
			$conn = $this->CONN;
			
	        foreach($insData as $key => $value){
	            $fileds[] = "`{$key}`";
	            if(!is_numeric($value)){
	                $field_val = mysql_real_escape_string($value);
	                $values[] = "'{$field_val}'";
	            }else{
	                $values[] = $value;
	            }
	        }     
        	$columns = implode(',', $fileds);
       		$columnVal = implode(',', $values);
			
			$sql = "INSERT INTO `".$tableName."` ($columns) VALUES ($columnVal)";
				
			$results = @mysql_query($sql,$conn);	
			if(!$results){
				$this->error("Insert Operation Failed..<hr>$sql<hr>");
				return false;		
			}
			$id = mysql_insert_id();
			return $id;
		}
		
		 function updateData($upData,$where,$tableName){     
		 	if(!is_array($upData) || empty($upData)) { 
				return false; 
			}
			
			if(empty($this->CONN)){	
				return false;		
			}
			$conn = $this->CONN;
			
		
			$query_string = $columns = $columnVal = "";   
		    $fileds = array();
			foreach($upData as $key => $value){
				if(!is_numeric($value)){
	                $field_val = addslashes($value);
	                $fileds[] = "`{$key}` = '{$field_val}'";
	                
	            }else{
	                $fileds[] = "`{$key}` = {$value}";
	            }
	        }
	        $query_string = implode(',', $fileds);     
			$sql = "UPDATE `".$tableName."` SET  $query_string WHERE ($where)";
			
			$results = @mysql_query($sql,$conn);	
			if(!$results){
				$this->error("Update Operation Failed..<hr>$sql<hr>");
				return false;		
			}
			$rows = 0;
			$rows = @mysql_affected_rows();
			return $rows;
		   
		}
				
		
	
	    //___________edit and modify record___________________//
		function update($sql="")	{
			if(empty($sql)) { 	return false; 		}
			if(!preg_match("/^update/i",$sql)){	return false;		}
			if(empty($this->CONN)){	return false;		}
			$conn = $this->CONN;
			$results = @mysql_query($sql,$conn);
			$rows = 0;
			$rows = @mysql_affected_rows();
			return $rows;
		}
		//____________generalize for all queries___________//
		function fetchAll($sql="")	{	
			
			if(empty($sql)) { return false; }
			if(empty($this->CONN)) { return false; }
			$conn = $this->CONN;
			$results = mysql_query($sql,$conn) or $this->error("Something wrong in query<hr>$sql<hr>");
			
			if(!$results){
			   $this->error("Query went bad ! <hr>$sql<hr>");
					return false;		}		
			if(!preg_match("/^select/i",$sql)){return true; 		}
			else {
		  	    $count = 0;
				$data = array();
				while ( $row = mysql_fetch_array($results,MYSQL_ASSOC)){	
					$data[$count] = $row;
					$count++;				
				}
				mysql_free_result($results);
			
				return $data;
		 	}
		}
		
		/**
		 * Fetch single Row
		 *
		 * @param unknown_type $sql
		 * @return unknown
		 */
		function fetchRow($sql="")	{	
			
			if(empty($sql)) { return false; }
			if(empty($this->CONN)) { return false; }
			$conn = $this->CONN;
			$results = mysql_query($sql,$conn) or $this->error("Something wrong in query<hr>$sql<hr>");
			
			if(!$results){
			   $this->error("Query went bad ! <hr>$sql<hr>");
					return false;		}		
			if(!preg_match("/^select/i",$sql)){return true;}
			else
			{
		  	    $data  = mysql_fetch_array($results,MYSQL_ASSOC);
				mysql_free_result($results);
				return $data;
		 	}
		}	
		
		function extraQueries($sql="")	{	
			
			if(empty($sql)) { return false; }
			if(empty($this->CONN)) { return false; }
			$conn = $this->CONN;
			$results = mysql_query($sql,$conn) or $this->error("Something wrong in query<hr>$sql<hr>");
			
			if(!$results){
			   $this->error("Query went bad ! <hr>$sql<hr>");
					return false;		}		
			else {
		  	    $count = 0;
				$data = array();
				while ( $row = mysql_fetch_array($results))
				{	$data[$count] = $row;
					$count++;				}
				mysql_free_result($results);
				return $data;
		 	}
		}	
		
		//____________generalize for all queries___________//
		function resultQuery($sql="")	{	
			
			if(empty($sql)) { return false; }
			if(empty($this->CONN)) { return false; }
			$conn = $this->CONN;
			$results = mysql_query($sql,$conn) or $this->error("Something wrong in query<hr>$sql<hr>");
			
			if(!$results){
			   $this->error("Query went bad ! <hr>$sql<hr>");
					return false;		}		
			if(!preg_match("/^select/i",$sql)){return true; 		}
			else {
		  	    $count = 0;
				$data = array();
				while ( $row = mysql_fetch_array($results,MYSQL_ASSOC)){	
					$data[$count] = $row;
					$count++;				
				}
				mysql_free_result($results);
			
				return $data;
		 	}
		}	
		
		
		
		
		function adder($sql="")	{	
			if(empty($sql)) 
				{ 	return false; 	}
			if(empty($this->CONN))
				{	return false;	}
				
			$conn = $this->CONN;
			$results = @mysql_query($sql,$conn);
	
			if(!$results)
				$id = "";  
			else
				$id = mysql_insert_id();
			return $id;
		}
		
				/**
		* @return array
		* @param string $tablename the tablename
		* @desc check if a table with the given name exists in DB
		*/
		function tableExists($tablename)
		{
			$conn = $this->CONN ;

			if(empty($conn)) { return false; }
			
			$results = mysql_list_tables(DB_DATABASE) or die("Could not access Table List...<hr>" . mysql_error());
			
			if(!$results){
				
				$message = "Query went bad!";
				//mysql_close($conn);
				die($message);
				return false;
				
			}else{
				
				$count = 0;
				$data = array();
				while ( $row = mysql_fetch_array($results)) {
					if ($row[0]==$tablename) {
						return true;
					//	mysql_close($conn);
						exit;
					}
				}
				mysql_free_result($results);
				//mysql_close($conn);
				return false;
			}
		}
		
		
		
	
	
	function setQuery($sql=""){
		if(empty($sql)){ 
			return false; 
		}
		if(empty($this->CONN)){ 
			return false; 
		}
		$conn = $this->CONN;
		$results = mysql_query($sql,$conn) or $this->error("Something went wrong in the query<hr>$sql<hr>");
		if(!$results){
			$this->error("Query went bad ! <hr>$sql<hr>");
			return false;	
		}else{
			return	$results;
		}
	}
	
	function dbFetchRow($result) {
        return mysql_fetch_row($result);
	}
	
	function dbFetchArray($result) {
        return mysql_fetch_array($result);
	}
	
	function dbFreeResult($result) {
        @mysql_free_result($result);
	}
	
	function dbNumRows($result) {
       return mysql_num_rows($result);
	}
	
	function dbNumFields($result) {
       return mysql_num_fields($result);
	}
	
	function dbFieldName($result) {
       return mysql_field_name($result);
	}
	
	function dbFieldType($result) {
       return mysql_field_type($result);
	}
	
	function totalTrim(&$value , $replace_with='' , $replace_to=''){
		$value = trim($value);
		if($replace_to==''){ 	
			return NULL;
		}
	
		while(stripos($value, $replace_to) != '' )
		{
			$value = ereg_replace( $replace_to, $replace_with , $value);
		}
		return NULL;
	}
	
	function dbTablesName(){
		$data = array();
		$tablesname = mysql_list_tables(DB_DATABASE);
	
		$i=0;
		while ($row = mysql_fetch_array($tablesname)){
			$data[$i] = $row[0];
			$i++;
		}

		return $data;
	}
		function dbTableField($tableName){
		$data = array();
		$sql = "SHOW COLUMNS FROM ".$tableName;
		$result = mysql_query($sql);
		$i = 0;
		while($row = mysql_fetch_assoc($result)){
			$data[$i]['Field'] = $row['Field'];
			$data[$i]['Type'] = $row['Type'];
			$data[$i]['Null'] = $row['Null'];
			$data[$i]['Key'] = $row['Key'];
			$data[$i]['Default'] = $row['Default'];
			$data[$i]['Extra'] = $row['Extra'];
			$i++;
		}
		
		return $data;
	}

	} //________ends the class here__________//

?>