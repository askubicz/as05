<?php

class Database {
	
	// declare and initialize variables for connect() function
	private static $dbName         = 'askubicz355wi20' ; 
	private static $dbHost         = '10.8.30.49' ;
	private static $dbUsername     = 'askubicz355wi20';
	private static $dbUserPassword = 'Melon!Lord!7!';
	
	// declare and initialize PDO instance variable: $connection
	private static $connection  = null;
	
	// method: __construct()
	public function __construct() {
		exit('No constructor required for class: Database');
	} 
	
	// method: connect()
	public static function connect() {
		if (null == self::$connection) {      
			try {
				self::$connection =  new PDO( "mysql:host=".self::$dbHost.";"."dbname=".self::$dbName, self::$dbUsername, self::$dbUserPassword);  
			}
			catch(PDOException $e) { die($e->getMessage()); }
		} 	
		// echo "Connected."; exit(); // uncomment to test database connection
		return self::$connection;
	} 
	
	// method: disconnect()
	public static function disconnect() {
		self::$connection = null;
	} 
	
} // end class: Database
?>
