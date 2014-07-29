<?php
/**
 * Created by PhpStorm.
 * User: Kim
 */

require_once("db_util.php");

define("COMMISSION_FEE", 0.05); // Комиссия системы в %

define("STATUS_NEW", 0);
define("STATUS_PROGRESS", 1);
define("STATUS_COMPLETED", 2);

//function add_order($customer_id, $title, $price) {
//    $link = get_connection_order_db();
//    $query = "INSERT INTO t_order (id, customer_id, contractor_id, price, profit, status, title, deleted) VALUES (NULL, $customer_id, NULL, $price, 0, ".STATUS_NEW.", '$title', false)";
//    $result = mysql_query($query, $link) or die('adding order failed: ' . mysql_error());
//    return $result;
//}
//
//function update_order_deleted($id) {
//    $link = get_connection_order_db();
//    $query = "UPDATE t_order SET deleted = true WHERE id = $id";
//    $result = mysql_query($query, $link) or die('select user failed: ' . mysql_error());
//}
//
//function update_order_status_and_contractor_id_and_profit($id, $status, $contractor_id, $profit, $link) {
//    if($link == NULL) {
//        $link = get_connection_order_db();
//    }
//    $query = "UPDATE t_order SET status = $status, contractor_id = $contractor_id, profit = $profit WHERE id = $id";
//    return mysql_query($query, $link);
//}
//
//function get_order_by_id($id) {
//    $link = get_connection_order_db();
//    $query = "SELECT * FROM t_order WHERE id = '$id'";
//    $result = mysql_query($query, $link) or die('select user failed: ' . mysql_error());
//    return mysql_fetch_array($result);
//}
//
//function get_order_by_id_update($id, $link) {
//    $query = "SELECT * FROM t_order WHERE id = $id FOR UPDATE";
//    $result = mysql_query($query, $link) or die('select user failed: ' . mysql_error());
//    return mysql_fetch_array($result);
//}
//
//function get_orders_by_customer_id($id) {
//    $link = get_connection_order_db();
//    $query = "SELECT * FROM t_order WHERE customer_id = '$id' AND deleted = false ORDER BY id DESC";
//    $result = mysql_query($query, $link) or die('select user failed: ' . mysql_error());
//    $orders = array();
//    while ($orders[] = mysql_fetch_array($result));
//    return $orders;
//}
//
//define("PAGE_SIZE", 20);
//
//function get_orders_by_max_id_and_limit($max_id, $limit) {
//    $link = get_connection_order_db();
//    $query = "SELECT * FROM t_order WHERE id < $max_id AND deleted = false AND status <> ".STATUS_COMPLETED." ORDER BY id DESC LIMIT ".$limit;
//    $result = mysql_query($query, $link) or die('select user failed: ' . mysql_error());
//    $orders = array();
//    while ($orders[] = mysql_fetch_array($result));
//    return $orders;
//}
//
//function get_orders_by_limit($limit) {
//    $link = get_connection_order_db();
//    $query = "SELECT * FROM t_order WHERE deleted = false AND status <> ".STATUS_COMPLETED." ORDER BY id DESC LIMIT ".$limit;
//    $result = mysql_query($query, $link) or die('select user failed: ' . mysql_error());
//    $orders = array();
//    while ($orders[] = mysql_fetch_array($result));
//    return $orders;
//}
//
//function get_orders_by_customer_id_and_max_id_and_limit($customer_id, $max_id, $limit) {
//    $link = get_connection_order_db();
//    $query = "SELECT * FROM t_order WHERE customer_id = $customer_id AND id < $max_id AND deleted = false ORDER BY id DESC LIMIT ".$limit;
//    $result = mysql_query($query, $link) or die('select user failed: ' . mysql_error());
//    $orders = array();
//    while ($orders[] = mysql_fetch_array($result));
//    return $orders;
//}
//
//function get_orders_by_customer_id_and_limit($customer_id, $limit) {
//    $link = get_connection_order_db();
//    $query = "SELECT * FROM t_order WHERE customer_id = $customer_id AND deleted = false ORDER BY id DESC LIMIT ".$limit;
//    $result = mysql_query($query, $link) or die('select user failed: ' . mysql_error());
//    $orders = array();
//    while ($orders[] = mysql_fetch_array($result));
//    return $orders;
//}

function add_order($customer_id, $title, $price, $link) {
    $query = mysqli_prepare($link,"INSERT INTO t_order (id, customer_id, contractor_id, price, profit, status, title, deleted) VALUES (NULL, ?, NULL, ?, 0, ?, ?, false)");
    $status = STATUS_NEW;
    mysqli_stmt_bind_param($query, 'idis', $customer_id, $price, $status, $title);
    $result = mysqli_stmt_execute($query) or die('add order failed: ' . mysqli_error($link));
    return $result;
}

function update_order_deleted($id) {
    $link = get_connection_order_db();
    $query = "UPDATE t_order SET deleted = true WHERE id = $id";
    $result = mysqli_query($link, $query) or die('select user failed: ' . mysqli_error($link));
}

function update_order_status_and_contractor_id_and_profit($id, $status, $contractor_id, $profit, $link) {
    if($link == NULL) {
        $link = get_connection_order_db();
    }
    $query = "UPDATE t_order SET status = $status, contractor_id = $contractor_id, profit = $profit WHERE id = $id";
    return mysqli_query($link, $query);
}

function get_order_by_id($id) {
    $link = get_connection_order_db();
    $query = "SELECT * FROM t_order WHERE id = $id";
    $result = mysqli_query($link, $query) or die('select user failed: ' . mysqli_error($link));
    return mysqli_fetch_array($result);
}

function get_order_by_id_update($id, $link) {
    $query = "SELECT * FROM t_order WHERE id = $id FOR UPDATE";
    $result = mysqli_query($link, $query) or die('select user failed: ' . mysqli_error($link));
    return mysqli_fetch_array($result);
}

define("PAGE_SIZE", 20);

function get_orders_by_max_id_and_limit($max_id, $limit) {
    $link = get_connection_order_db();
    $query = "SELECT * FROM t_order WHERE id < $max_id AND deleted = false AND status <> ".STATUS_COMPLETED." ORDER BY id DESC LIMIT ".$limit;
    $result = mysqli_query($link, $query) or die('select user failed: ' . mysqli_error($link));
    $orders = array();
    while ($orders[] = mysqli_fetch_array($result));
    return $orders;
}

function get_orders_by_limit($limit) {
    $link = get_connection_order_db();
    $query = "SELECT * FROM t_order WHERE deleted = false AND status <> ".STATUS_COMPLETED." ORDER BY id DESC LIMIT ".$limit;
    $result = mysqli_query($link, $query) or die('select user failed: ' . mysqli_error($link));
    $orders = array();
    while ($orders[] = mysqli_fetch_array($result));
    return $orders;
}

function get_orders_by_customer_id_and_max_id_and_limit($customer_id, $max_id, $limit) {
    $link = get_connection_order_db();
    $query = "SELECT * FROM t_order WHERE customer_id = $customer_id AND id < $max_id AND deleted = false ORDER BY id DESC LIMIT ".$limit;
    $result = mysqli_query($link, $query) or die('select user failed: ' . mysqli_error($link));
    $orders = array();
    while ($orders[] = mysqli_fetch_array($result));
    return $orders;
}

function get_orders_by_customer_id_and_limit($customer_id, $limit) {
    $link = get_connection_order_db();
    $query = "SELECT * FROM t_order WHERE customer_id = $customer_id AND deleted = false ORDER BY id DESC LIMIT ".$limit;
    $result = mysqli_query($link, $query) or die('select user failed: ' . mysqli_error($link));
    $orders = array();
    while ($orders[] = mysqli_fetch_array($result));
    return $orders;
}