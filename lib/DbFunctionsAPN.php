<?php
header('Content-Type: text/html; charset=utf-8');
include_once '../lib/DbFunctionsGCM.php';

/**
 * Extends the DbFunctionsGCM. The constructor is overridden to change
 * the token table name reference.
 * @author matjaz
 *
 */
class DbFunctionsAPN extends DbFunctionsGCM {
 
     function __construct() {
		parent::__construct();
        $this->tokenTable='apn_tokens';
	}
	
	
}
 
?>