<?php
header("Content-Type:application/json");

require_once("ResolveMethod.php");
require_once("ResolveApp.php");
require_once("DefaultApp.php");
require_once("ResponseUtil.php");
require_once("ztemp.php");

try {
	$resolve_method_obj = new ResolveMethod();
	$resolve_app_obj = new ResolveApp();
	$method_name_from_query_param = array_key_exists('method', $_GET) ? $_GET['method'] : ( array_key_exists('m', $_GET) ? $_GET['m'] : 'default_method');
	$app_name_from_query_param = array_key_exists('app', $_GET) ? $_GET['app'] : ( array_key_exists('a', $_GET) ? $_GET['a'] : 'DefaultApp');
	$method_to_call= ($method_name_from_query_param == 'default_method' || $method_name_from_query_param == '' )
				? 'default_method' : $resolve_method_obj->resolve_generic_method_name($method_name_from_query_param);
	$app_to_call= ($app_name_from_query_param == 'DefaultApp' || $app_name_from_query_param == '') 
				? 'DefaultApp' : $resolve_app_obj->resolve_generic_app_name($app_name_from_query_param);
	$app_to_call_obj = new $app_to_call();	
	$response = $app_to_call_obj->$method_to_call();
	ResponseUtil::success($response);
} catch(Exception $e) {
  ResponseUtil::caught($e);
} catch(Error $e) {
  ResponseUtil::caught($e);
}