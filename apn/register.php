<?php

/**
 * Registering a user device
 * Store reg id in users table
 */
header('Content-Type: text/xml; charset=utf-8');
print '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
print '<GCM>';
 if (isset($_POST["acr"]) && isset($_POST["memId"]) && isset($_POST["regId"])) {
    $acr = $_POST["acr"];
    $memid = $_POST["memId"];
    $gcm_regid = $_POST["regId"]; // GCM Registration ID
    // Store user details in db
    require '../db_functions_apn.php';
    require '../GCM.php';
    
	$db = new DbFunctionsAPN();
    	
    $res = $db->storeUser($acr, $memid, $gcm_regid);
	//TODO: error handling!
	echo 'OK';
	//print $result;
} else {
    print 'Bad request';
}
print '</APN>';
?>