<?php

require "CommonUtil.php";

class ResolveMethod {

	public $method_syn_arr_reverse = array();
	
	function resolve_generic_method_name($method) {
		$this->fill_the_values();
		if(array_key_exists($method, $this->method_syn_arr_reverse)) {
		    return $this->method_syn_arr_reverse[$method];
		} else {
		    throw new Exception("No such method ".$method." exists. Existing methods are "
		    .implode(", ", array_keys($this->method_syn_arr_reverse)));
		}
		return $this->method_syn_arr_reverse[$method];
	}
	
	function fill_the_values() {
		if(count($this->method_syn_arr_reverse) == 0) {					
		    $this->method_syn_arr_reverse = CommonUtil::generate_value_key_arr_from_key_value(array(
				"get" => "get, find, need, fetch",
				"create" => "create, insert, add",
				"update" => "update, modify, change",
				"delete" => "delete, remove, del, erase",
			));
		}
	}
	
}