<?php
// configuration
error_reporting(1);
define('dbtype', "mysql");
define('dbhost', "localhost");
define('dbname', "virgindb");
define('dbuser', "root");
define('dbpass', "root");
// database connection
class Connection{
	public static function make_connection(){
		try{
			$dbConnection = new PDO(dbtype.':dbname='.dbname.';host='.dbhost.';charset=utf8', dbuser, dbpass);
			$dbConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
			echo $e->getMessage();
		}
		return $dbConnection;
	}

	public static function connection_close(){
		$arr=func_num_args();
		foreach ($arr as $indx=>$val){
			$arr[$indx]=NULL;
		}
	}
}

?>