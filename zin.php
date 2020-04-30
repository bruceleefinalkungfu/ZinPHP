<?php
header("Content-Type:application/json");
require_once("method_synonym.php");
require_once("app_synonym.php");
require_once("default_app.php");
require_once("ResponseUtil.php");

try {
	$resolve_method_obj = new ResolveMethod();
	$resolve_app_obj = new ResolveApp();
	$method_name_from_query_param = array_key_exists('method', $_GET) ? $_GET['method'] : ( array_key_exists('m', $_GET) ? $_GET['m'] : 'default_method');
	$app_name_from_query_param = array_key_exists('app', $_GET) ? $_GET['app'] : ( array_key_exists('a', $_GET) ? $_GET['a'] : 'default_app');
	$method_to_call= ($method_name_from_query_param == 'default_method' || $method_name_from_query_param == '' )
				? 'default_method' : $resolve_method_obj->resolve_generic_method_name($method_name_from_query_param);
	$app_to_call= ($app_name_from_query_param == 'default_app' || $app_name_from_query_param == '') 
				? 'default_app' : $resolve_app_obj->resolve_generic_app_name($app_name_from_query_param);
	$app_to_call_obj = new $app_to_call();	
	$response = $app_to_call_obj->$method_to_call();
	ResponseUtil::success($response);
} catch(Exception $e) {
  ResponseUtil::caught($e);
} catch(Error $e) {
  ResponseUtil::caught($e);
}