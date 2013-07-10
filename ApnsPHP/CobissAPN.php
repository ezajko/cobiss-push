<?php
class CobissAPN {
	
	public static function sendVelcomeMessage($token, $memid, $acr) {
		date_default_timezone_set('Europe/Rome');
		error_reporting(-1);
		
		require_once '../ApnsPHP/ApnsPHP/Autoload.php';
		
		$push = new ApnsPHP_Push(
				ApnsPHP_Abstract::ENVIRONMENT_SANDBOX,
				'../ApnsPHP/mcobiss.pem');
		$push->setRootCertificationAuthority('../ApnsPHP/entrust.pem');
		
		$push->connect();
		
		$message = new ApnsPHP_Message($token);
		$message->setCustomIdentifier("Message-Badge-".$badge);
		$message->setText(
				'Naroceni ste na obvestila za ');//.$memid.'@'.$acr.'.');
		$message->setSound();
		$message->setCustomProperty('acme2', array('bang', 'whiz'));
		$message->setCustomProperty('acme3', array('bing', 'bong'));
		$message->setExpiry(30);
		$push->add($message);
		
		$push->send();
		$push->disconnect();
		
		$aErrorQueue = $push->getErrors();
		if (!empty($aErrorQueue)) {
			var_dump($aErrorQueue);
		}
	}
	
	public static function notifyMany($ids, $badges) {
		
		date_default_timezone_set('Europe/Rome');
		error_reporting(-1);
		
		require_once '../ApnsPHP/ApnsPHP/Autoload.php';
		
		$push = new ApnsPHP_Push(
				ApnsPHP_Abstract::ENVIRONMENT_SANDBOX,
				'../ApnsPHP/mcobiss.pem');
		$push->setRootCertificationAuthority('../ApnsPHP/entrust.pem');
		
		print "\nTry to connect\n";
		$push->connect();
		print "\nConnected ".$res;
		
		$failedIds=array();
		for ($i = 0; $i < count($ids); $i++) {
			print "\n\nrow ".$ids[$i]." badge ".(1*$badges[$i]);
			try {
				$message = new ApnsPHP_Message($ids[$i]);
				$message->setCustomIdentifier("Message-Badge-".$badge);
				$message->setBadge(1*($badges[$i]));
				$message->setText('Imate sporocila.');
				$message->setSound();
				$message->setCustomProperty('acme2', array('bang', 'whiz'));
				$message->setCustomProperty('acme3', array('bing', 'bong'));
				$message->setExpiry(30);
				$push->add($message);
			} catch (Exception $e) {
				print "\nCreating message failed";
				$failedIds[]=$ids[$i];
			}
			
		}
		
		
		// Send all messages in the message queue
		$push->send();
		print "\nSent";
		// Disconnect from the Apple Push Notification Service
		$push->disconnect();
		print "\nDisconnected";
		$aErrorQueue = $push->getErrors();
		if (!empty($aErrorQueue)) {
			var_dump($aErrorQueue);
		}
		return $failedIds;
	}
}
?>