<?php
 
class DB_Connect {
 
    // constructor
    function __construct() {
 
    }
 
    // destructor
    function __destruct() {
        // $this->close();
    }
 
    // Connecting to database
    public function connect() {
       // require_once 'config.php';
	   $DB_HOST= "localhost";
		$DB_USER= "root";
		$DB_PASSWORD= "q1w2e3";
		$DB_DATABASE= "push";

        // connecting to mysql
        $con = mysql_connect($DB_HOST, $DB_USER, $DB_PASSWORD);
		
        // selecting database
        mysql_select_db($DB_DATABASE);
		mysql_query ('SET NAMES utf8');
        // return database handler
        return $con;
    }
 
    // Closing database connection
    public function close() {
        mysql_close();
    }
 
}
?>