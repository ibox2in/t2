<?php
/**
 * Created by PhpStorm.
 * User: Kim
 */

include_once($_SERVER["DOCUMENT_ROOT"]."/resources/config.php");

//function init_db($drop) {
//    if($drop) {
//        drop_order_db(get_connection_order_db());
//        drop_user_db(get_connection_user_db());
//    }
//    init_db_user(get_connection_user_db());
//    init_db_order(get_connection_order_db());
//}
//
//function init_db_user($link) {
//    $db_name = get_config()["db"]["user_db"]["dbname"];
//    if(!mysql_select_db($db_name)) {
//        mysql_query("CREATE DATABASE ".$db_name, $link) or die("Could not select user database: " . mysql_error());
//        echo "User database created successfully<br/>";
//    }
//
//    mysql_select_db($db_name);
//    // ========================== Users table ==========================
//    $query = "CREATE TABLE IF NOT EXISTS t_user (
//        id integer NOT NULL AUTO_INCREMENT,
//        login varchar(40) UNIQUE NOT NULL,
//        password char(32) NOT NULL,
//        account double precision NOT NULL,
//        type integer NOT NULL,
//        sid varchar(64),
//        CONSTRAINT t_user_pkey PRIMARY KEY(id)
//    )";
//
//    $result = mysql_query($query) or die("Creating users table failed: " . mysql_error());
//    if($result) {
//        echo "Users table created successfully<br/>";
//    }
//
//    mysql_close($link);
//}
//
//function init_db_order($link) {
//    $db_name = get_config()["db"]["order_db"]["dbname"];
//    if(!mysql_select_db($db_name)) {
//        mysql_query("CREATE DATABASE ".$db_name, $link) or die("Could not select order database: " . mysql_error());
//        echo "Order database created successfully<br/>";
//    }
//
//    mysql_select_db($db_name);
//    // ========================== Orders table ==========================
//    $query = "CREATE TABLE IF NOT EXISTS t_order (
//        id integer NOT NULL AUTO_INCREMENT,
//        customer_id integer NOT NULL,
//        contractor_id integer,
//        price double precision,
//        profit double precision,
//        status integer NOT NULL,
//        title text,
//        deleted boolean NOT NULL,
//        CONSTRAINT t_order_pkey PRIMARY KEY(id),
//        INDEX(customer_id),
//        INDEX(status),
//        INDEX(deleted)
//    )";
//
//    $result = mysql_query($query) or die("Creating orders table failed: " . mysql_error());
//    if($result) {
//        echo "Orders table created successfully<br/>";
//    }
//
//    mysql_close($link);
//}
//
//function drop_order_db($connection) {
//    mysql_query("DROP DATABASE IF EXISTS ".get_config()["db"]["order_db"]["dbname"], $connection) or die("Could not delete order database: " . mysql_error());
//    echo "Order database deleted successfully<br/>";
//}
//
//function drop_user_db($connection) {
//    mysql_query("DROP DATABASE IF EXISTS ".get_config()["db"]["user_db"]["dbname"], $connection) or die("Could not delete user database: " . mysql_error());
//    echo "User database deleted successfully<br/>";
//}
//
//function get_connection_user_db() {
//    $config = get_config();
//    $connection = mysql_connect($config["db"]["user_db"]["host"], $config["db"]["user_db"]["username"], $config["db"]["user_db"]["password"]) or die("Could not connect user db: " . mysql_error());
//    mysql_select_db($config["db"]["user_db"]["dbname"]);
//    return $connection;
//}
//
//function get_connection_order_db() {
//    $config = get_config();
//    $connection = mysql_connect($config["db"]["order_db"]["host"], $config["db"]["order_db"]["username"], $config["db"]["order_db"]["password"]) or die("Could not connect order db: " . mysql_error());
//    mysql_select_db($config["db"]["order_db"]["dbname"]);
//    return $connection;
//}

function init_db($drop) {
    if($drop) {
        drop_order_db(get_connection_order_db());
        drop_user_db(get_connection_user_db());
    }
    init_db_user(get_connection_user_db());
    init_db_order(get_connection_order_db());
}

function init_db_user($link) {
    $db_name = get_config()["db"]["user_db"]["dbname"];
    if(!mysqli_select_db($link, $db_name)) {
        mysqli_query($link, "CREATE DATABASE ".$db_name) or die("Could not select user database: " . mysqli_error($link));
        echo "User database created successfully<br/>";
    }

    mysqli_select_db($link, $db_name);
    // ========================== Users table ==========================
    $query = "CREATE TABLE IF NOT EXISTS t_user (
        id integer NOT NULL AUTO_INCREMENT,
        login varchar(40) UNIQUE NOT NULL,
        password char(32) NOT NULL,
        account double precision NOT NULL,
        type integer NOT NULL,
        sid varchar(64),
        CONSTRAINT t_user_pkey PRIMARY KEY(id)
    )";

    $result = mysqli_query($link, $query) or die("Creating users table failed: " . mysqli_error($link));
    if($result) {
        echo "Users table created successfully<br/>";
    }

    mysqli_close($link);
}

function init_db_order($link) {
    $db_name = get_config()["db"]["order_db"]["dbname"];
    if(!mysqli_select_db($link, $db_name)) {
        mysqli_query($link, "CREATE DATABASE ".$db_name) or die("Could not select order database: " . mysqli_error($link));
        echo "Order database created successfully<br/>";
    }

    mysqli_select_db($link, $db_name);
    // ========================== Orders table ==========================
    $query = "CREATE TABLE IF NOT EXISTS t_order (
        id integer NOT NULL AUTO_INCREMENT,
        customer_id integer NOT NULL,
        contractor_id integer,
        price double precision,
        profit double precision,
        status integer NOT NULL,
        title text,
        deleted boolean NOT NULL,
        CONSTRAINT t_order_pkey PRIMARY KEY(id),
        INDEX(customer_id),
        INDEX(status),
        INDEX(deleted)
    )";

    $result = mysqli_query($link, $query) or die("Creating orders table failed: " . mysqli_error($link));
    if($result) {
        echo "Orders table created successfully<br/>";
    }

    mysqli_close($link);
}

function drop_order_db($connection) {
    mysqli_query($connection, "DROP DATABASE IF EXISTS ".get_config()["db"]["order_db"]["dbname"]) or die("Could not delete order database: " . mysqli_error($connection));
    echo "Order database deleted successfully<br/>";
}

function drop_user_db($connection) {
    mysqli_query($connection, "DROP DATABASE IF EXISTS ".get_config()["db"]["user_db"]["dbname"]) or die("Could not delete user database: " . mysqli_error($connection));
    echo "User database deleted successfully<br/>";
}

function get_connection_user_db() {
    $config = get_config();
    $connection = mysqli_connect($config["db"]["user_db"]["host"], $config["db"]["user_db"]["username"], $config["db"]["user_db"]["password"]) or die("Could not connect user db: ");
    mysqli_select_db($connection, $config["db"]["user_db"]["dbname"]);
    return $connection;
}

function get_connection_order_db() {
    $config = get_config();
    $connection = mysqli_connect($config["db"]["order_db"]["host"], $config["db"]["order_db"]["username"], $config["db"]["order_db"]["password"]) or die("Could not connect order db: ");
    mysqli_select_db($connection, $config["db"]["order_db"]["dbname"]);
    return $connection;
}