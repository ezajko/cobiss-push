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
