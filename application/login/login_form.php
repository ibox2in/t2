<?php
/**
 * Created by PhpStorm.
 * User: Kim
 */

require_once("../util/session_util.php");
require_once("../util/json_util.php");

init_session();
if(login($_POST["login"], md5($_POST["password"]))) {
    echo JSON_SUCCESS_TRUE;
} else {
    echo JSON_SUCCESS_FALSE;
}