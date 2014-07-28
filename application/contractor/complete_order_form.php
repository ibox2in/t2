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
mysql_query("START TRANSACTION", $order_connection);
$order = get_order_by_id_update(intval($_POST["order_id"]), $order_connection);
if($order["deleted"]) {
    mysql_query("COMMIT", $order_connection);
    header('HTTP/1.1 410 Gone', true, 410);
    die();
}
if($order["profit"] != 0) {
    mysql_query("COMMIT", $order_connection);
    header('HTTP/1.1 403 Forbidden', true, 403);
    die();
}
$commission = $order["price"] * COMMISSION_FEE;

$user = get_user_by_id($_SESSION["uid"]);
$account = $user["account"] + $order["price"] - $order["price"] * COMMISSION_FEE;

$user_connection = get_connection_user_db();

mysql_query("START TRANSACTION", $user_connection);
if (update_order_status_and_contractor_id_and_profit($order["id"], STATUS_COMPLETED, $_SESSION["uid"], $commission, $order_connection) && update_user_account($user["id"], $account, $user_connection)) {
    mysql_query("COMMIT", $order_connection);
    mysql_query("COMMIT", $user_connection);
    $_SESSION["account"] = $account;
} else {
    mysql_query("ROLLBACK", $order_connection);
    mysql_query("ROLLBACK", $user_connection);
}

echo form_json_success(array("account" => $account));