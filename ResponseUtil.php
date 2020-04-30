<?php

class ResponseUtil {
			
	public static function success($data)
	{
		ResponseUtil::response(200, "Success", $data);
	}		
	public static function error($data)
	{
		ResponseUtil::response(500, "Error", $data);
	}		
	public static function caught($e)
	{
		ResponseUtil::error(array(
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
		echo $json_response;
	}
}