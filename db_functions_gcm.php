<?php
 
class DbFunctionsGCM {
 
    protected $db;
	protected $postfix;
	
	protected $tableName;
    //put your code here
    // constructor
    function __construct() {
        require_once 'db_connect.php';
        $this->postfix='gcm';
        $this->db = new DB_Connect();
        $this->db->connect();
        $this->tableName='gcm_tokens';
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
    	
    	$result=mysql_query("SELECT gcmid FROM $this->tableName WHERE uid='$uid' and token='$dev_id'");
    	if (mysql_num_rows($result) == 0) {
    		$result = mysql_query("INSERT INTO $this->tableName (uid, token) VALUES('$uid','$dev_id')");
    	}
    	
    }
	
	
	public function doesUserExist($acr, $memid, $dev_id) {
		$uid;
    	$result=mysql_query("SELECT uid FROM users WHERE acr='$acr' and memid='$memid'");
    	if (mysql_num_rows($result) == 0) {
    		return FALSE;
    	}
    	else {
    		$uid=mysql_fetch_array($result)["uid"];
    		//echo "obstaja ".$uid;
    	}
    	
    	$result=mysql_query("SELECT gcmid FROM $this->tableName WHERE uid='$uid' and token='$dev_id'");
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
    	else {
    		$uid=mysql_fetch_array($result)["uid"];
    		//echo "obstaja ".$uid;
    	}
    	
    	$result=mysql_query("SELECT gcmid FROM $this->tableName WHERE uid='$uid' and token='$dev_id'");
    	if (mysql_num_rows($result) == 0) {
    		return FALSE;
    	}
    	else {;
        	$result = mysql_query("DELETE FROM $this->tableName WHERE uid='$uid' and token='$dev_id'");
		    return TRUE;
    	}
    }
	
	public function getAllRegistrationIds($acr, $memid) {
		$tableName=$acr.$memid.$this->postfix;
		$result=mysql_query("SELECT dev_id FROM $tableName");
		return $result;
	}
	

}
 
?>