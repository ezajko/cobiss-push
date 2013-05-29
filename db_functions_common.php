<?php
 
class DbFunctionsCommon {
 
    private $db;
 
    //put your code here
    // constructor
    function __construct() {
        require_once 'db_connect.php';
        $this->db = new DB_Connect();
        $this->db->connect();
    }
 
    // destructor
    function __destruct() {
    }
    
    public function getAllUsers() {
        $result = mysql_query("select * FROM members");
        return $result;
    }

}
 
?>