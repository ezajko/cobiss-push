<?php
include_once '../DbFunctionsAPN.php';

$apn = new DbFunctionsAPN();
$users=$apn->getDistinctTokens();
if ($no_of_users>0) {
	$tokens=array();
	while ($row = mysql_fetch_array($users)) {
		$tokens[]=$row["token"];
	}
	
	$notifications=array();
	foreach ($tokens as &$token) {
		$notification->token=$token;
		$notification->count=$apn->getUnreadCount($tokenAPN);
		$notifications[]=$notification;
	}
}
?>