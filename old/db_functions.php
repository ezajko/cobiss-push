<?php
 
class DB_Functions {
 
    private $db;
 
    //put your code here
    // constructor
    function __construct() {
        include_once 'db_connect.php';
        // connecting to database
        $this->db = new DB_Connect();
        $this->db->connect();
    }
 
    // destructor
    function __destruct() {
 
    }
    /**
     * Storing new user
     * returns user details
     */
    public function storeUser($acr, $memid, $gcm_regid) {
        // insert user into database
        $result = mysql_query("INSERT INTO gcm_users_cobiss(acr, memid, gcm_regid, created_at) VALUES('$acr', '$memid', '$gcm_regid', NOW())");
        // check for successful store
        if ($result) {
            // get user details
            $id = mysql_insert_id(); // last inserted id
            $result = mysql_query("SELECT * FROM gcm_users_cobiss WHERE id = $id") or die(mysql_error());
            // return user details
            if (mysql_num_rows($result) > 0) {
                return mysql_fetch_array($result);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
	
	/**
	DELETE FROM `gcm_users_cobiss` WHERE gcm_regid = 'APA91b' 
     * Storing new user
     * returns user details
     */
	public function deleteAllUsersWithRegId($gcm_regid) {
        // insert user into database
        $result = mysql_query("DELETE FROM `gcm_users_cobiss` WHERE gcm_regid = '$gcm_regid' ");
        // check for successful store
        if ($result) {
            // get user details
            $id = mysql_insert_id(); // last inserted id
            $result = mysql_query("SELECT * FROM gcm_users_cobiss WHERE id = $id") or die(mysql_error());
            // return user details
            if (mysql_num_rows($result) > 0) {
                return mysql_fetch_array($result);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
	
	
	public function deleteUser($acr, $memid, $regid) {
        // insert user into database
        $result = mysql_query("DELETE FROM `gcm_users_cobiss` WHERE memid = '$memid' and acr = '$acr' and gcm_regid='$regid'");
        return $result;
    }
 
    /**
     * Getting all users
     */
    public function getAllUsers() {
        $result = mysql_query("select * FROM gcm_users_cobiss");
        return $result;
    }
 
	public function getAllRegistrationIds($acr, $memid) {
		$result=mysql_query("SELECT gcm_regid FROM gcm_users_cobiss WHERE memid = $memid and acr=$acr");
		return $result;
	}
	
	public function doesUserExist($acr, $memid, $regid) {
		$result=mysql_query("SELECT * FROM gcm_users_cobiss WHERE memid = '$memid' and acr = '$acr' and gcm_regid='$regid'");
		$no_of_users = mysql_num_rows($result);
		if ($no_of_users==0) return 'FALSE';
		else return 'TRUE';
	}
	
	public function getUserInfoByRegId($regid) {
		$result=mysql_query("SELECT * FROM gcm_users_cobiss WHERE gcm_regid = $regid");
		return $result;
	}
}
 
?>