<?php
/**
 * ApnsPHP implementation for mCOBISS. Holds static methods for batch sending
 * notifications and the welcome notification on registration. 
 * 
 */
 class CobissAPN {
	/**
	 * Sends a notification to inform the device that it has been successfully
	 * subscribed to notifications for the specified library membership.
	 * 
	 * @param unknown $token The device token.
	 * @param unknown $memid The member's ID
	 * @param unknown $acr The acronym of the library.
	 */
	public static function sendVelcomeMessage($token, $memid, $acr) {
		$result=1;
		date_default_timezone_set('Europe/Rome');
		error_reporting(-1);
		
		require_once '../ApnsPHP/ApnsPHP/Autoload.php';
		
		$push = new ApnsPHP_Push(
				ApnsPHP_Abstract::ENVIRONMENT_PRODUCTION,
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
	
	/**
	 * DEPRECATED - for testing only
	 * @param unknown $ids
	 * @param unknown $badges
	 */
	public static function notifyManyDefault($ids, $badges) {
		notifyMany($ids, $badges, "a message text");
	}
	
	/**
	 * Sends a notification to listed devices. The message holds
	 * a text and a badge - the number of unread messages.
	 * 
	 * @param unknown $ids Array of tokens
	 * @param unknown $badges Array of badges - notification count
	 * @param unknown $text - The body of the message.
	 * @return multitype:unknown A list of tokens to which notifications
	 * weren't delivered.
	 */
	public static function notifyMany($ids, $badges, $text) {
		
		date_default_timezone_set('Europe/Rome');
		
		error_reporting(-1);
		
		require_once '../ApnsPHP/ApnsPHP/Autoload.php';
		
		$push = new ApnsPHP_Push(
				ApnsPHP_Abstract::ENVIRONMENT_PRODUCTION,
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
				$message->setText($text);
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