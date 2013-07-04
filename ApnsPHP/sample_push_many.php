<?php
/**
 * @file
 * sample_push.php
 *
 * Push demo
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://code.google.com/p/apns-php/wiki/License
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to aldo.armiento@gmail.com so we can send you a copy immediately.
 *
 * @author (C) 2010 Aldo Armiento (aldo.armiento@gmail.com)
 * @version $Id$
 */

// Adjust to your timezone
date_default_timezone_set('Europe/Rome');

// Report all PHP errors
error_reporting(-1);

// Using Autoload all classes are loaded on-demand
require_once '../ApnsPHP/ApnsPHP/Autoload.php';

print "\n\nPushManyStart";

// Instanciate a new ApnsPHP_Push object
$push = new ApnsPHP_Push(
	ApnsPHP_Abstract::ENVIRONMENT_SANDBOX,
	'../ApnsPHP/mcobiss.pem'
);

// Set the Root Certificate Autority to verify the Apple remote peer
$push->setRootCertificationAuthority('../ApnsPHP/entrust.pem');

// Connect to the Apple Push Notification Service
$push->connect();




for ($i = 0; $i < sizeof($ids); $i++) {
	$message = new ApnsPHP_Message($ids[i]);
	$message->setBadge(1*$badges[i]);
	$message->setText('Sporočila');
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

// Examine the error message container
$aErrorQueue = $push->getErrors();
if (!empty($aErrorQueue)) {
	var_dump($aErrorQueue);
}

?>