<?php

require_once("DatabaseConnection.php");

#To test some random things
class ztemp {

	function get() {
		$databaseConnection = new DatabaseConnection();
		return $databaseConnection->get('zin-game-jackace-connection');
	}
	
	function create() {
		$databaseConnection = new DatabaseConnection();
		return $databaseConnection->create('zin-game-jackace-connection', array(
			'server_id' => 'xyz_server',
			'client_id' => 'xyz_client'
		));
	}

}