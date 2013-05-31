<?php
header('Content-Type: text/html; charset=utf-8');
include_once 'DbFunctionsGCM.php';
 
class DbFunctionsAPN extends DbFunctionsGCM {
 
     function __construct() {
		parent::__construct();
        $this->tokenTable='apn_tokens';
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
		$result=mysql_query("SELECT uid FROM '$this->tokenTable' WHERE token='$tokenAPN'");
		if (mysql_num_rows($result) == 0)
			return 0;
		
		// build list of member ids
		$uids=array();
		while ($row = mysql_fetch_array($result)) {
			$uids[]=$row["uid"];
			print " ".$row["uid"]." ";
		}
		
		// count unread messages addressed to all members registered to device
		foreach ($uids as &$uid) {
			$result=mysql_query("SELECT uid FROM messages WHERE uid='$uid' and msgread=0");
		}
		
		return mysql_num_rows($result);
	}
	
	public function getAllMessagesByToken($tokenAPN) {
		// get all members the device is registered to be notified about
		$result=mysql_query("SELECT uid FROM '$tokenTable' WHERE token='$tokenAPN'");
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
		$result=mysql_query("UPDATE messages SET msgread=1 WHERE mid='$mid'");
		return $result;
	}
	
	public function deleteMessage($mid) {
		$result=mysql_query("DELETE FROM messages WHERE mid=$mid");
		return $result;
	}
	
	public function getDistinctTokens() {
		return mysql_query("SELECT DISTINCT token FROM `apn_tokens`");
	}
}
 
?>