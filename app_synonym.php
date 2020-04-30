<?php

require_once("CommonUtil.php");

class ResolveApp {

	public $app_syn_arr_reverse = array();
	
	function resolve_generic_app_name($app_name) {
		$this->fill_the_values();
		if(array_key_exists($app_name, $this->app_syn_arr_reverse)) {
		    return $this->app_syn_arr_reverse[$app_name];
		} else {
		    throw new Exception("No such app ".$app_name." exists. Existing apps are "
		    .implode(", ", array_keys($this->app_syn_arr_reverse)));
		}
	}
	
	function fill_the_values() {
		if(count($this->app_syn_arr_reverse) == 0) {					
		    $this->app_syn_arr_reverse = CommonUtil::generate_value_key_arr_from_key_value(array(
				"zin_game_card_jackace" => "jack, jackace, card_jack, j_ace, ace_jack, z_g_c_j_a, jace"
			));
		}
	}
	
}