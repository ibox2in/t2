<?php
/**
 * Created by PhpStorm.
 * User: Kim
 */

require_once("../util/session_util.php");
require_once("../util/order_util.php");
require_once("../util/json_util.php");

init_session();
if(!check_auth(true)) {
    header('HTTP/1.1 401 Unauthorized', true, 401);
    die();
}

$_POST = array_filter($_POST);
if(!isset($_POST["title"]) || !isset($_POST["price"])) {
    header('HTTP/1.1 406 Not Acceptable', true, 406);
    die();
}

$title = $_POST["title"];
if (!preg_match('/^[\pL][\pL-_. 0-9\\n]*$/u', $_POST["title"]) || !is_numeric($_POST["price"])) {
    header('HTTP/1.1 406 Not Acceptable', true, 406);
    die();
}

$connection = get_connection_order_db();
if(add_order($_SESSION["uid"], nl2br($title), round(floatval($_POST["price"]), 2), $connection)) {
    $order = get_order_by_id(mysqli_insert_id($connection));
    $order["price"] = number_format($order["price"], 2);
    echo form_json_success($order);
} else {
    echo JSON_SUCCESS_FALSE;
}