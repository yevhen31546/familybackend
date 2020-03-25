<?php

//Note: This file should be included first in every php page.
error_reporting(E_ALL);
ini_set('display_errors', 'On');
define('BASE_PATH', dirname(dirname(__FILE__)));

define('APP_FOLDER', 'simpleadmin');
define('CURRENT_PAGE', basename($_SERVER['REQUEST_URI']));
define('LOGO_URL', '/whitelogosm.png');
// Local

//define('BASE_URL', 'http://familyback1219.com');
//define('DB_HOST', "localhost");
//define('DB_USER', "root");
//define('DB_PASSWORD', "");
//define('DB_NAME', "familymember");
//define('SMTP_HOST', 'smtp.gmail.com');
//define('SMTP_PORT', 465);
//define('SMTP_ENC', 'ssl');
//define('SMTP_FROM', 'pandamoney425@gmail.com'); // Should be updated in production mode
//define('SMTP_PASS', 'self1971'); // Should be updated in production mode
//define('SMTP_ENDPOINT', 'http://familyback1219.com/members/members.php' );
//define('SMTP_APPROVED_URL', 'http://familyback1219.com/members/member-profile.php' );
//
//// PayPal configuration
//define('PAYPAL_ID', 'sb-xiooh1228600@personal.example.com'); // change to make Live
//define('PAYPAL_SANDBOX', TRUE); //TRUE or FALSE, need to update for Live
//define('PAYPAL_RETURN_URL', 'http://familyback1219.com/register.php');
//define('PAYPAL_CANCEL_URL', 'http://familyback1219.com');
//define('PAYPAL_NOTIFY_URL', 'http://familyback1219.com/paypal_ipn.php');
//define('PAYPAL_CURRENCY', 'USD');
//define('PAYPAL_URL', (PAYPAL_SANDBOX == true)?"https://www.sandbox.paypal.com/cgi-bin/webscr":"https://www.paypal.com/cgi-bin/webscr");


// Server

define('BASE_URL', 'https://mynotes4u.com');
define('DB_HOST', "mysql.gip.superb.net");
define('DB_USER', "u_family_db");
define('DB_PASSWORD', "Norman@12345");
define('DB_NAME', "family_db");
define('SMTP_HOST', 'smtp.superb.net');
define('SMTP_PORT', 587);
define('SMTP_ENC', 'tls');
define('SMTP_FROM', 'help@mynotes4u.com'); // Should be updated in production mode
define('SMTP_PASS', 'Axugust151959'); // Should be updated in production mode August151959
define('SMTP_ENDPOINT', 'https://mynotes4u.com/members/members.php' );
define('SMTP_APPROVED_URL', 'https://mynotes4u.com/members/member-profile.php' );

// PayPal configuration
define('PAYPAL_ID', 'sb-xiooh1228600@personal.example.com'); // change to make Live
define('PAYPAL_SANDBOX', FALSE); //TRUE or FALSE, need to update for Live
define('PAYPAL_RETURN_URL', 'https://mynotes4u.com/register.php');
define('PAYPAL_CANCEL_URL', 'https://mynotes4u.com');
define('PAYPAL_NOTIFY_URL', 'https://mynotes4u.com/paypal_ipn.php');
define('PAYPAL_CURRENCY', 'USD');
define('PAYPAL_URL', (PAYPAL_SANDBOX == true)?"https://www.sandbox.paypal.com/cgi-bin/webscr":"https://www.paypal.com/cgi-bin/webscr");

require_once BASE_PATH . '/lib/MysqliDb/MysqliDb.php';
require_once BASE_PATH . '/helpers/helpers.php';

/**
 * Get instance of DB object
 */
function getDbInstance() {
	return new MysqliDb(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
}

$bell_count = 0; // number of notification for badge
