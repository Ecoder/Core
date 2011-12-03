<?php
include "code.php";

//Public properties because we have to json_encode it -_-
class Info {
	public $maxUploadSize,$dirSep,$tree_showHidden,$lang;

	public function __construct() {
		global $cnf,$code;
		$this->maxUploadSize=$this->phpIniSizeToBytes(ini_get("upload_max_filesize"));
		$this->dirSep=DIRECTORY_SEPARATOR;
		$this->tree_showHidden=$cnf['showHidden'];
		$this->lang=$code['lang'];
	}

	private function phpIniSizeToBytes($val) {
		$val = trim($val);
		$last = strtolower($val[strlen($val)-1]);
		switch($last) {
			// The 'G' modifier is available since PHP 5.1.0
			case 'g':
				$val *= 1024;
			case 'm':
				$val *= 1024;
			case 'k':
				$val *= 1024;
		}

		return $val;
	}
}

Output::add("info",new Info());
Output::send();