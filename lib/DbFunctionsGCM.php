<?php
 
class DbFunctionsGCM {
 
    protected $db;
	
	protected $tokenTable;
    //put your code here
    // constructor
    function __construct() {
        require_once '../lib/DbConnect.php';
        $this->db = new DbConnect();
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
    		$row=mysql_fetch_array($result);
    		$uid=$row["uid"];
    	}
    	
    	$result=mysql_query("SELECT tid FROM $this->tokenTable WHERE uid='$uid' and token='$dev_id'");
    	$count=mysql_num_rows($result);
    	if ($count == 0) {
    		$result = mysql_query("INSERT INTO $this->tokenTable (uid, token) VALUES('$uid','$dev_id')");
    	}
    	
    }
	
	
	public function doesUserExist($acr, $memid, $dev_id) {
		$uid;
    	$result=mysql_query("SELECT uid FROM users WHERE acr='$acr' and memid='$memid'");
    	if (mysql_num_rows($result) == 0) {
    		return FALSE;
    	}
    	
    	$row=mysql_fetch_array($result);
    	$uid=$row["uid"];
    	    	
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
    	
    	$row=mysql_fetch_array($result);
    	$uid=$row["uid"];
    	    	
    	$result=mysql_query("SELECT tid FROM $this->tokenTable WHERE uid='$uid' and token='$dev_id'");
    	if (mysql_num_rows($result) == 0) {
    		return FALSE;
    	}
    	else {;
        	$result = mysql_query("DELETE FROM $this->tokenTable WHERE uid='$uid' and token='$dev_id'");
		    return TRUE;
    	}
    }
    
    public function getAllRegistrationIdsByUID($uid) {
    	    	 
    	$result=mysql_query("SELECT token FROM $this->tokenTable WHERE uid='$uid'");
    	
    	if (mysql_num_rows($result) == 0) return;
    	$tokens=array();
    	while ($row = mysql_fetch_array($result)) {
    		$tokens[]=$row["token"];
    		//print " ".$row["uid"]." ";
    	}
    	
    	return $tokens;
    	 
    }
	
	public function getAllRegistrationIds($acr, $memid) {
		$uid;
    	$result=mysql_query("SELECT uid FROM users WHERE acr='$acr' and memid='$memid'");
    	if (mysql_num_rows($result) == 0) {
    		$uid=0;
    	}
    	else {
    		$row=mysql_fetch_array($result);
    		$uid=$row["uid"];
    	}
    		
    	
    	$result=mysql_query("SELECT tid,token FROM $this->tokenTable WHERE uid='$uid'");
    	
    	return $result;
    	
	}
	
	public function addMessage($title, $message, $acr, $memid) {
		$uid;
		$result=mysql_query("SELECT uid FROM users WHERE acr='$acr' and memid='$memid'");
		if (mysql_num_rows($result) == 0)
			return -1;
		 
		$row=mysql_fetch_array($result);
		$uid=$row["uid"];
		//    var_dump(mysql_fetch_array($result));
		$unread=0;
		$result = mysql_query("INSERT INTO messages (uid, title, message, msgread, created_at) VALUES('$uid','$title','$message',0, NOW())");
	
		return mysql_insert_id();
	}
	
	
	
	public function getUnreadCount($tokenAPN) {
		// get all members the device is registered to be notified about
		$result=mysql_query("SELECT uid FROM $this->tokenTable WHERE token='$tokenAPN'");
		if (mysql_num_rows($result) == 0)
			return 0;
		//print " begin ";
		// build list of member ids
		$uids=array();
		while ($row = mysql_fetch_array($result)) {
			$uids[]=$row["uid"];
			//print " ".$row["uid"]." ";
		}
	
		// count unread messages addressed to all members registered to device
		foreach ($uids as &$uid) {
			$result=mysql_query("SELECT uid FROM messages WHERE uid='$uid' and msgread=0");
		}
	
		return mysql_num_rows($result);
	}
	
	public function getAllMessagesByToken($tokenAPN) {
		// get all members the device is registered to be notified about
		$result=mysql_query("SELECT uid FROM $this->tokenTable WHERE token='$tokenAPN'");
		if (mysql_num_rows($result) == 0)
			return 0;
	
		// build list of member ids
		$uids=array();
		while ($row = mysql_fetch_array($users)) {
			$uids[]=$row["uid"];
		}
	
		// count unread messages addressed to all members registered to device
		foreach ($uids as &$uid) {
			$result=mysql_query("SELECT * FROM messages WHERE uid='$uid'");
		}
	
		return $result;
	}
	
	public function getAllMessages($acr, $memId) {
		// get all members the device is registered to be notified about
		$result=mysql_query("SELECT uid FROM users WHERE acr='$acr' and memid='$memId'");
		if (mysql_num_rows($result) == 0)
			return 0;
	
		// build list of member ids
		$uids=array();
		while ($row = mysql_fetch_array($result)) {
			$uids[]=$row["uid"];
		}
	
		// count unread messages addressed to all members registered to device
		foreach ($uids as &$uid) {
			$result=mysql_query("SELECT * FROM messages WHERE uid='$uid'");
		}
	
		return $result;
	}
	
	public function markMessageAsRead($mid) {
		$result=mysql_query("SELECT * FROM messages WHERE mid='$mid'");
		if (mysql_num_rows($result) == 0)
			return "no such msg";
		$result=mysql_query("UPDATE messages SET msgread=1 WHERE mid='$mid'");
		return $result;
	}
	
	public function deleteMessage($mid) {
		$result=mysql_query("SELECT * FROM messages WHERE mid='$mid'");
		if (mysql_num_rows($result) == 0)
			return "no such msg";
		$result=mysql_query("DELETE FROM messages WHERE mid=$mid");
		return $result;
	}
	
	public function getDistinctTokens() {
		return mysql_query("SELECT DISTINCT tid, token FROM `$this->tokenTable`");
	}

}
 
?>