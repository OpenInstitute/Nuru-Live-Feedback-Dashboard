<?php
require_once('cls.base.php');

define('DB_HOST', 		'localhost');
define('DB_CHARSET', 	 'utf8');

if($_SERVER['HTTP_HOST'] == "localhost" or $_SERVER['HTTP_HOST'] == "10.0.2.2"){
	define('DB_NAME', 		'db_oi_nuru');	
	define('DB_USER', 		'XXXXXXXX');
	define('DB_PASSWORD', 	'XXXXXXXX');
	
} else {
	
	define('DB_NAME', 		'XXXXXXXX');	
	define('DB_USER', 	 	'XXXXXXXXXX');
	define('DB_PASSWORD', 	'XXXXXXXXXXX');
}

$pdb_prefix = 'uawb_';

?>
