<?php

require_once("CommonUtil.php");
require_once("GlobalConfig.php");

class ResolveMethod {

	public $method_syn_arr_reverse = array();
	
	function resolve_generic_method_name($method) {
		$this->fill_the_values();
		if(array_key_exists($method, $this->method_syn_arr_reverse)) {
			CommonUtil::d($method." method resolved to ".$this->method_syn_arr_reverse[$method]);
		    return $this->method_syn_arr_reverse[$method];
		} else {
		    CommonUtil::d("No such method ".$method." exists. Existing methods are "
		    .implode(", ", array_keys($this->method_syn_arr_reverse)));
		}
	}
	
	function fill_the_values() {
	    #CommonUtil::d(implode(', ', array_keys(GlobalConfig::$conf_allowed_method_names_arr)));
		if(count($this->method_syn_arr_reverse) == 0) {					
		    $this->method_syn_arr_reverse = CommonUtil::generate_value_key_arr_from_key_value(GlobalConfig::$conf_allowed_method_names_arr);
		}
	}
	
}