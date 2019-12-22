<?php
//Note: This file should be included first in every php page.
error_reporting(E_ALL);
ini_set('display_errors', 'On');
define('BASE_PATH', dirname(__FILE__));

// define('BASE_URL', 'http://dev.myfamilybackccc.com:8081/admin/');
// define('DB_HOST', "localhost");
// define('DB_USER', "root");
// define('DB_PASSWORD', "");
// define('DB_NAME', "familymember");

define('BASE_URL', 'https://mynotes4u.com/admin/');
define('DB_HOST', "mysql.gip.superb.net");
define('DB_USER', "u_family_db");
define('DB_PASSWORD', "Norman@12345");
define('DB_NAME', "family_db");

define('APP_FOLDER', 'simpleadmin');
define('CURRENT_PAGE', basename($_SERVER['REQUEST_URI']));

require_once BASE_PATH . '/apps/users/lib/MysqliDb/MysqliDb.php';
require_once BASE_PATH . '/apps/users/helpers/helpers.php';

/*
|--------------------------------------------------------------------------
| DATABASE CONFIGURATION
|--------------------------------------------------------------------------
 */

/**
 * Get instance of DB object
 */
function getDbInstance() {
    return new MysqliDb(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
}
?>