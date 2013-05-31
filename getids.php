<?php
/*
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Get_RegId>
	<LibraryId>sikmb</LibraryId>
	<MemberId>110507</MemberId>
</Get_RegId>
*/
header('Content-Type: text/xml');
print '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';

  $xml_post = file_get_contents('php://input');
  
  // If we receive data, save it.
  if ($xml_post) {
    //echo $xml_post;
	
	$xml = simplexml_load_string($xml_post);
	//print_r($xml);
	$acr   = $xml->LibraryId[0];
	$userId= $xml->MemberId[0];
	
	//print $acr;
	//print $userId;
	
	include_once './db_functions.php';
    $db = new DB_Functions();
    
	$users=$db->getAllRegistrationIds($acr,$userId);
	$no_of_users = mysql_num_rows($users);
	
	print '<Set_RegId>';
	while ($row = mysql_fetch_array($users)) {
		echo '<RegId>'.$row["gcm_regid"].'</RegId>';
	}
	
	print '</Set_RegId>';
	
	return;
  }
?>
