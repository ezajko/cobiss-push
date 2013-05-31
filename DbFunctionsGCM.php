<?php
 
class DbFunctionsGCM {
 
    protected $db;
	
	protected $tokenTable;
    //put your code here
    // constructor
    function __construct() {
        require_once 'db_connect.php';
        $this->db = new DB_Connect();
        $this->db->connect();
        $this->tokenTable='gcm_tokens';
    }
 
    // destructor
    function __destruct() {
 
    }
	
	/**
     * Storing new user
     * returns user details
     */
    public function storeUser($acr, $memid, $dev_id) {
    	$uid;
    	$result=mysql_query("SELECT uid FROM users WHERE acr='$acr' and memid='$memid'");
    	if (mysql_num_rows($result) == 0) {
    		$result = mysql_query("INSERT INTO users (acr, memid) VALUES('$acr','$memid')");
    		$uid=mysql_insert_id();
    		//echo "vpisal ".$uid;
    	}
    	else {
    		$uid=mysql_fetch_array($result)["uid"];
    		//echo "obstaja ".$uid;
    	}
    	
    	$result=mysql_query("SELECT tid FROM $this->tokenTable WHERE uid='$uid' and token='$dev_id'");
    	if (mysql_num_rows($result) == 0) {
    		$result = mysql_query("INSERT INTO $this->tokenTable (uid, token) VALUES('$uid','$dev_id')");
    	}
    	
    }
	
	
	public function doesUserExist($acr, $memid, $dev_id) {
		$uid;
    	$result=mysql_query("SELECT uid FROM users WHERE acr='$acr' and memid='$memid'");
    	if (mysql_num_rows($result) == 0) {
    		return FALSE;
    	}
    	
    	$uid=mysql_fetch_array($result)["uid"];
    	    	
    	$result=mysql_query("SELECT tid FROM $this->tokenTable WHERE uid='$uid' and token='$dev_id'");
    	if (mysql_num_rows($result) == 0) {
    		return FALSE;
    	}
    	else return TRUE;
	}
	
	public function deleteUser($acr, $memid, $dev_id) {
		$uid;
    	$result=mysql_query("SELECT uid FROM users WHERE acr='$acr' and memid='$memid'");
    	if (mysql_num_rows($result) == 0) {
    		return FALSE;
    	}
    	
    	$uid=mysql_fetch_array($result)["uid"];
    	    	
    	$result=mysql_query("SELECT tid FROM $this->tokenTable WHERE uid='$uid' and token='$dev_id'");
    	if (mysql_num_rows($result) == 0) {
    		return FALSE;
    	}
    	else {;
        	$result = mysql_query("DELETE FROM $this->tokenTable WHERE uid='$uid' and token='$dev_id'");
		    return TRUE;
    	}
    }
	
	public function getAllRegistrationIds($acr, $memid) {
		$uid;
    	$result=mysql_query("SELECT uid FROM users WHERE acr='$acr' and memid='$memid'");
    	if (mysql_num_rows($result) == 0) {
    		$uid=0;
    	}
    	else 
    		$uid=mysql_fetch_array($result)["uid"];
    	
    	$result=mysql_query("SELECT token FROM $this->tokenTable WHERE uid='$uid'");
    	
    	return $result;
    	
	}
	

}
 
?>