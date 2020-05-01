<?php

#To test some random things
require_once("DatabaseConnection.php");

class ztemp {

	function get() {
		$databaseConnection = new DatabaseConnection();
		return $databaseConnection->get('zin-game-jackace-connect');
	}
	
	function create() {
		$databaseConnection = new DatabaseConnection();
		return $databaseConnection->create('zin-game-jackace-connection', array(
			'server_id' => 'xyz_server',
			'client_id' => 'xyz_client'
		));
	}

}