<?php

require_once("CommonUtil.php");
require_once("GlobalConfig.php");

class ResolveApp {

	public $app_syn_arr_reverse = array();
	
	function resolve_generic_app_name($app_name) {
		$this->fill_the_values();
		if(array_key_exists($app_name, $this->app_syn_arr_reverse)) {
			CommonUtil::d($app_name." app resolved to ".$this->app_syn_arr_reverse[$app_name]);
		    return $this->app_syn_arr_reverse[$app_name];
		} else {
		    CommonUtil::d("No such app ".$app_name." exists. Existing apps are "
		    .implode(", ", array_keys($this->app_syn_arr_reverse)));
			return $app_name;
		}
	}
	
	function fill_the_values() {
	    #CommonUtil::d(implode(', ', array_keys(GlobalConfig::$conf_allowed_app_names_arr)));
		if(count($this->app_syn_arr_reverse) == 0) {					
		    $this->app_syn_arr_reverse = CommonUtil::generate_value_key_arr_from_key_value(GlobalConfig::$conf_allowed_app_names_arr);
		}
	}
	
}