<?php

session_start();

use function DBFunctions\DBConnect;
use function DBFunctions\DBFetch;
use function DBFunctions\DBGetError;
use function DBFunctions\DBNumRows;
use function DBFunctions\DBQuery;

include_once dirname(__FILE__) . "/../src/lib_database.php";

$host     = "localhost";
$user     = "root";
$password = "root";
$database = "data_base";

// We make the connection with the database
$return = DBConnect($host, $user, $password, $database);

// We validate that a connection error has not been generated.
if(DBGetError() != false) {

    // We store in the return the errors generated during the connection
    $return = DBGetError();
}

// Make a query to a table in the database.
$sql = "SELECT * FROM table";
$result = DBQuery($sql);

// Validate that there is no error in the query made
if(DBGetError() != false) {

    // We print the possible error (s) generated and cut the execution.
    var_dump(DBGetError());
    exit();
}

// We print the number of rows found in the query.
var_dump(DBNumRows($result)); print "<br>";

// We go through the rows obtained.
while ($info = DBFetch($result)) {

    // We print the information of each row obtained.
    var_dump($info);
    print "<br>";
}
