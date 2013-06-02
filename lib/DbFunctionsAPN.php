<?php
header('Content-Type: text/html; charset=utf-8');
include_once '../lib/DbFunctionsGCM.php';
 
class DbFunctionsAPN extends DbFunctionsGCM {
 
     function __construct() {
		parent::__construct();
        $this->tokenTable='apn_tokens';
	}
	
	
}
 
?>