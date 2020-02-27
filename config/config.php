<?php

//Note: This file should be included first in every php page.
error_reporting(E_ALL);
ini_set('display_errors', 'On');
define('BASE_PATH', dirname(dirname(__FILE__)));

define('APP_FOLDER', 'simpleadmin');
define('CURRENT_PAGE', basename($_SERVER['REQUEST_URI']));

//define('BASE_URL', 'http://familyback1219.com');
//define('DB_HOST', "localhost");
//define('DB_USER', "root");
//define('DB_PASSWORD', "");
//define('DB_NAME', "familymember");


 define('BASE_URL', 'https://mynotes4u.com');
 define('DB_HOST', "mysql.gip.superb.net");
 define('DB_USER', "u_family_db");
 define('DB_PASSWORD', "Norman@12345");
 define('DB_NAME', "family_db");

// SMTP config
//define('SMTP_HOST', 'smtp.gmail.com');
//define('SMTP_PORT', 465);
//define('SMTP_ENC', 'ssl');
//
//define('SMTP_FROM', 'pandamoney425@gmail.com'); // Should be updated in production mode
//define('SMTP_PASS', 'self1971'); // Should be updated in production mode
//define('SMTP_ENDPOINT', 'http://familyback1219.com/members/member-profile.php' );
//define('SMTP_APPROVED_URL', 'http://familyback1219.com/members/member-profile.php' );

define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 465);
define('SMTP_ENC', 'ssl');

define('SMTP_FROM', 'test4@mynotes4u.com'); // Should be updated in production mode
define('SMTP_PASS', 'Pineturtle1971!'); // Should be updated in production mode
define('SMTP_ENDPOINT', 'https://mynotes4u.com/members/member-profile.php' );
define('SMTP_APPROVED_URL', 'https://mynotes4u.com/members/member-profile.php' );



require_once BASE_PATH . '/lib/MysqliDb/MysqliDb.php';
require_once BASE_PATH . '/helpers/helpers.php';

/**
 * Get instance of DB object
 */
function getDbInstance() {
	return new MysqliDb(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
}