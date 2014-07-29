<?php
/**
 * Created by PhpStorm.
 * User: Kim
 */

include($_SERVER["DOCUMENT_ROOT"]."/resources/config.php");
include(get_config()["paths"]["util"]."db_util.php");

if(!get_config()["setup"]) {
    init_db(true);
} else {
    echo ";(";
}
