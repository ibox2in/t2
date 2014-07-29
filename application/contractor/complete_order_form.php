<?php
/**
 * Created by PhpStorm.
 * User: Kim
 */

require_once("../util/db_util.php");
require_once("../util/session_util.php");
require_once("../util/json_util.php");
require_once("../util/order_util.php");

init_session();
if(!check_auth(false)) {
    header('HTTP/1.1 401 Unauthorized', true, 401);
    die();
}

$order_connection = get_connection_order_db();
mysqli_query($order_connection, "START TRANSACTION");
$order = get_order_by_id_update(intval($_POST["order_id"]), $order_connection);
if($order["deleted"]) {
    mysqli_query($order_connection, "COMMIT");
    header('HTTP/1.1 410 Gone', true, 410);
    die();
}
if($order["profit"] != 0) {
    mysqli_query($order_connection, "COMMIT");
    header('HTTP/1.1 403 Forbidden', true, 403);
    die();
}
$commission = $order["price"] * COMMISSION_FEE;

$user = get_user_by_id($_SESSION["uid"]);
if($user["type"] != TYPE_CONTRACTOR) {
    mysqli_query($order_connection, "COMMIT");
    die();
}

$account = $user["account"] + $order["price"] - $order["price"] * COMMISSION_FEE;

$user_connection = get_connection_user_db();

mysqli_query($user_connection, "START TRANSACTION");
if (update_order_status_and_contractor_id_and_profit($order["id"], STATUS_COMPLETED, $_SESSION["uid"], $commission, $order_connection) && update_user_account($user["id"], $account, $user_connection)) {
    mysqli_query($order_connection, "COMMIT");
    mysqli_query($user_connection, "COMMIT");
    $_SESSION["account"] = $account;
} else {
    mysqli_query($order_connection, "ROLLBACK");
    mysqli_query($user_connection, "ROLLBACK");
}

echo form_json_success(array("account" => number_format($account, 2)));