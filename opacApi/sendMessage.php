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
/*
  $xml_post = file_get_contents('php://input');
  
  if ($xml_post) {
	$xml = simplexml_load_string($xml_post);
	//print_r($xml);
	$LibraryId= (string)strtoupper($xml->LibraryId[0]);
	$MemberId = (string)$xml->MemberId[0];
	$Title = 	(string)$xml->Title[0];
	$Message = 	(string)$xml->Message[0];
	*/

if (isset($_REQUEST["acr"]) && isset($_REQUEST["memId"]) && isset($_REQUEST["title"])&& isset($_REQUEST["msg"])) {
	$LibraryId = $_REQUEST["acr"];
	$MemberId = $_REQUEST["memId"];
	$Title = $_REQUEST["title"];
	$Message=$_REQUEST["msg"];
	
	 //dump($xml);
	print '<Push><Status>';
	
	include_once '../lib/DbFunctionsGCM.php';
	include_once '../lib/DbFunctionsAPN.php';
	
	
	print '<gcm>';
	$gcm = new DbFunctionsGCM();
	$users=$gcm->getAllRegistrationIds($LibraryId,$MemberId);
	if ($users==0 || mysql_num_rows($users)==0) print 'no reg ids';
	else {
		include_once '../lib/GCMsender.php';
		$gcm = new GCMsender();
		$ids=array();
		while ($row = mysql_fetch_array($users)) {
			$ids[]=$row["token"];
		}
		$result = $gcm->send_notification($ids, $Title, $Message, $LibraryId, $MemberId);
	}
	print '</gcm>';

	print '<apn>';
	$apn = new DbFunctionsAPN();
	//$apnSender = new APN();
	$users=$apn->getAllRegistrationIds($LibraryId,$MemberId);
	$no_of_users = mysql_num_rows($users);
	if ($no_of_users==0) print 'no reg ids';
	else {
		$apn->addMessage($Title, $Message, $LibraryId, $MemberId);
		echo "message saved";
	}
	print '</apn>';
	
	print '</Status></Push>';
	
	
  }
  
 
?>
