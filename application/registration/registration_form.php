<?php
/**
 * Created by PhpStorm.
 * User: Kim
 */

require_once("../util/session_util.php");
require_once("../util/user_util.php");
require_once("../util/json_util.php");

init_session();

$_POST = array_filter($_POST);
if(!isset($_POST["login"]) || !isset($_POST["password"])) {
    header('HTTP/1.1 406 Not Acceptable', true, 406);
    die();
}

if (!preg_match('/^[\pL][\pL-_. 0-9]{5,31}$/u', $_POST["login"])) {
    header('HTTP/1.1 406 Not Acceptable', true, 406);
    die();
}

if(add_user($_POST["login"], md5($_POST["password"]), $_POST["type"] == TYPE_CUSTOMER ? TYPE_CUSTOMER : TYPE_CONTRACTOR) && login($_POST["login"], md5($_POST["password"]))) {
    echo JSON_SUCCESS_TRUE;
} else {
    echo JSON_SUCCESS_FALSE;
}