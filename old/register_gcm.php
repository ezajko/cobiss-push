<?php
 

/**
 * Registering a user device
 * Store reg id in users table
 */
header('Content-Type: text/xml; charset=utf-8');
print '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
print '<APN>';
 if (isset($_POST["acr"]) && isset($_POST["memId"]) && isset($_POST["regId"])) {
    $acr = $_POST["acr"];
    $memid = $_POST["memId"];
    $gcm_regid = $_POST["regId"]; // GCM Registration ID
    // Store user details in db
    include_once './db_functions_push.php';
    include_once './GCM.php';
    
	$db = new DB_Functions_push();
    
	//$res = $db->deleteUser($acr, $memid, $gcm_regid);
	//print_r($res);
	
    $res = $db->storeUserGCM($acr, $memid, $gcm_regid);
	//TODO: error handling!
	echo 'OK';
	//print $result;
} else {
    print 'Bad request';
}
print '</APN>';
?>