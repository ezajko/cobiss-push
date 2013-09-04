<?php
 
/**
 *  Returns TRUE if the specified token is already subscribed to messages
 *  for the membership specified by member id and acronym.
 */
header('Content-Type: text/xml; charset=utf-8');
print '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
print '<GCM>';
if (isset($_REQUEST["acr"]) && isset($_REQUEST["memId"]) && isset($_REQUEST["regId"])) {
    $acr = $_REQUEST["acr"];
    $memid = $_REQUEST["memId"];
    $token = $_REQUEST["regId"]; // GCM Registration ID
    // Store user details in db
    require '../lib/DbFunctionsGCM.php';
    
	$db = new DbFunctionsGCM();
    
    if ($db->doesUserExist($acr, $memid, $token))
    	echo 'TRUE';
    // send notification
    else
    	echo 'FALSE';
   
} else {
    print 'Bad request';
}
print '</GCM>';
?>