<?php
header("Content-Type:application/json");
require_once("method_synonym.php");
require_once("app_synonym.php");

class zin_game_card_jackace {

	public static function delete()
	{
		success($_GET['name']);
	}
}


$resolve_method_obj = new ResolveMethod();
$resolve_app_obj = new ResolveApp();
$method_to_call=$resolve_method_obj->resolve_generic_method_name($_GET['method']);
$app_to_call=$resolve_app_obj->resolve_generic_app_name($_GET['app']);
$app_to_call::$method_to_call();


function success($data)
{
	response(200, "Success", $data);
}
function response($status,$status_message,$data)
{
	$response['status']=$status;
	$response['status_message']=$status_message;
	$response['data']=$data;
	
	$json_response = json_encode($response);
	echo $json_response;
}