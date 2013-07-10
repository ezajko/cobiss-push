<?php

/**
 * Registering a user device
 * Store reg id in users table
 */
header('Content-Type: text/xml; charset=utf-8');
print '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';

 if (isset($_REQUEST["acr"]) &&  isset($_REQUEST["memId"]) &&  isset($_REQUEST["regId"]) &&
    !empty($_REQUEST["acr"]) && !empty($_REQUEST["memId"]) && !empty($_REQUEST["regId"])) {
   $acr = $_REQUEST["acr"];
    $memid = $_REQUEST["memId"];
    $token = $_REQUEST["regId"]; // GCM Registration ID
    // Store user details in db
    require_once '../lib/DbFunctionsAPN.php';
    
	$db = new DbFunctionsAPN();
    
	if ($db->doesUserExist($acr, $memid, $token)) {
		echo "\n<APN>OK</APN>"; // already registered
    } else {
		$res = $db->storeUser($acr, $memid, $token);
		if ($db->doesUserExist($acr, $memid, $token)) {
			
			print "\n<log>";
			include_once '../ApnsPHP/CobissAPN.php';
			$capn=new CobissAPN();
			$status=$capn->sendVelcomeMessage($token, $memid, $acr);
			print '</log>';
			
			if ($status>-1) {
				print "\n<APN>";
				echo 'OK';
				print '</APN>';
			} else {
				print "\n<APN>";
				echo 'error';
				print '</APN>';
			}
		}
		else {
			print "\n<APN>";
			print 'error';
			print '</APN>';
		}
	}
} else {
    print "\n<APN>Bad request</APN>";
}


?>