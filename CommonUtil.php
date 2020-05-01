<?php

class CommonUtil {
	
	public static function generate_value_key_arr_from_key_value($method_syn_arr) {
		$method_syn_arr_reverse = array();	
		$method_syn_arr_keys = array_keys($method_syn_arr);
		$method_syn_arr_size = count($method_syn_arr);
		for( $i=0; $i < $method_syn_arr_size; $i++ ) {
			$comma_separated_val_arr = explode(',', $method_syn_arr[$method_syn_arr_keys[$i]]);
			foreach($comma_separated_val_arr as $single_val) {
				$single_val = trim($single_val);
				if(array_key_exists($single_val, $method_syn_arr_reverse)) {
					throw new Exception("value ".$single_val+" already belongs to ".$method_syn_arr_reverse[$single_val].
					". It can't belong to ".$method_syn_arr_keys[$i]);
				}
				$method_syn_arr_reverse[$single_val] = $method_syn_arr_keys[$i];
			}
		}
		return $method_syn_arr_reverse;
	}
	
	public static function isDebugEnabled( $debugType) {
		return array_key_exists( $debugType.'debug', $_GET);
	}
	public static function debug($msg, $debugType) {
		if(self::isDebugEnabled( $debugType)) {
			echo $msg.'-----------</br>';
		}
	}
	public static function d($msg) {
		self::debug($msg, '');
	}
}