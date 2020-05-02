<?php

class ResponseUtil {
			
	public static function success($data)
	{
		self::response(200, "Success", $data);
	}		
	public static function error($data)
	{
		self::response(500, "Failed", $data);
	}		
	public static function caught($e)
	{
		self::response(1234, "ExceptionError", array(
		"line"=>$e->getLine(),
		"file"=>$e->getFile(),
		"message"=>$e->getMessage()
		));
	}
	public static function response($status,$status_message,$data)
	{
		$response['status']=$status;
		$response['status_message']=$status_message;
		$response['data']=$data;
		
		$json_response = json_encode($response);
		error_log('(Response)     '.$json_response);
		echo $json_response;
	}
}