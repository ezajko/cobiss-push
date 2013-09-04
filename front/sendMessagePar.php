<?php
/**
Processes the GET/POST parameters and calls sendMessage.php
*/

  header('Content-Type: text/xml');
print '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';

if (isset($_REQUEST["acr"]) && 
	isset($_REQUEST["memId"]) && 
	isset($_REQUEST["title"])&& 
	isset($_REQUEST["message"])) {
		
		$LibraryId = $_REQUEST["acr"];
		$MemberId = $_REQUEST["memId"];
		$Title = $_REQUEST["title"];
		$Message=$_REQUEST["message"];
		
		require_once 'sendMessage.php';
	
	}
 
?>
