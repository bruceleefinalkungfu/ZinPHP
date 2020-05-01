<?php

require_once("DatabaseConnection.php");
require_once("Call.php");

#To test some random things
class ztemp {

	function get() {
		$databaseConnection = new DatabaseConnection();
		return $databaseConnection->get();
	}
	
	function create() {
		$databaseConnection = new DatabaseConnection();
		return $databaseConnection->create();
	}

	function callCInstanceExample() {
		return Call::c($this, 'callCInstanceExample2');
	}
	
	function callCInstanceExample2() {
		return "callCInstanceExample2 called";
	}

	static function callCStaticExample() {
		return Call::statically(static::class, 'callCInstanceExample2');
	}
	
	static function callCStaticExample2() {
		return "callCStaticExample2 called";
	}

}