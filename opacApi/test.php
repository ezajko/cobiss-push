<?php


header('Content-Type: text/xml');
print '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';

require_once '../db_connect.php';

$db = new DB_Connect();

echo $db;

  $xml_post = file_get_contents('php://input');
  
  if ($xml_post) {
	$xml = simplexml_load_string($xml_post);
	//print_r($xml);
	$RegId  = 	(string)$xml->RegId[0];
	$LibraryId= (string)$xml->LibraryId[0];
	$MemberId = (string)$xml->MemberId[0];
	$Title = 	(string)$xml->Title[0];
	$Message = 	(string)$xml->Message[0];
	print '<Push><Status>';
	
	include_once './GCM.php';
	$gcm = new GCM();
	$ids=array();
	$ids[]=$RegId;
	$result = $gcm->send_notification($ids, $Title, $Message, $LibraryId, $MemberId);
	print $result;	
	print '</Status></Push>';
	
	return;
  }
?>
