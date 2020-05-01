<?php

require_once("CommonUtil.php");

class Call {
	
	public static function c($classToCall, $methodName) {
		CommonUtil::debug("class: ".self::getClassName($classToCall)." -> method:".$methodName, "method");
		return call_user_func(array($classToCall, $methodName));
	}
	public static function getClassName($classToCall) {
		return get_class($classToCall);
		#return $classToCall::__CLASS__;
		#return get_called_class($classToCall);
	}
	
}