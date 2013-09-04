<?php

/**
 * Only holds a method for obtaining the list of subscribed memberships.
 * @author matjaz
 *
 */
class DbFunctionsCommon {
 
    private $db;
 
    //put your code here
    // constructor
    function __construct() {
        require_once '../lib/DbConnect.php';
        $this->db = new DbConnect();
        $this->db->connect();
    }
 
    // destructor
    function __destruct() {
    }
    
    public function getAllUsers() {
        $result = mysql_query("select * FROM users");
        return $result;
    }

}
 
?>