<?php
header('Content-Type: text/html; charset=utf-8');
//echo 'vrh';
if (isset($_GET["acr"]) && isset($_GET["message"]) && isset($_GET["title"])) {
    $message = $_GET["message"];
	$title = $_GET["title"];
	$acr = $_GET["acr"];
	$memid = $_GET["memId"];
	
 //   include_once '../APN.php';
	include_once '../db_functions_apn.php';
	print "saving ".$title;
	echo $message;
    $apn = new DbFunctionsAPN();
    
	echo $apn->addMessage($title, $message, $acr, $memid);
	
	
	//echo 'aafaf';
    
}
?>