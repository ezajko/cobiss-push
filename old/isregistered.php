<?php
 

/**
 * Registering a user device
 * Store reg id in users table
 */
header('Content-Type: text/xml');
print '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
print '<GCM>';
 if (isset($_POST["acr"]) && isset($_POST["memId"]) && isset($_POST["regId"])) {
    $acr = $_POST["acr"];
    $memid = $_POST["memId"];
    $gcm_regid = $_POST["regId"]; // GCM Registration ID
    // Store user details in db
    include_once './db_functions.php';
    
	$db = new DB_Functions();
    
    $res = $db->doesUserExist($acr, $memid, $gcm_regid);
 
    print $res;
} else {
    print 'Bad request';
}
print '</GCM>';
?>