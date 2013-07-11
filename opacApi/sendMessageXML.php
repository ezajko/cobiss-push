<?php
/*
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Push>
	<LibraryId>SIKMB</LibraryId>
	<MemberId>0000137</MemberId>
	<Title>Naslov</Title>
	<Message>Besedilo obvestila...</Message>
</Push>
*/

  header('Content-Type: text/xml');
  print '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';

  $xml_post = file_get_contents('php://input');
  
 if ($xml_post) {
	$xml = simplexml_load_string($xml_post);
	//print_r($xml);
	$LibraryId= (string)strtoupper($xml->LibraryId[0]);
	$MemberId = (string)$xml->MemberId[0];
	$Title = 	(string)$xml->Title[0];
	$Message = 	(string)$xml->Message[0];
	//print "title ".$Title."\n\n";
	require_once '../front/sendMessage.php';
	
 }
 
?>
