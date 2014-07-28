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

$order = get_order_by_id(intval($_POST["order_id"]));
if($order["customer_id"] != $_SESSION["uid"]) {
    header('HTTP/1.1 403 Forbidden', true, 403);
    die();
}
update_order_deleted($order["id"]);
echo JSON_SUCCESS_TRUE;