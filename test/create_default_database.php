<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');

use function DBFunctions\DBConnect;
use function DBFunctions\DBGetError;

include_once dirname(__FILE__) . "/config.php";
include_once dirname(__FILE__) . "/../src/lib_database.php";

global $CONFIG;
$database_data = $CONFIG['DB'];
DBConnect($database_data['host'], $database_data['user'], $database_data['pass'], $database_data['database'], $database_data['port'], $database_data['socket']);
if(DBGetError() != false) {

    var_dump(DBGetError());
}



