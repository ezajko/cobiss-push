<?php
//header('Content-Type: text/html; charset=utf-8');
echo 'vrh';
if (isset($_GET["token"]) && isset($_GET["badge"]) ) {
    
	$token = $_GET["token"];
	$badge = $_GET["badge"];
	
	print "\nbadge: ".$badge."\ntoken: ".$token;
    /*
	include_once '../APN.php';
	*/
// 	include_once '../ApnsPHP/samplePush.php';
	include_once '../ApnsPHP/CobissAPN.php';
	
	$capn=new CobissAPN();
	$tokens=array();
	$tokens[]=$token;
	print $capn->notifyManyDefault($tokens, $badges);
	//echo pushOne($token, $badge);
	//echo 'aafaf';
    
}
?>