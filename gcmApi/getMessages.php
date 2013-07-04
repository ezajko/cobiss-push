<?php
 header('Content-Type: text/xml; charset=utf-8');
/**
 * get apn messages
 * Store reg id in users table
 */
print '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
print '<messages>';
 if (isset($_REQUEST["devId"]) ) {
    $devId =  $_REQUEST["devId"];
    
    //print $devId;
    require '../lib/DbFunctionsGCM.php';
    
	$db = new DbFunctionsGCM();
    
    $res = $db->getAllMessagesByToken($devId);/*
	if ($res != false)
		$no_of_msgs = mysql_num_rows($res);
	else
		$no_of_msgs = 0;*/
	while ($row = mysql_fetch_array($res)) {
		
		print '<message>';
		print '<id>'.$row["mid"].'</id>';
		print '<base>'.$row["acr"].'</base>';
		print '<member>'.$row["memid"].'</member>';
		print '<title>'.$row["title"].'</title>';
		print '<content>'.$row["message"].'</content>';
		print '<msgread>'.$row["msgread"].'</msgread>';
		print '<created_at>'.$row["created_at"].'</created_at>';
		print '</message>';
	}
    
} else {
    print 'Bad request';
}
print '</messages>';
?>