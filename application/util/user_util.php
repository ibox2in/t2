<?php
/**
 * Created by PhpStorm.
 * User: Kim
 */

require_once("db_util.php");

//function add_user($login, $password, $type) {
//    $link = get_connection_user_db();
//    $query = "INSERT INTO t_user (id, login, password, account, type, sid) VALUES (NULL, '$login', '$password', 0, $type, NULL)";
//    $result = mysql_query($query, $link) or die('adding user failed: ' . mysql_error());
//    return $result;
//}
//
//function user_valid($login, $password) {
//    $user = get_user($login, $password);
//    return !empty($user);
//}
//
//function get_user_by_login_password($login, $password) {
//    $link = get_connection_user_db();
//    $query = "SELECT * FROM t_user WHERE login = '$login' AND password = '$password' LIMIT 1";
//    $result = mysql_query($query, $link) or die('select user failed: ' . mysql_error());
//    return mysql_fetch_array($result);
//}
//
//function get_user_by_id($id) {
//    $link = get_connection_user_db();
//    $query = "SELECT * FROM t_user WHERE id = $id LIMIT 1";
//    $result = mysql_query($query, $link) or die('select user failed: ' . mysql_error());
//    return mysql_fetch_array($result);
//}
//
//function get_users_by_ids($ids) {
//    $link = get_connection_user_db();
//    $ids = implode(',', $ids);
//    $query = "SELECT * FROM t_user WHERE id IN ($ids)";
//    $result = mysql_query($query, $link) or die('select user failed: ' . mysql_error());
//    $users = array();
//    while ($users[] = mysql_fetch_array($result));
//    return $users;
//}
//
//function update_user_sid($id, $sid) {
//    $link = get_connection_user_db();
//    $query = "UPDATE t_user SET sid = '$sid' WHERE id = $id";
//    return mysql_query($query, $link);
//}
//
//function update_user_account($id, $account, $link) {
//    if($link == NULL) {
//        $link = get_connection_user_db();
//    }
//    $query = "UPDATE t_user SET account = $account WHERE id = $id";
//    return mysql_query($query, $link);
//}
//
//define("TYPE_CUSTOMER", 0);
//define("TYPE_CONTRACTOR", 1);
//
//function user_customer($id) {
//    $user = get_user_by_id($id);
//    if(!empty($user) && $user["type"] == TYPE_CUSTOMER) {
//        return true;
//    }
//    return false;
//}
//
//function user_contractor($id) {
//    $user = get_user_by_id($id);
//    if(!empty($user) && $user["type"] == TYPE_CONTRACTOR) {
//        return true;
//    }
//    return false;
//}

function add_user($login, $password, $type) {
    $link = get_connection_user_db();
    $query = mysqli_prepare($link,"INSERT INTO t_user (id, login, password, account, type, sid) VALUES (NULL, ?, ?, 0, ?, NULL)");
    mysqli_stmt_bind_param($query, 'sss', $login, $password, $type);
    $result = mysqli_stmt_execute($query) or die('add user failed: ' . mysqli_error($link));
    return $result;
}

function get_user_by_login_password($login, $password) {
    $link = get_connection_user_db();
    $query = mysqli_prepare($link,"SELECT * FROM t_user WHERE login = ? AND password = ?");
    mysqli_stmt_bind_param($query, 'ss', $login, $password);
    mysqli_stmt_execute($query);
    $result = mysqli_stmt_get_result($query);
    return mysqli_fetch_array($result);
}

function get_user_by_id($id) {
    $link = get_connection_user_db();
    $query = mysqli_prepare($link,"SELECT * FROM t_user WHERE id = ?");
    mysqli_stmt_bind_param($query, 'i', $id);
    mysqli_stmt_execute($query);
    $result = mysqli_stmt_get_result($query);
    return mysqli_fetch_array($result);
}

function get_users_by_ids($ids) {
    $link = get_connection_user_db();
    $ids = implode(',', $ids);
    $query = "SELECT * FROM t_user WHERE id IN ($ids)";
    $result = mysqli_query($link, $query) or die('select user failed: ' . mysqli_error($link));
    $users = array();
    while ($users[] = mysqli_fetch_array($result));
    return $users;
}

function update_user_sid($id, $sid) {
    $link = get_connection_user_db();
    $query = "UPDATE t_user SET sid = '$sid' WHERE id = $id";
    return mysqli_query($link, $query);
}

function update_user_account($id, $account, $link) {
    if($link == NULL) {
        $link = get_connection_user_db();
    }
    $query = "UPDATE t_user SET account = $account WHERE id = $id";
    return mysqli_query($link, $query);
}

define("TYPE_CUSTOMER", 0);
define("TYPE_CONTRACTOR", 1);

function user_customer($id) {
    $user = get_user_by_id($id);
    if(!empty($user) && $user["type"] == TYPE_CUSTOMER) {
        return true;
    }
    return false;
}

function user_contractor($id) {
    $user = get_user_by_id($id);
    if(!empty($user) && $user["type"] == TYPE_CONTRACTOR) {
        return true;
    }
    return false;
}