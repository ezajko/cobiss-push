<?php
class CobissAPN {
	
	public static function sendVelcomeMessage($token, $memid, $acr) {
		$result=1;
		date_default_timezone_set('Europe/Rome');
		error_reporting(-1);
		
		require_once '../ApnsPHP/ApnsPHP/Autoload.php';
		
		$push = new ApnsPHP_Push(
				ApnsPHP_Abstract::ENVIRONMENT_SANDBOX,
				'../ApnsPHP/mcobiss.pem');
		$push->setRootCertificationAuthority('../ApnsPHP/entrust.pem');
		
		$push->connect();
		try {
			$message = new ApnsPHP_Message($token);
			$message->setCustomIdentifier("Message-Badge-".$badge);
			$message->setText('Naročeni ste na sporočila '.$memid.'@'.$acr.'.');
			$message->setSound();
			$message->setCustomProperty('acme2', array('bang', 'whiz'));
			$message->setCustomProperty('acme3', array('bing', 'bong'));
			$message->setExpiry(30);
			$push->add($message);
			$push->send();
			
		} catch (Exception $e) {
			print "\nCreating message failed - probably due to a bad token";
			print "\n\n".$e->getMessage()."\n";
			$result=-1;
		}
		
		try {
			$push->disconnect();
		} catch (Exception $e) {
			$result=-1;
		}
		
		$aErrorQueue = $push->getErrors();
		if (!empty($aErrorQueue)) {
			var_dump($aErrorQueue);
		}
	}
	
	public static function notifyMany($ids, $badges) {
		
		//date_default_timezone_set('Europe/Rome');
		
		error_reporting(-1);
		
		require_once '../ApnsPHP/ApnsPHP/Autoload.php';
		
		$push = new ApnsPHP_Push(
				ApnsPHP_Abstract::ENVIRONMENT_SANDBOX,
				'../ApnsPHP/mcobiss.pem');
		$push->setRootCertificationAuthority('../ApnsPHP/entrust.pem');
		
		//print "\nTry to connect\n";
		$push->connect();
		//print "\nConnected ".$res;
		
		$failedIds=array();
		for ($i = 0; $i < count($ids); $i++) {
			//print "\n\n".$i.": ".substr($ids[$i], 0,10)."... badge ".(1*$badges[$i]);
			try {
				$message = new ApnsPHP_Message($ids[$i]);
				$message->setCustomIdentifier("Message-Badge-".$badge);
				$message->setBadge(1*($badges[$i]));
				$message->setText('Imate neprebrana sporočila.');
				//print $message->getText();
				$message->setSound();
				$message->setCustomProperty('acme2', array('bang', 'whiz'));
				$message->setCustomProperty('acme3', array('bing', 'bong'));
				$message->setExpiry(30);
				$push->add($message);
			} catch (Exception $e) {
				//print "\nCreating message failed";
				//print "\n\n".$e->getMessage()."\n";
				$failedIds[]=$ids[$i];
			}
			
		}
		
		try {
			$push->send();
			//print "\nSent";
			// Disconnect from the Apple Push Notification Service
			$push->disconnect();
		} catch (Exception $e) {
			
		}
		//print "\nDisconnected";
		$aErrorQueue = $push->getErrors();
		if (!empty($aErrorQueue)) {
			var_dump($aErrorQueue);
		}
		return $failedIds;
	}
}
?>