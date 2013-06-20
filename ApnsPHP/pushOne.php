<?php

function pushOne($token,$badge) {

	// Adjust to your timezone
	date_default_timezone_set('Europe/Rome');
	
	// Report all PHP errors
	error_reporting(-1);
	
	// Using Autoload all classes are loaded on-demand
	require_once '../ApnsPHP/ApnsPHP/Autoload.php';
	
	// Instanciate a new ApnsPHP_Push object
	$push = new ApnsPHP_Push(
		ApnsPHP_Abstract::ENVIRONMENT_SANDBOX,
		'../ApnsPHP/mcobiss.pem'
	);
	
	// Set the Provider Certificate passphrase
	// $push->setProviderCertificatePassphrase('test');
	
	// Set the Root Certificate Autority to verify the Apple remote peer
	$push->setRootCertificationAuthority('../ApnsPHP/entrust.pem');
	
	// Connect to the Apple Push Notification Service
	$push->connect();
	
	// Instantiate a new Message with a single recipient
	$message = new ApnsPHP_Message('$token');
	
	print "message instantiated\n";
	
	// Set a custom identifier. To get back this identifier use the getCustomIdentifier() method
	// over a ApnsPHP_Message object retrieved with the getErrors() message.
	$message->setCustomIdentifier("Message-Badge-3");
	
	// Set badge icon to "3"
	$message->setBadge($badge);
	
	// Set a simple welcome text
	$message->setText('Hello APNs-enabled device!');
	
	// Play the default sound
	$message->setSound();
	
	// Set a custom property
	$message->setCustomProperty('acme2', array('bang', 'whiz'));
	
	// Set another custom property
	$message->setCustomProperty('acme3', array('bing', 'bong'));
	
	// Set the expiry value to 30 seconds
	$message->setExpiry(30);
	
	// Add the message to the message queue
	$push->add($message);
	
	// Send all messages in the message queue
	$push->send();
	
	// Disconnect from the Apple Push Notification Service
	$push->disconnect();
	
	// Examine the error message container
	$aErrorQueue = $push->getErrors();
	if (!empty($aErrorQueue)) {
		var_dump($aErrorQueue);
	}
}