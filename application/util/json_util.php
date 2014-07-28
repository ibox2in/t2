<?php
/**
 * Created by PhpStorm.
 * User: Kim
 */

define("JSON_SUCCESS_TRUE", "{'success':true}");
define("JSON_SUCCESS_FALSE", "{'success':false}");


function form_json_success($object) {
    return "{'success':true, 'response': " . json_encode($object) . "}";
}