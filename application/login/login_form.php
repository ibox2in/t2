<?php
/**
 * Created by PhpStorm.
 * User: Kim
 */

require_once("../util/session_util.php");

init_session();
if(login($_POST["login"], md5($_POST["password"]))) {
    echo "{'success':true}";
} else {
    echo "{'success':false}";
}