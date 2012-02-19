<?php
include_once "lib/db.php";
include_once "io.php";
include_once "extension.php";
include_once "filetype.php";

include_once "config.php";

class Ecoder {

	static function __init() {
		global $db;
		Db::setConnectionInfo($db['db'],$db['user'],$db['pass']);
	}
}

Ecoder::__init();