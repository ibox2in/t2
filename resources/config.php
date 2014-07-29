<?php
/**
 * Created by PhpStorm.
 * User: Kim
 */


$config = array(
    "db" => array(
        "user_db" => array(
            "dbname" => "t_db",
            "username" => "vk",
            "password" => "devqwa123",
            "host" => ""
        ),
        "order_db" => array(
            "dbname" => "t_db",
            "username" => "vk",
            "password" => "devqwa123",
            "host" => ""
        )
    ),
    "paths" => array(
        "img" => $_SERVER["DOCUMENT_ROOT"]."/public/img",
        "js" => $_SERVER["DOCUMENT_ROOT"]."/public/js",
        "css" => $_SERVER["DOCUMENT_ROOT"]."/public/css",
        "forms" => array(
            "registration" => $_SERVER["DOCUMENT_ROOT"]."/application/registration/",
            "login" => $_SERVER["DOCUMENT_ROOT"]."/application/login/",
            "contractor" => $_SERVER["DOCUMENT_ROOT"]."/application/contractor/",
            "customer" => $_SERVER["DOCUMENT_ROOT"]."/application/registration/"
        ),
        "util" => $_SERVER["DOCUMENT_ROOT"]."/application/util/"
    ),
    "setup" => false
);

function get_config() {
    global $config;
    return $config;
}

define("PATH_CSS", "../public/css/");