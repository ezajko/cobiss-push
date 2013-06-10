<?php

/**
 * Registering a user device
 * Store reg id in users table
 */
header('Content-Type: text/xml; charset=utf-8');
print '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
print '<APN>';
 if (isset($_REQUEST["acr"]) && isset($_REQUEST["memId"]) && isset($_REQUEST["regId"])) {
    $acr = $_REQUEST["acr"];
    $memid = $_REQUEST["memId"];
    $token = $_REQUEST["regId"]; // GCM Registration ID
    // Store user details in db
    require_once '../lib/DbFunctionsAPN.php';
    
	$db = new DbFunctionsAPN();
    
    //echo $res = $db->doesUserExist($acr, $memid, $gcm_regid);
	$res = $db->storeUser($acr, $memid, $token);
	if ($db->doesUserExist($acr, $memid, $token))
		echo 'OK';
	else 
		echo 'NOT REGISTERED';
	//print $result;
} else {
    print 'Bad request';
}
print '</APN>';

?>