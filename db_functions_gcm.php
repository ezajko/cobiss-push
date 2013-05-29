<?php
 
class DbFunctionsGCM {
 
    protected $db;
	protected $postfix;
    //put your code here
    // constructor
    function __construct() {
        require_once 'db_connect.php';
        $this->postfix='gcm';
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
    public function storeUser($acr, $memid, $dev_id) {
		$tableName=$acr.$memid.$this->postfix;
		$this->addToMemberList($acr, $memid);
		if ($this->doesUserExist($acr, $memid, $dev_id))
			return TRUE;
		
		// Create table
		$sql="CREATE TABLE IF NOT EXISTS $tableName (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`dev_id` text,
				`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
		// Execute query
		if (mysql_query($sql)) {
		   ;//echo "";
		}
		else {
			return FALSE;
		   ;//echo "Error creating table: " ;//. mysqli_error($con);
		}
        // insert user into database
        $result = mysql_query("INSERT INTO $tableName (dev_id, created_at) VALUES('$dev_id', NOW())");
        print "result: ".$result;//echo "";
		// check for successful store
        if ($result) {
            // get user details
            $id = mysql_insert_id(); // last inserted id
            $result = mysql_query("SELECT * FROM $tableName WHERE id = $id") or die(mysql_error());
            // return user details
            if (mysql_num_rows($result) > 0) {
                return mysql_fetch_array($result);
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }
	
	
	public function doesUserExist($acr, $memid, $dev_id) {
		$tableName=$acr.$memid.$this->postfix;
		$result=mysql_query("SELECT * FROM $tableName WHERE dev_id='$dev_id'");
		$no_of_users = mysql_num_rows($result);
		if ($no_of_users==0) return FALSE;
		else return TRUE;
	}
	
	public function deleteUser($acr, $memid, $dev_id) {
		$tableName=$acr.$memid.$this->postfix;
        
		$result = mysql_query("DELETE FROM $tableName WHERE dev_id='$dev_id'");
		
        return TRUE;
    }
	
	public function getAllRegistrationIds($acr, $memid) {
		$tableName=$acr.$memid.$this->postfix;
		$result=mysql_query("SELECT dev_id FROM $tableName");
		return $result;
	}
	
	public function addToMemberList($acr, $memid) {
		$result=mysql_query("SELECT * FROM members WHERE acr='$acr' and memid='$memid'");
		$no_of_users = mysql_num_rows($result);
		if ($no_of_users==0) 
			$result = mysql_query("INSERT INTO members (acr, memid) VALUES('$acr','$memid')");
        
	}
}
 
?>