<?php
 
/**
 * get apn messages
 * Store reg id in users table
 */
header('Content-Type: text/xml');
print '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
print '<messages>';
 if ((isset($_POST["acr"]) && isset($_POST["memId"])&& isset($_POST["msgId"])) ||
     (isset( $_GET["acr"]) && isset( $_GET["memId"])&& isset( $_GET["msgId"])) ) {
		$acr = isset($_POST["acr"]) ? $_POST["acr"]: $_GET["acr"];
		$memid =isset($_POST["memId"]) ? $_POST["memId"]: $_GET["memId"];
		$msgid = isset($_POST["msgId"]) ? $_POST["msgId"]: $_GET["msgId"];
    // Store user details in db
    require '../db_functions_apn.php';
    
	$db = new DbFunctionsAPN();
    $res = $db->deleteMessage($acr,$memid,$msgid);
	print "OK";/*
	if ($res != false)
		$no_of_msgs = mysql_num_rows($res);
	else
		$no_of_msgs = 0;
	while ($row = mysql_fetch_array($res)) {+
		print '<message>';
		print '<id>'.$row["id"].'</id>';
		print '<title>'.$row["title"].'</title>';
		print '<content>'.$row["message"].'</content>';
		print '<msgread>'.$row["msgread"].'</msgread>';
		print '<created_at>'.$row["created_at"].'</created_at>';
		print '</message>';
	}
    */
} else {
    print 'Bad request';
}
print '</messages>';
?>