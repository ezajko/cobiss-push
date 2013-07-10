<?php
 
class DbFunctionsGCM {
 
    protected $db;
	
	protected $tokenTable;
    
	/** Class constuctor
	 * connects to database */
    function __construct() {
        require_once '../lib/DbConnect.php';
        $this->db = new DbConnect();
        $this->db->connect();
        $this->tokenTable='gcm_tokens';
    }
 
    // destructor
    function __destruct() {
 
    }
	
	/** Storing new user  */
    public function storeUser($acr_, $memid, $dev_id) {
    	$uid;
    	$acr=strtoupper($acr_);
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
	
    /** Check if the device is subscribed <p>Returns TRUE or FALSE</p>  */
	public function doesUserExist($acr_, $memid, $dev_id) {
		$acr=strtoupper($acr_);
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
	
	/** Delets the subscription  */
	public function deleteUser($acr_, $memid, $dev_id) {
		$acr=strtoupper($acr_);
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
    
    /** Delets all subscriptions (app uninstalled)  */
    public function deleteToken($dev_id) {
    	$result = mysql_query("DELETE FROM $this->tokenTable WHERE token='$dev_id'");
    	return TRUE;
    	
    }
    
    /** Gets a list of registration IDs of a specified member  */
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
	
    /** Gets a list of registration IDs of a specified member  */
	public function getAllRegistrationIds($acr_, $memid) {
		$acr=strtoupper($acr_);
		
		$result=mysql_query("SELECT uid FROM users WHERE acr='$acr' and memid='$memid'");
    	if (mysql_num_rows($result) == 0) {
    		$uid=0;
    		return 0;
    	}
    	else {
    		$row=mysql_fetch_array($result);
    		$uid=$row["uid"];
    	}
    		
    	
    	$result=mysql_query("SELECT tid,token FROM $this->tokenTable WHERE uid='$uid'");
    	
    	return $result;
    	
	}
	
	/** Stores a message  */
	public function addMessage($title, $message, $acr_, $memid) {
		$acr=strtoupper($acr_);
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
	
	
	/** Get unread messages count for all device subscriptions  */
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
	
	/** Get all messages for all device subscriptions  */
	public function getAllMessagesByToken($token) {
		
		$result=mysql_query("select * from messages
			inner join users on users.uid = messages.uid
			inner join $this->tokenTable on $this->tokenTable.uid = messages.uid
			where $this->tokenTable.token ='$token'
			");//group by users.uid");
		return $result;
	}
	
	/** Get all messages for a member  */
	public function getAllMessagesByMember($acr_, $memId) {
		$acr=strtoupper($acr_);
		
		$result=mysql_query("select * from messages
				inner join users on users.uid = messages.uid
				where users.acr='$acr' and users.memid='$memId'
				");//group by users.uid");
		return $result;
	}
	
	
	/** <p><b>Deprecated</p></b> Get all messages for a member <p> old and dirty way</p>  */
	public function getAllMessages($acr_, $memId) {
		$acr=strtoupper($acr_);
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
	
	/** Mark message as read  */
	public function markMessageAsRead($mid) {
		$result=mysql_query("SELECT * FROM messages WHERE mid='$mid'");
		if (mysql_num_rows($result) == 0)
			return "no such msg";
		$result=mysql_query("UPDATE messages SET msgread=1 WHERE mid='$mid'");
		return $result;
	}
	
	/** Delete the message  */
	public function deleteMessage($mid) {
		$result=mysql_query("SELECT * FROM messages WHERE mid='$mid'");
		if (mysql_num_rows($result) == 0)
			return "no such msg";
		$result=mysql_query("DELETE FROM messages WHERE mid=$mid");
		return $result;
	}
	
	/** Get all unique tokens - usefull when displaying subscribed devices */
	public function getDistinctTokens() {
		return mysql_query("SELECT DISTINCT tid, token FROM `$this->tokenTable`");
	}

}
 
?>