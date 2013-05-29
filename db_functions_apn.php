<?php
header('Content-Type: text/html; charset=utf-8');
include_once 'db_functions_gcm.php';
 
class DbFunctionsAPN extends DbFunctionsGCM {
 
     function __construct() {
		parent::__construct();
        $this->postfix="apn";
	}
	
	public function addMessage($title, $message, $acr, $memid) {
		$tableName=$acr.$memid."msgs";
		//if ($this->doesUserExist($acr, $memid, $dev_id))
		//	return true;
		// Create table
		
		$sql="CREATE TABLE IF NOT EXISTS ".$tableName." (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`title` text,
				`message` text,
				`msgread` INT(10) UNSIGNED NOT NULL,
				`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
		
		if (mysql_query($sql)) {
		   print "create ok";
		}
		else {
			return "Error creating table: " ;//. mysqli_error($con);
		}
		echo "writing".$title;
		$unread=0;
		$result = mysql_query("INSERT INTO ".$tableName." (title, message,msgread, created_at) VALUES('".$title."','".$message."',0, NOW())");
        echo $result;
		return $result;
		//if ($result) return true;
		//else return false;
	}
   
    public function getAllMessages($acr, $memid) {
		$tableName=$acr.$memid."msgs";
		$result=mysql_query("SELECT * FROM $tableName");
		return $result;
	}
	
	public function markMessageAsRead($acr, $memid, $mid) {
		$tableName=$acr.$memid."msgs";
		$result=mysql_query("UPDATE $tableName SET msgread=1 WHERE id=$mid");
		return $result;
	}
	
	public function deleteMessage($acr, $memid, $mid) {
		$tableName=$acr.$memid."msgs";
		$result=mysql_query("DELETE FROM $tableName WHERE id=$mid");
		return $result;
	}
	
	public function getUnreadCount($acr, $memid, $mid) {
		$tableName=$acr.$memid."msgs";
		$result=mysql_query("SELECT * FROM $tableName WHERE msgread=1");
		return mysql_num_rows($result);
	}
}
 
?>