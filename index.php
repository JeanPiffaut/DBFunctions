<?php

session_start();

use function DBFunctions\DBConnect;
use function DBFunctions\DBGetError;

include_once dirname(__FILE__) . "/lib_database.php";

$host     = "localhost";
$user     = "root";
$password = "root";
$database = "swiff_base";
$port     = 3307;
$socket   = "/Applications/MAMP/tmp/mysql/mysql.sock";

$return = DBConnect($host, $user, $password, $database, $port, $socket);
if(DBGetError() != false) {

    $return = DBGetError();
}

var_dump($return);
