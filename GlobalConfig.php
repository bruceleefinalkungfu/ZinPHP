<?php
class GlobalConfig {
	public static $conf_allowed_app_names_arr = array(
				"ZinGameCardJackAce" => "zingamecardjackace, zin_game_card_jackace, jack, jackace, card_jack, j_ace, ace_jack, z_g_c_j_a, jace",
				"DatabaseConnection" => "databaseconnection, db, database"
			);
	public static $conf_allowed_method_names_arr = array(
				"get" => "get, find, fetch",
				"create" => "create, insert, add",
				"update" => "update, modify, change",
				"delete" => "delete, remove, del, erase"
			);
	public static $debug_types = array(
		"", "query", "verbose", "method", "all"
	);
}