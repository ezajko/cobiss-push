<?php
/**
 * Holds utilites to connect to the MySQL database.
 * @author matjaz
 *
 */
class DbConnect {
 
    // constructor
    function __construct() {
 
    }
 
    // destructor
    function __destruct() {
        // $this->close();
    }
 
    /**
     * Returns the connection reference.
     * @return resource The connection.
     */
    public function connect() {
       // require_once 'config.php';
    	require_once '../lib/config.php';
	   
		$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
		
        // selecting database
        mysql_select_db(DB_DATABASE);
		mysql_query ('SET NAMES utf8');
        // return database handler
        return $con;
    }
 
    /**
     * Closes the connection to the database.
     */
    public function close() {
        mysql_close();
    }
 
}
?>