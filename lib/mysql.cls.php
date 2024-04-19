<?php
class MySqlClass {
	
	var $CONN;
	var $DATABASE;
	var $QUERY;
	var $SERVER;
	var $ERROR;
	var $ERROR_MSG;
	
	function __construct ($user = DB_USERNAME, $pass = DB_PASSWORD, $server = DB_SERVER, $dbase = DB_DATABASE)	{	
		
		$user = $user;
		$pass = $pass;
		$server = $server;
		$dbase = $dbase;
		$this->DATABASE = $dbase;
		$this->SERVER   = $server;
		$this->ERROR     =  false;
		$this->ERROR_MSG = '';
		
		//echo $this->SERVER; exit;
		try {
			if($_SERVER['SERVER_ADDR'] == '192.168.1.248')
				$this->CONN = mysqli_connect($server, $user, $pass, '', 0, '/data/mysqld.sock');
			else 
				$this->CONN = mysqli_connect($server, $user, $pass);
			
			
			$db_select 	= mysqli_select_db($this->CONN,$dbase);
			
			if ($this->CONN === false) 
			{
			  	throw new Exception('Cannot connect to mysql - ' . $this->Error() );
				exit;
			}	
			if ($db_select === false)
			{
			   	throw new Exception('Database selection failed.' . $this->Error() );
				exit;
			}
			
			mysqli_query($this->CONN,"SET SESSION sql_mode = 'NO_ENGINE_SUBSTITUTION'");	
		} catch (Exception $e) {
			
			$this->ErrorLogWrite( $e->getMessage() );
			return ;
		}
		
		return true;
	}

	function __destruct() {	
		if($this->ERROR == true )	{
			$to = 'gequaldev@gmail.com';
			$subject = TITLE . " check error log";
			$message = $this->ERROR_MSG;
			$from    = $to;
			//$a = Email_Send($to, $subject, $message, $from );
		}
		
		try {
			if (!mysqli_close($this->CONN))   throw new Exception('MySql connection close ' . $this->Error());
		} catch (Exception $e) {
			$this->ErrorLogWrite( $e->getMessage() );
			return false;
		}		
		return true;
    } 
	
	function count_row($sql="")	
	{
		$sql = trim($sql);
		
		if(empty($sql)) 
		{
			return 0;
		}	

		$this->TotalQueryInPage[] = $sql;
		
		try 
		{
			$try = preg_match("/^select/i",$sql);
			
			if ($try === 0)   
			{
				throw new Exception('Check Select Query - ' . $sql);
			}	
			
			if (!($this->CONN))  
			{ 
				throw new Exception('MySql connection close');
			}
				
			$this->QUERY = $sql;
			
			$results = mysqli_query($this->CONN,$sql);
			
			if($results == false )
			{ 
				throw new Exception('MySql SQL ' . $this->Error($sql) );
			}	
		} 
		catch (Exception $e) 
		{
			$this->ErrorLogWrite( $e->getMessage() );
			##return false;
			return 0;
		}
		
		$rec_count = mysqli_num_rows($results);
		mysqli_free_result($results);
		
		if($rec_count > 0)
			return $rec_count;
		else
		   return 0;	
		
	}
	
	function ErrorLogWrite($text) {
		$this->ERROR = true;
		$text = 'Caught exception: ' . $text . "\n" ;
		
		$myFile = ERROR_LOG_FOLDER . 'MySqlError.txt';
		
		if(file_exists($myFile))
		{
			if(filesize($myFile)>2097152) // 2 MB
			{
			 	@unlink($myFile);
			}
		}
		
		//$fh = fopen($myFile, 'a+') or die("can't open file");
		if($fh = fopen($myFile, 'a+'))
		{
			$stringData  = chr(13) . '=================================================================';
			$stringData .= chr(13) . date('d-M-Y H-i') . chr(13) . chr(13) . $_SERVER['REQUEST_URI'] . ' == ' . $_SERVER['REMOTE_ADDR'] . chr(13) . chr(13) ;
			$stringData .= $text;
			$stringData .= chr(13) . '=================================================================' . chr(13);
		
			$this->ERROR_MSG .= $stringData ;
		
			fwrite($fh, $stringData);
			fclose($fh);
		}
		return NULL;
	}
	
	function Error($sql = NULL)	{
	
		$errNo = mysqli_errno($this->CONN);
		$str  =  "  Error Number => " . mysqli_errno($this->CONN);
		$str .=  "  Error => " . mysqli_error($this->CONN);
		if($sql != NULL ) $str .= chr(13) . chr(13) . $sql;
		
		if($errNo == 1040 || $errNo == 2002 || $errNo == 1226) {
			$to = 'gequaldev@gmail.com';
			$subject = "hbasales.com check error log";
			$message = $str . chr(13). chr(13). serialize($_SERVER);
			$from    = $to;
			$this->ErrorLogWrite($message);
			//$a = Email_Send($to, $subject, nl2br($message), $from );
			exit;
		}	
		return $str;
	}

	function select($sql="")	{
		$sql = trim($sql);
			
		if(empty($sql)) return false;
		try {
			$try = preg_match("/^select/i",$sql);
			if ($try === false)   throw new Exception('Check Select Query - ' . $sql);
			if (!($this->CONN))   throw new Exception('MySql connection close');
			$this->QUERY = $sql;
			
			$msc = microtime(true);
			
			$results = mysqli_query($this->CONN,$sql);
			
			$msc = microtime(true)-$msc;
			

			if($results == false ) throw new Exception('MySql SQL ' . $this->Error($sql) );
		} catch (Exception $e) {
			$this->ErrorLogWrite( $e->getMessage() );
			//return false;
			return NULL;
		}
		$data = $this->mysqlFetchAssoc($results);
		mysqli_free_result($results); 
		
		if(isset($_GET['debug']) == '1' && ($_SERVER['HTTP_HOST'] == '192.168.1.253' || $_SERVER['HTTP_HOST'] == '27.109.8.106:8080' || $_SERVER['REMOTE_ADDR'] == '27.109.8.106'))
		{
			echo "Select : <br>============<br>";
			$this->getQuery($sql);
			echo $msc . ' s<br>'; // in seconds
			echo ($msc * 1000) . ' ms<br><br>'; // in millseconds
			if($this->QUERY == "SELECT category_id,parent_id,category_name FROM `sky_category` WHERE `category_id` = 23 AND status='1'")
			{
			//var_dump(debug_backtrace());
			//echo '<br><br>';
			//exit;
			}
			
		}
		
		
		return $data;
	}

	/*function GetSqlSetString ($filedAry) {
		$return = NULL ;
		while (list($key, $value) = each($filedAry)) {
		  	$return .=  '`' . $key . '` = \'' .  mysqli_real_escape_string($this->CONN,$value) . '\', ';
		}
		$return = substr($return, 0, strlen($return)-2);
		return $return;
	}*/
	function GetSqlSetString ($filedAry) {
		$return = NULL ;
		#while (list($key, $value) = each($filedAry)) {
		foreach ($filedAry as $key => $value) {
		  	$return .=  '`' . $key . '` = \'' .  mysqli_real_escape_string($this->CONN,$value) . '\', ';
		}
		$return = substr($return, 0, strlen($return)-2);
		return $return;
	}
	
	function insert($tableName = NULL , $insertAry = NULL) {
		try {
			if (trim($tableName) == '')   throw new Exception('Table name required.');
			if (!is_array($insertAry))   throw new Exception('Table field array is required.');
			if (!($this->CONN))   throw new Exception('MySql connection close');
			$sql = "INSERT INTO " . $tableName . ' SET ' . $this->GetSqlSetString ($insertAry);
			$this->QUERY = $sql;
			$results = mysqli_query($this->CONN,$sql);
			if($results == false ) throw new Exception('MySql SQL ' . $this->Error($sql) );
		} catch (Exception $e) {
			$this->ErrorLogWrite( $e->getMessage() );
			return false;
		}
		$InsertId = mysqli_insert_id($this->CONN);
		return $InsertId;
	}
	/* Insert for adminpanel Start*/
	function insertNew($sql='') {
		
		try {
			if (trim($sql)=='')   throw new Exception('SQL is required.');
			$this->TotalQueryInPage[] = $sql;
			
			if (!($this->CONN))   throw new Exception('MySql connection close');
			$this->QUERY = $sql;
			$results = mysqli_query($this->CONN,$sql);
			if($results == false ) throw new Exception('MySql SQL ' . $this->Error($sql) );
		} catch (Exception $e) {
			
			$this->ErrorLogWrite( $e->getMessage() );
			return false;
		}
		$InsertId = mysqli_insert_id($this->CONN);
		return $InsertId;
	}
	/* Insert for adminpanel End*/
	function update($tableName = NULL , $updateAry = NULL, $whereCond = NULL) {
		try {
			if (trim($tableName) == '')   throw new Exception('Table name required.');
			if (trim($whereCond) == '')   throw new Exception('Where condition required.');
			if (!is_array($updateAry))   throw new Exception('Table field array is required.');
			if (!($this->CONN))   throw new Exception('MySql connection close');
			$sql = "UPDATE " . $tableName . ' SET ' . $this->GetSqlSetString ($updateAry) . ' WHERE ' . $whereCond ;
			
			//echo $sql;
			//exit;
			
			$this->QUERY = $sql;
			$results = mysqli_query($this->CONN,$sql);
			if($results == false ) throw new Exception('MySql SQL ' . $this->Error($sql) );
			else {
				$rslt = mysqli_affected_rows($this->CONN);
				if($rslt == 0 ) {
					return true;
				}	else {
					return $rslt;
				}
			}
		} catch (Exception $e) {
			$this->ErrorLogWrite( $e->getMessage() );
			return false;
		}
		return NULL;
		//return mysql_affected_rows($this->CONN);
	}
	
	function updateNew($tableName = NULL , $updateAry = NULL, $whereCond = NULL) {
		
		try {
			
			if (trim($tableName) == '')   throw new Exception('Table name required.');
			if (trim($whereCond) == '')   throw new Exception('Where condition required.');
			if (!is_array($updateAry))   throw new Exception('Table field array is required.');
			if (!($this->CONN))   throw new Exception('MySql connection close');
			
			echo $sql = "UPDATE " . $tableName . ' SET ' . $this->GetSqlSetString ($updateAry) . ' WHERE ' . $whereCond ;
			$this->QUERY = $sql; exit;
			$results = mysqli_query($this->CONN,$sql);
			if($results == false ) throw new Exception('MySql SQL ' . $this->Error($sql) );
			else {
				$rslt = mysqli_affected_rows($this->CONN);
				if($rslt == 0 ) {
					return true;
				}	else {
					return $rslt;
				}
			}
		} catch (Exception $e) {
			$this->ErrorLogWrite( $e->getMessage() );
			return false;
		}
		//return false;
		return mysqli_affected_rows($this->CONN);
	}
	
	function delete ($tableName = NULL ,  $whereCond = NULL) {
		try {
			if (trim($tableName) == '')   throw new Exception('Table name required.');
			if (trim($whereCond) == '')   throw new Exception('Where condition required.');
			if (!($this->CONN))   throw new Exception('MySql connection close');
			$sql = "DELETE FROM " . $tableName . ' WHERE ' . $whereCond ;
			$this->QUERY = $sql;
			$results = mysqli_query($this->CONN,$sql);
			if($results == false ) throw new Exception('MySql SQL ' . $this->Error($sql) );
		} catch (Exception $e) {
			$this->ErrorLogWrite( $e->getMessage() );
			return false;
		}
		return mysqli_affected_rows($this->CONN);
	}
	
	function fields ($tableName = NULL) {
		try {
			if (trim($tableName) == '')   throw new Exception('Table name required.');
			if (!($this->CONN))   throw new Exception('MySql connection close');
			$sql = "SHOW COLUMNS FROM " . $tableName ;
			$this->QUERY = $sql;
			$results = mysqli_query($this->CONN,$sql);
			if($results == false ) throw new Exception('MySql SQL ' . $this->Error($sql) );
		} catch (Exception $e) {
			$this->ErrorLogWrite( $e->getMessage() );
			return false;
		}
		$data = $this->mysqlFetchAssoc($results);
		mysqli_free_result($results); 
		return $data;
	}
	
	function tables () {
		try {
			if (trim($this->DATABASE) == '')   throw new Exception('Databse name is not available.');
			if (!($this->CONN))   throw new Exception('MySql connection close');
			$sql = "SHOW TABLES FROM " . $this->DATABASE ;
			$this->QUERY = $sql;
			$results = mysqli_query($this->CONN,$sql);
			if($results == false ) throw new Exception('MySql SQL ' . $this->Error($sql) );
		} catch (Exception $e) {
			$this->ErrorLogWrite( $e->getMessage() );
			return false;
		}
		$data = $this->mysqlFetchAssoc($results);
		mysqli_free_result($results);
		return $data;
	}

	function changeTablePrefix($old, $new) {
		$table_rs = $this->tables();
		
		for($i=0; $i<count($table_rs); $i++)	{
			$tempNew = str_replace($old, $new, $table_rs[$i]['Tables_in_'.DB_DATABASE]);
			echo $sql = "RENAME TABLE `".$table_rs[$i]['Tables_in_'.DB_DATABASE]."`  TO `".$tempNew."`; " ;
			echo "<br>";
		}
		return NULL;
	}
	
	/*function mysqlFetchAssoc ($results) {
		$count = 0;
		$data = array();
		while($row = mysqli_fetch_assoc($results))	{
			$row = array_map('ArrayStripcslashes', $row ); 
			$data[$count] = $row;
			$count++;
		}
		return $data;
	}*/
	function mysqlFetchAssoc($results) 
	{
		$count = 0;
		$data = array();
		
		while($row = mysqli_fetch_assoc($results))	
		{
			$row = array_map(array($this, 'ArrayStripcslashesnew'), $row ); 
			$data[$count] = $row;
			$count++;
		}
		return $data;
	}
	function ArrayStripcslashesnew ( $value) 
	{
		//return stripcslashes($value); 
		return $value; 
	}
	
	function sql_query ($sql = NULL) {
		$sql = trim($sql);
		try {
			if (trim($sql) == '')   throw new Exception('Query required.');
			if (!($this->CONN))   throw new Exception('MySql connection close');
			$this->QUERY = $sql;
			
			$results = mysqli_query($this->CONN,$sql);
			if($results == false ) throw new Exception('MySql SQL ' . $this->Error($sql) );
		} catch (Exception $e) {
			$this->ErrorLogWrite( $e->getMessage() );
			return false;
		}
		
		if(preg_match("/^select/i",$sql)) return $data = $this->mysqlFetchAssoc($results);
		return NULL;
	}
	
	function getInsertUpdateArray($tblName) { // This function will use only for developer....
		$RS = $this->fields($tblName);
		$forCnt = count($RS);
		$strAry = array();
		
		for($i=0; $i < $forCnt; $i++ )	{
			$strAry[] = '\''.$RS[$i]['Field'].'\'##=> $'.$RS[$i]['Field'] ;
		}
		$br = '<br>'; 
		$tab = chr(9).chr(9) ;
		$str = str_replace('##', $tab,  implode(','.$br , $strAry) ) ;
		echo $rntStr = html_entity_decode( 'array( 	' . $br . $str . $br . ');' );
		return NULL;
	}
	
	function getQuery() {
		if($this->QUERY) {
			echo $this->QUERY;
			echo "<BR><BR>";
		}
		return NULL;
	}

	function getCharacterSet($variable_name) {
		if(in_array($variable_name, array("character_set_client","character_set_connection","character_set_database","character_set_filesystem","character_set_results","character_set_server","character_set_system")))
		{
			$sql = "SHOW VARIABLES LIKE '".$variable_name."'";
			$results = mysqli_query($this->CONN,$sql);
			$charset_data = $this->mysqlFetchAssoc($results);
			if(count($charset_data) > 0) {
				$default_charset = $charset_data[0]['Value'];
				return $default_charset;
			}
		}
		return null;
	}

	function setCharacterSet($variable_name, $value) {
		if(in_array($variable_name, array("character_set_client","character_set_connection","character_set_database","character_set_filesystem","character_set_results","character_set_server","character_set_system")))
		{
			if($variable_name == 'character_set_client')
			{
				$sql = "SET CHARACTER SET '".$value."'";
				mysqli_query($this->CONN, $sql);
				return true;
			}
		}
		return false;
	}
	
	function ExplainQuery() {
		$sql = 'EXPLAIN ' . $this->QUERY;
		try {
			$results = mysqli_query($this->CONN,$sql);
			if($results == false ) throw new Exception('MySql SQL ' . $this->Error($sql) );
		} catch (Exception $e) {
			$this->ErrorLogWrite( $e->getMessage() );
			return false;
		}
		return $data = $this->mysqlFetchAssoc($results);
	}
	
	// Database back up system start	
	function fetch_array($db_query) {
    	return mysqli_fetch_array($db_query, MYSQLI_ASSOC);
	}
  
	function not_null($value) {
		if (is_array($value)) {
			  if (sizeof($value) > 0) {
				return true;
			  } else {
				return false;
			  }
		} else {
			  if ( (is_string($value) || is_int($value)) && ($value != '') && ($value != 'NULL') && (strlen(trim($value)) > 0)) {
				return true;
			  } else {
				return false;
			  }
		}
	}
	
	function deletePastMonthBackup () { // Before one month back delete process start
		if ($handle = opendir(DATABASE_BACKUP_PATH))	{
			$lastmonth = mktime (0,0,0,date("m")-1,date("d"), date("Y"));
			//$lastday = mktime (0,0,0,date("m"),date("d")-1, date("Y"));
			
			while (false !== ($file = readdir($handle)))
			{
				if($file!='.' and $file!='..' and $file!= '.htaccess' and $file!= 'index.php')
				{
					$xx=filemtime(DATABASE_BACKUP_PATH.$file);
					if($xx < $lastmonth)
					unlink(DATABASE_BACKUP_PATH.$file);
				}
			}
			closedir($handle);
		}
		return NULL;
	}
	
	function getDatabaseBackup () {
		ini_set('max_execution_time', 0);
		$this->deletePastMonthBackup();
		//$fileName = date("F_d_Y_H_i_s") . '_' . $this->DATABASE . ".sql" ;
		$fileName = date("F_d_Y") . '_' . $this->DATABASE . ".sql" ;

		$backup_file = DATABASE_BACKUP_PATH . $fileName ;
		
		if(!file_exists($backup_file))	$Database_Back_Up = true;
		else $Database_Back_Up = false;
		
		if($Database_Back_Up == true) {
			$fp = fopen($backup_file, 'w');
			$schema = '# Database Backup For ' . TITLE . "\n" .
					  '# Copyright (c) ' . date('Y') . ' ' . TITLE . "\n" .
					  '#' . "\n" .
					  '# Server version: ' . mysqli_get_server_info($this->CONN) . "\n" .
					  '#' . "\n" .
					  '# PHP Version: ' . phpversion() . "\n" .
					  '#' . "\n" .
					  '# Database Name: ' . $this->DATABASE . "\n" .
					  '# Database Host: ' . $this->SERVER . "\n" .
					  '#' . "\n" .
					  '# Generation Time: ' . date("F d, Y  h:i A") . "\n\n";
					  
			fputs($fp, $schema);
			$schema = NULL;
			$sql = 'SHOW TABLES';
			try {
				$tables_query = mysqli_query($this->CONN,$sql);
				if($tables_query == false ) throw new Exception('MySql SQL ' . $this->Error($sql) );
			} catch (Exception $e) {
				$this->ErrorLogWrite( $e->getMessage() );
				return false;
			}
			
			while ($tables = $this->fetch_array($tables_query)) 
			{
				  
				  list(,$table) = each($tables);
				  
				  mysqli_query($this->CONN,"ANALYZE TABLE `".$table."`");
				  
				  mysqli_query($this->CONN,"OPTIMIZE TABLE `".$table."`");
				  
				  //if($table != 'db_import_product')  continue;
				  
				  $schema .= "\n" . '-- --------------------------------------------------------';
				  $schema .= "\n\n" . 'DROP TABLE IF EXISTS `' . $table . '`;' . "\n" .
							'CREATE TABLE IF NOT EXISTS `' . $table . '` (' . "\n";
				
				  $table_list = array();
				  $sql_dsp = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . $this->DATABASE ."' AND TABLE_NAME = '" . $table ."'";
				 
				  try {
					$fields_query = mysqli_query($this->CONN,$sql_dsp);
					if($fields_query == false ) throw new Exception('MySql SQL ' . $this->Error($sql_dsp) );
				  }catch (Exception $e) {
					$this->ErrorLogWrite( $e->getMessage() );
					return false;
				  }
				  while ($fields = $this->fetch_array($fields_query)) {
					$table_list[] = $fields['COLUMN_NAME'];
					$schema .= '  `' . $fields['COLUMN_NAME'] . '` ' . $fields['COLUMN_TYPE'];
					if (isset($fields['COLLATION_NAME'])) $schema .= ' collate '. $fields['COLLATION_NAME'];
					if ($fields['IS_NULLABLE'] != 'YES') $schema .= ' NOT NULL';
					if($fields['COLUMN_DEFAULT'] == 'CURRENT_TIMESTAMP')	{
						if (strlen($fields['COLUMN_DEFAULT']) > 0) $schema .= ' default ' . $fields['COLUMN_DEFAULT'] ;
					}	else 	{
						if (strlen($fields['COLUMN_DEFAULT']) > 0) $schema .= ' default \'' . $fields['COLUMN_DEFAULT'] . '\'';
					}
					if (isset($fields['EXTRA'])) $schema .= ' ' . $fields['EXTRA'];
					//if (strlen($fields['COLUMN_COMMENT']) > 0) $schema .= ' COMMENT \'' . $fields['COLUMN_COMMENT'] .'\'';
					if (strlen($fields['COLUMN_COMMENT']) > 0) $schema .= ' COMMENT \'' . str_replace('\'', '\'\'',  $fields['COLUMN_COMMENT'] ).'\'';
					$schema .= ',' . "\n";
				  }
				  $schema = preg_replace("/,\n$/i", '', $schema);
				  $index = array();
				  $sql_that = "SHOW KEYS FROM `" . $table . "`";
				  try {
						$keys_query = mysqli_query($this->CONN,$sql_that);
						if($keys_query == false ) throw new Exception('MySql SQL ' . $this->Error($sql_that) );
				  } catch (Exception $e) {
						$this->ErrorLogWrite( $e->getMessage() );
						return false;
				  }
				  
				  while ($keys = $this->fetch_array($keys_query)) {
					$kname = $keys['Key_name'];
				
					if (!isset($index[$kname])) {
					  $index[$kname] = array('unique' => !$keys['Non_unique'],
											 'fulltext' => ($keys['Index_type'] == 'FULLTEXT' ? '1' : '0'),
											 'columns' => array());
					}
				
					$index[$kname]['columns'][] = $keys['Column_name'];
				  }
				
				  while (list($kname, $info) = each($index)) {
					$schema .= ',' . "\n";
				
					$columns = implode($info['columns'], ', ');
				
					if ($kname == 'PRIMARY') {
					  $schema .= '  PRIMARY KEY (' . $columns . ')';
					} elseif ( $info['fulltext'] == '1' ) {
					  $schema .= '  FULLTEXT ' . $kname . ' (' . $columns . ')';
					} elseif ($info['unique']) {
					  $schema .= '  UNIQUE ' . $kname . ' (' . $columns . ')';
					} elseif ($info['COMMENT']) {
					  $schema .= '  COMMENT ' . $kname . ' (' . $columns . ')';
					} else {
					  $schema .= '  KEY ' . $kname . ' (' . $columns . ')';
					}
				  }
				
				  $Engine  = '';
				  $Charset ='';
				  $Collate = '';
				  $AutoIncrement = '';
				  
				  $sql = "SELECT AUTO_INCREMENT, ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA = '".$this->DATABASE."' AND TABLE_NAME = '".$table."'"; 
				  $getEngine = $this->select($sql);
				  
				  if(strlen($getEngine[0]['ENGINE']) >0 )	{
				  	$Engine =  ' ENGINE=' . $getEngine[0]['ENGINE'];
				  }
				  
				  if(strlen($getEngine[0]['AUTO_INCREMENT']) > 0 )	{
				  	$AutoIncrement =  ' AUTO_INCREMENT=' . $getEngine[0]['AUTO_INCREMENT'];
				  }
				  
				  $sql = "SELECT * FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = '".$this->DATABASE."'";
				  $getSchemata = $this->select($sql);
				  
				  if(strlen($getSchemata[0]['DEFAULT_CHARACTER_SET_NAME']) > 0 ) {
				  	$Charset =  ' DEFAULT CHARSET=' . $getSchemata[0]['DEFAULT_CHARACTER_SET_NAME'];
				  }
				  
				  if(strlen($getSchemata[0]['DEFAULT_COLLATION_NAME']) > 0 )	{
				  	$Collate =  ' COLLATE=' . $getSchemata[0]['DEFAULT_COLLATION_NAME'];
				  }

				  $schema .= "\n" . ') '.  $Engine . $Charset . $Collate . $AutoIncrement . ' ;' . "\n\n";			  
				  fputs($fp, $schema);
				  $schema = NULL;
		
				$table_fields_string = "`" . implode('`,`', $table_list) . "`";

				//$sql_dipesh = "SELECT " . implode(',', $table_list) . " from `" . $table . "`";
				$sql_dipesh = "SELECT " . $table_fields_string . " from `" . $table . "`";
		
				try {
					$rows_query = mysqli_query($this->CONN,$sql_dipesh);
					if($rows_query == false ) throw new Exception('MySql SQL ' . $this->Error($sql_dipesh) );
				} catch (Exception $e) {
					$this->ErrorLogWrite( $e->getMessage() );
					return false;
				}
				
				//$schema_dipesh = 'INSERT INTO `' . $table . '` (' . implode(', ', $table_list) . ') VALUES ' . "\n";
				$schema_dipesh = 'INSERT INTO `' . $table . '` (' . $table_fields_string . ') VALUES ' . "\n";

			    $schema_dipesh_temp = $schema_dipesh;
				$rec_count = mysqli_num_rows($rows_query); 
			 	$cnt = 1 ;
				while ($rows = $this->fetch_array($rows_query)) {		
				  $schema = $schema_dipesh;
				  $schema_dipesh = '';
				  $schema .= '(';
				  
				  reset($table_list);
				  while (list(,$i) = each($table_list)) {
					if (!isset($rows[$i])) {
					  $schema .= 'NULL, ';
					} elseif ($this->not_null($rows[$i])) {
					  $row = mysqli_real_escape_string($this->CONN,$rows[$i]);
					  $row = preg_replace("/\n#/i", "\n".'\#', $row);
					  $schema .= '\'' . $row . '\', ';
					} else {
					  $schema .= '\'\', ';
					}
				  }
				  if($cnt == $rec_count || ($cnt%50) == 0) {
	 		  		  $schema = preg_replace('/, $/i', '', $schema) . ');' . "\n";
  					  $schema_dipesh = $schema_dipesh_temp;
				  }	else {
					  $schema = preg_replace('/, $/i', '', $schema) . '),' . "\n";
				  }
				  $cnt++;
				  fputs($fp, $schema);
				} 
				$schema = NULL;
			}
			
			fclose($fp);
			return NULL;
		}
	}
}

function ArrayStripcslashes ($value) {
	return 	stripcslashes($value); 
}

function ArrayEscapestring ( $value) {
	global $obj;
	if($obj->CONN)
	    return mysqli_real_escape_string($obj->CONN,$value);
	else
		return addcslashes($value);	
}
?>
