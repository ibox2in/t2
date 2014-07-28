<?php
/**
 * Created by PhpStorm.
 * User: Kim
 */

require_once("../util/session_util.php");
require_once("../util/order_util.php");
require_once("../util/user_util.php");

init_session();
if(!check_auth(true)) {
    header('HTTP/1.1 401 Unauthorized', true, 401);
    die();
}

$orders = array();

if(intval($_GET["max_id"]) != 0) {
    $orders = get_orders_by_customer_id_and_max_id_and_limit($_SESSION["uid"], intval($_GET["max_id"]), PAGE_SIZE);
} else {
    $orders = get_orders_by_customer_id_and_limit($_SESSION["uid"], PAGE_SIZE);
}

$ids = array();

foreach($orders as $key => &$val) {
    $ids[] = $val["contractor_id"];
}
$ids = array_filter($ids);
$ids[] = -1;

$users = get_users_by_ids($ids);
$map = array();
foreach($users as $key => &$val) {
    $map[$val["id"]] = $val["login"];
}

foreach($orders as $key => &$val) {
    $val["contractor_id"] = $map[$val["contractor_id"]];
}
echo json_encode($orders);