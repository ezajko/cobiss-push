<?php
 header('Content-Type: text/xml; charset=utf-8');
/**
 * Gets notifications for the membership specified by an acronym and a membership id.
 * Store reg id in users table
 */
print '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
print '<messages>';
 if (isset($_REQUEST["acr"]) && isset($_REQUEST["memId"])) {
    $acr =  $_REQUEST["acr"];
    $memid =$_REQUEST["memId"];
    
    require '../lib/DbFunctionsAPN.php';
    
	$db = new DbFunctionsAPN();
    
    $res = $db->getAllMessagesByMember($acr,$memid);
	
	while ($row = mysql_fetch_array($res)) {
		
		print '<message>';
		print '<id>'.$row["mid"].'</id>';
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