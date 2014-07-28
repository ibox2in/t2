<?php
/**
 * Created by PhpStorm.
 * User: Kim
 */
/*
    The important thing to realize is that the config file should be included in every
    page of your project, or at least any page you want access to these settings.
    This allows you to confidently use these settings throughout a project because
    if something changes such as your database credentials, or a path to a specific resource,
    you'll only need to update it here.
*/

$config = array(
    "db" => array(
        "user_db" => array(
            "dbname" => "t_db",
            "username" => "vk",
            "password" => "devqwa123",
            "host" => "mysql-env-8899864.jelastic.regruhosting.ru"
        ),
        "order_db" => array(
            "dbname" => "t_db",
            "username" => "vk",
            "password" => "devqwa123",
            "host" => "mysql-env-8899864.jelastic.regruhosting.ru"
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

/*
    I will usually place the following in a bootstrap file or some type of environment
    setup file (code that is run at the start of every page request), but they work
    just as well in your config file if it's in php (some alternatives to php are xml or ini files).
*/

/*
    Creating constants for heavily used paths makes things a lot easier.
    ex. require_once(LIBRARY_PATH . "Paginator.php")
*/
//defined("LIBRARY_PATH")
//or define("LIBRARY_PATH", realpath(dirname(__FILE__) . '/library'));
//
//defined("TEMPLATES_PATH")
//or define("TEMPLATES_PATH", realpath(dirname(__FILE__) . '/templates'));

/*
    Error reporting.
*/
//ini_set("error_reporting", "true");
//error_reporting(E_ALL|E_STRCT);