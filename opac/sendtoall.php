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
	
	 //dump($xml);
	print '<Push><Status>';
	
	include_once '../db_functions_gcm.php';
	include_once '../db_functions_apn.php';
	
	include_once '../GCM.php';
    
	$gcm = new DbFunctionsGCM();
	$gcmSender = new GCM();
	
	print '<gcm>';
	$users=$gcm->getAllRegistrationIds($LibraryId,$MemberId);
	$no_of_users = mysql_num_rows($users);
	if ($no_of_users==0) print 'no reg ids';
	else {
		$gcm = new GCM();
		$ids=array();
		while ($row = mysql_fetch_array($users)) {
			$ids[]=$row["gcm_regid"];
		}
		$result = $gcm->send_notification($ids, $Title, $Message, $LibraryId, $MemberId);
		echo($result);
	}
	print '</gcm>';

	print '<apn>';
	$apn = new DbFunctionsAPN();
	//$apnSender = new APN();
	$users=$gcm->getAllRegistrationIds($LibraryId,$MemberId);
	$no_of_users = mysql_num_rows($users);
	if ($no_of_users==0) print 'no reg ids';
	else {
		//save msg to DB
		//
		$gcm = new GCM();
		$ids=array();
		while ($row = mysql_fetch_array($users)) {
			$ids[]=$row["gcm_regid"];
		}
		$result = $gcm->send_notification($ids, $Title, $Message, $LibraryId, $MemberId);
		echo($result);
	}
	print '</apn>';
	
	print '</Status></Push>';
	
	return;
  }
  
 
?>
