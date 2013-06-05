<?php
header('Content-Type: text/html; charset=utf-8');
if (isset($_GET["memid"]) && isset($_GET["msg"]) && isset($_GET["title"])) {
    $message = $_GET["msg"];
	$title = $_GET["title"];
	$memid = $_GET["memid"];
	$acr = $_GET["acr"];
	
 //   include_once '../APN.php';
	include_once '../lib/DbFunctionsAPN.php';
	echo $message;
    $apn = new DbFunctionsAPN();
    $apn->addMessage($title, $message, $acr, $memid);
	
    
    include_once '../lib/DbFunctionsGCM.php';
    $gcm = new DbFunctionsGCM();
    $result=$gcm->getAllRegistrationIds($acr, $memid);
    
    if (mysql_num_rows($result)>0) {
    	$tokens=array();
    	while ($row = mysql_fetch_array($result)) {
    		$tokens[]=$row["token"];
    		//print " ".$row["uid"]." ";
    	}
	    include_once '../lib/GCMsender.php';
	    $sender = new GCMsender();
	    $sender->send_notification($tokens, $title, $message, $acr, $memid);
    }
	//echo 'aafaf';
    
}
?>