<?php
/**
 * Created by PhpStorm.
 * User: Kim
 */

require_once("../util/session_util.php");

init_session();
logout();
die_with_redirect();