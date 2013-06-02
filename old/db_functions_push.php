<?php
 
class DB_Functions_push {
 
    private $db;
 
    //put your code here
    // constructor
    function __construct() {
        include_once './db_connect.php';
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
    public function storeUserAPN($acr, $memid, $gcm_regid) {
		$tableName=$acr.$memid."apn";
		
		// Create table
		$sql="CREATE TABLE IF NOT EXISTS $tableName (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`gcm_regid` text,
				`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
		// Execute query
		if (mysql_query($sql))
		{
		   ;//echo "";
		}
		else
		{
		   echo "Error creating table: " ;//. mysqli_error($con);
		}
        // insert user into database
        $result = mysql_query("INSERT INTO $tableName (gcm_regid, created_at) VALUES('$gcm_regid', NOW())");
        // check for successful store
        if ($result) {
            // get user details
            $id = mysql_insert_id(); // last inserted id
            $result = mysql_query("SELECT * FROM $tableName WHERE id = $id") or die(mysql_error());
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
	
	public function storeUserGCM($acr, $memid, $gcm_regid) {
		$tableName=$acr.$memid."gcm";
		
		// Create table
		$sql="CREATE TABLE IF NOT EXISTS $tableName (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`gcm_regid` text,
				`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
		// Execute query
		if (mysql_query($sql))
		{
		   ;//echo "";
		}
		else
		{
		   echo "Error creating table: " ;//. mysqli_error($con);
		}
        // insert user into database
        $result = mysql_query("INSERT INTO $tableName (gcm_regid, created_at) VALUES('$gcm_regid', NOW())");
        // check for successful store
        if ($result) {
            // get user details
            $id = mysql_insert_id(); // last inserted id
            $result = mysql_query("SELECT * FROM $tableName WHERE id = $id") or die(mysql_error());
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
	
}
 
?>