<?php

namespace DBFunctions;

use mysqli;
use mysqli_result;

/**
 * The functions of this region focus on validating the processes and in case of error, grouping them in their own
 * review method.
 * The function to perform validations can be modified and in fact it is expected that something will be done to meet
 * the needs of the projects in this validation and generate more precise error detection.
 */
#region Validation

/**
 * This variable is used globally in the library in order to store the errors that have been generated during the
 * query process.
 */
$DBError = array();

/**
 * It performs a validation that verifies that the session that connects to the database exists.
 * It can be modified to validate any field that is used during the project in order not to make the process longer and
 * that this applies to the entire library.
 * @return bool
 */
function DBValidateSession(): bool
{
    if(isset($_SESSION)) {

        return true;
    } else {

        DBSetError("The session has not been started");
        return false;
    }
}

/**
 * It defines a new error that has been generated during the process and stores it in the variable in charge.
 * @param $message
 * @param string $code
 */
function DBSetError($message, $code = ""): void
{
    global $DBError;
    $error = array();

    if(isset($code) == true && empty($code) == false) {

        $error['code'] = $code;
    }

    $error['message'] = $message;

    $DBError[] = $error;
}

/**
 * It returns false in case an error has not been generated in the process and the array with all the errors in case
 * one is generated during the process.
 * @return bool|array
 */
function DBGetError(): bool|array
{
    global $DBError;
    if(empty($DBError) == true) {

        return false;
    } else {

        return $DBError;
    }
}
#endregion Validation

/**
 * The functions of this region focus on being able to make a connection and maintenance of this during the project
 * process without interfering with other processes that could be used during the project.
 */
#region Connection

/**
 * Returns the connection link.
 * In case the session does not exist or the connection is not created, it will return false and add an error to the
 * list of these.
 * @return bool|mysqli
 */
function DBGetConnection(): bool|mysqli
{
    if(DBValidateSession() === true) {

        if(isset($_SESSION['link']) && $_SESSION['link'] != "") {

            return $_SESSION['link'];
        } else {

            DBSetError("There is no connection to the database.");
            return false;
        }
    } else {

        return false;
    }
}

/**
 * Store the connection variable in a superglobal variable so you don't have to connect over and over to the database.
 * Returns true or false depending on whether the session validation completes it.
 * @param mysqli $link
 * @return bool
 */
function DBSetConnection(mysqli $link): bool
{
    if(DBValidateSession() === true) {

        $_SESSION['link'] = $link;
        return true;
    } else {

        return false;
    }
}

/**
 * Make the connection with the database and store the link in a super global variable.
 * If the session validation passes, it creates the new connection, otherwise, it returns false and stores what
 * happened in the error variable.
 * In case if the validation passes, but it finds a connection error, it will add it to the list of existing errors and
 * return a false.
 * @param string      $host
 * @param string      $user
 * @param string      $password
 * @param string      $database
 * @param int|null    $port
 * @param string|null $socket
 * @return bool|mysqli
 */
function DBConnect(string $host, string $user, string $password, string $database, ?int $port = null,
                   ?string $socket = null): bool|mysqli
{
    if(DBValidateSession() === true) {

        $link = mysqli_connect($host, $user, $password, $database, $port, $socket);
        if($link === false || $link === null) {

            DBSetError(mysqli_connect_error(), mysqli_connect_errno());
            return false;
        } else {

            DBSetConnection($link);
            return $link;
        }
    } else {

        return false;
    }
}

/**
 * It closes the connection that is sent by parameter and if it is the same one that is currently active, it removes
 * it from the session variable.
 * @param mysqli $link
 */
function DBDisconnect(mysqli $link): void
{
    if($_SESSION['link'] == $link) {

        unset($_SESSION['link']);
    }

    mysqli_close($link);
}

function DBChangeDataBase($database, $link): bool
{

    return true;
}
#endregion Connection

/**
 * The functions of this region are in charge of executing the query and processes that have to do with it, such as
 * obtaining the last id and escaping the strings so that they do not contain values that can generate problems in the
 * database.
 */
#region Query

/**
 * It makes a query to the database that the system is currently connected to.
 * The query that will be carried out will be sent by parameter as a string
 * @param string $query
 * @param mysqli|null $link
 * @return mixed
 */
function DBQuery(string $query, mysqli|null $link = null): mixed
{
    if($link === null) {

        $link = DBGetConnection();
    }

    $result = mysqli_query($link, $query);
    if($result === false || $result === null) {

        DBSetError(mysqli_error($link), mysqli_errno($link));
    }

    return $result;
}

/**
 * Return the last id inserted in the database in which it is connected.
 * @param mysqli|null $link
 * @return int|string
 */
function DBLastInsert(mysqli|null $link = null): int|string
{
    if($link === null) {

        $link = DBGetConnection();
    }

    return mysqli_insert_id($link);
}

/**
 * Returns the string with the escaped characters according to to avoid errors in the database.
 * @param string $text
 * @return string
 */
function DBEscapeString(string $text, mysqli|null $link = null): string
{
    if($link === null) {

        $link = DBGetConnection();
    }

    return mysqli_real_escape_string($link, $text);
}
#endregion Query

/**
 * The functions of this region are responsible for carrying out necessary actions and complying with the standard of
 * the library.
 */
#region Result

/**
 * Retrieves the information of the result object to be used as an array.
 * @param mysqli_result $result
 * @return array|null
 */
function DBFetch(mysqli_result $result): ?array
{
    return mysqli_fetch_assoc($result);
}

/**
 * Returns the number of rows that were obtained in the query result.
 * @param mysqli_result $result
 * @return int
 */
function DBNumRows(mysqli_result $result): int
{
    return mysqli_num_rows($result);
}

/**
 * Set the result to a specific column.
 * If it is not indicated which column it is going to point to, it will be set to column 0 or the first column.
 * @param mysqli_result $result
 * @param int|string $offset
 * @return bool
 */
function DBDataSeek(mysqli_result &$result, int|string $offset = 0): bool
{
    return mysqli_data_seek($result, $offset);
}
#endregion Result
