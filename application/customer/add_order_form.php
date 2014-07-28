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

if(add_order($_SESSION["uid"], $_POST["title"], floatval($_POST["price"]))) {
    echo form_json_success(get_order_by_id(mysql_insert_id()));
} else {
    echo JSON_SUCCESS_FALSE;
}