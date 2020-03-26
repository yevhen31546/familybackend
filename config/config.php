<?php

require_once __DIR__.'/../vendor/autoload.php';
if ($_SERVER['SERVER_NAME'] === 'familyback1219.com') {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '.env.local');
    $dotenv->load();
} else {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '.env.production');
    $dotenv->load();
}

//Note: This file should be included first in every php page.
error_reporting(E_ALL);
ini_set('display_errors', 'On');
define('BASE_PATH', dirname(dirname(__FILE__)));

define('APP_FOLDER', 'simpleadmin');
define('CURRENT_PAGE', basename($_SERVER['REQUEST_URI']));
define('LOGO_URL', '/whitelogosm.png');

define('BASE_URL', $_ENV['BASE_URL']);
define('DB_HOST', $_ENV['DB_HOST']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASSWORD', $_ENV['DB_PASSWORD']);
define('DB_NAME', $_ENV['DB_NAME']);
define('SMTP_HOST', $_ENV['SMTP_HOST']);
define('SMTP_PORT', $_ENV['SMTP_PORT']);
define('SMTP_ENC', $_ENV['SMTP_ENC']);
define('SMTP_FROM', $_ENV['SMTP_FROM']);
define('SMTP_PASS', $_ENV['SMTP_PASS']);
define('SMTP_ENDPOINT', $_ENV['SMTP_ENDPOINT']);
define('SMTP_APPROVED_URL', $_ENV['SMTP_APPROVED_URL']);
define('SUPPORT_MAIL', $_ENV['SUPPORT_MAIL']);

// PayPal configuration
define('PAYPAL_ID', $_ENV['PAYPAL_ID']); // change to make Live
define('PAYPAL_SANDBOX', $_ENV['PAYPAL_SANDBOX']); //TRUE or FALSE, need to update for Live
define('PAYPAL_RETURN_URL', $_ENV['PAYPAL_RETURN_URL']);
define('PAYPAL_CANCEL_URL', $_ENV['PAYPAL_CANCEL_URL']);
define('PAYPAL_NOTIFY_URL', $_ENV['PAYPAL_NOTIFY_URL']);
define('PAYPAL_CURRENCY', $_ENV['PAYPAL_CURRENCY']);
define('PAYPAL_URL', $_ENV['PAYPAL_SANDBOX'] ? "https://www.sandbox.paypal.com/cgi-bin/webscr" : "https://www.paypal.com/cgi-bin/webscr");

require_once BASE_PATH . '/lib/MysqliDb/MysqliDb.php';
require_once BASE_PATH . '/helpers/helpers.php';

function getDbInstance() {
	return new MysqliDb(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
}

$bell_count = 0; // number of notification for badge
