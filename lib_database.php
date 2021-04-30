<?php

namespace DBFunctions;

use mysqli;
use mysqli_result;

/**
 *
 */
#region Validation

$DBError = array();

/**
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
 *
 */
#region Connection

/**
 * @return bool|mysqli
 */
function DBGetConnection(): bool|mysqli
{
    if(isset($_SESSION['link']) && $_SESSION['link'] != "") {

        return $_SESSION['link'];
    } else {

        DBSetError("There is no connection to the database.");
        return false;
    }
}

/**
 * @param mysqli $link
 */
function DBSetConnection(mysqli $link): void
{
    $_SESSION['link'] = $link;
}

/**
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
    if(DBValidateSession() == true) {

        $link = mysqli_connect($host, $user, $password, $database, $port, $socket);

        DBSetConnection($link);
        return $link;
    } else {

        return false;
    }
}
#endregion Connection

/**
 *
 */
#region Query

/**
 * @param string $query
 * @param mysqli|null $link
 * @return mixed
 */
function DBQuery(string $query, ?mysqli $link = null): mixed
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
 * @param mysqli|mysqli_result $result
 * @return int|string
 */
function DBLastInsert(mysqli|mysqli_result $result): int|string
{
    return mysqli_insert_id($result);
}

/**
 * @param string $text
 * @return string
 */
function DBEscapeString(string $text): string
{
    $link = DBGetConnection();
    return mysqli_real_escape_string($link, $text);
}
#endregion Query

#region Result

/**
 * @param mysqli_result $result
 * @return array|null
 */
function DBFetch(mysqli_result $result): ?array
{
    return mysqli_fetch_assoc($result);
}

/**
 * @param mysqli_result $result
 * @return int
 */
function DBNumRows(mysqli_result $result): int
{
    return mysqli_num_rows($result);
}
#endregion Result
