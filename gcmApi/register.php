<?php

/**
 * Subscribes the specified token to messages for the membership 
 * specified by the acronym and membership id. Sends a welcome message
 * if subsription successful.
 */

header('Content-Type: text/xml; charset=utf-8');
print '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
print '<GCM>';
 if (isset($_REQUEST["acr"]) &&  isset($_REQUEST["memId"]) &&  isset($_REQUEST["regId"]) &&
    !empty($_REQUEST["acr"]) && !empty($_REQUEST["memId"]) && !empty($_REQUEST["regId"])) {
    $acr = $_REQUEST["acr"];
    $memid = $_REQUEST["memId"];
    $token = $_REQUEST["regId"]; // GCM Registration ID
    // Store user details in db
    require_once '../lib/DbFunctionsGCM.php';
    require_once '../gcmApi/definitions.php';
    
	$db = new DbFunctionsGCM();
    
    if ($db->doesUserExist($acr, $memid, $token)) {
		echo 'already registered'; // already registered
    } else {
		$res = $db->storeUser($acr, $memid, $token);
		if ($db->doesUserExist($acr, $memid, $token)) {
			echo 'OK';
			
			require_once '../lib/GCMsender.php';
			$registatoin_ids = array($token);
			$gcm = new GCMsender();
			$result = $gcm->send_notification($registatoin_ids, 
				WELCOME_TITLE, WELCOME_TEXT.$memid."@".$acr, $acr, $memid);
	   		print $result;
		}
		else 
			echo 'NOT REGISTERED';
    }
} else {
    print 'Bad request';
}
print '</GCM>';

?>