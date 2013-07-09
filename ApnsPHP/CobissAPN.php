<?php
class CobissAPN {
	private $push;
	
	function __construct() {
		require_once '../ApnsPHP/ApnsPHP/Autoload.php';
		date_default_timezone_set('Europe/Rome');
		error_reporting(-1);
		
		$push = new ApnsPHP_Push(
				ApnsPHP_Abstract::ENVIRONMENT_SANDBOX,
				'../ApnsPHP/mcobiss.pem');
		$push->setRootCertificationAuthority('../ApnsPHP/entrust.pem');
	}
	
	public function sendVelcomeMessage($token, $memid, $acr) {
		$push->connect();
		
		$message = new ApnsPHP_Message($token);
		$message->setCustomIdentifier("Message-Badge-".$badge);
		$message->setText(
				'Naroèeni ste na obvestila za èlanstvo'.$memid.'@'.$acr);
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
	
	public function notifyMany($ids, $badges) {
		$push->connect();
		
		for ($i = 0; $i < count($ids); $i++) {
			print '\n\nrow '.$ids[$i].' badge '.(1*$badges[$i]);
		
			$message = new ApnsPHP_Message($ids[$i]);
			$message->setBadge(1*($badges[$i]));
			$message->setText('SporoÄila');
			$message->setSound();
			$message->setCustomProperty('acme2', array('bang', 'whiz'));
			$message->setCustomProperty('acme3', array('bing', 'bong'));
			$message->setExpiry(30);
			$push->add($message);
		}
		
		
		// Send all messages in the message queue
		$push->send();
		
		// Disconnect from the Apple Push Notification Service
		$push->disconnect();
		
		$aErrorQueue = $push->getErrors();
		if (!empty($aErrorQueue)) {
			var_dump($aErrorQueue);
		}
	}
}
?>