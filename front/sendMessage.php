<?php
	
	 //dump($xml);
	print '<Push><Status>';
	
	include_once '../lib/DbFunctionsGCM.php';
	include_once '../lib/DbFunctionsAPN.php';
	include_once '../lib/DbFunctionsCommon.php';
	
	$com=new DbFunctionsCommon();
	$members=$com->getAllUsers();
	if (mysql_num_rows($members)==0) print 'no devices';
	else {
		//save message
		$gcm = new DbFunctionsGCM();
		$gcm->addMessage($Title, $Message, $LibraryId, $MemberId);
		
		print '<gcm>';
		$users=$gcm->getAllRegistrationIds($LibraryId,$MemberId);
		if ($users==0 || mysql_num_rows($users)==0) print 'no android devices';
		else {
			include_once '../lib/GCMsender.php';
			$gcm = new GCMsender();
			$ids=array();
			while ($row = mysql_fetch_array($users)) {
				$ids[]=$row["token"];
			}
			$result = $gcm->send_notification($ids, $Title, $Message, $LibraryId, $MemberId);
			print 'OK';
		}
		print '</gcm>';
	
		print '<apn>';
		$apn = new DbFunctionsAPN();
		//$apnSender = new APN();
		$users=$apn->getAllRegistrationIds($LibraryId,$MemberId);
		$no_of_users = mysql_num_rows($users);
		if ($no_of_users==0) print 'no ios devices';
		else {
			//$apn->addMessage($Title, $Message, $LibraryId, $MemberId);
			print "OK";
		}
		print '</apn>';
		
		print '</Status></Push>';
	  }

 
?>
