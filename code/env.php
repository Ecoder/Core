<?php
class Env {
	private $maxUploadSize,$dirSep,$tree_showHidden,$lang,$autosave;

	public function __construct() {
		global $cnf,$code;
		$this->maxUploadSize=$this->phpIniSizeToBytes(ini_get("upload_max_filesize"));
		$this->dirSep=DIRECTORY_SEPARATOR;
		$this->tree_showHidden=$cnf['showHidden'];
		$this->lang=$code['lang'];
		$this->autosave=$code['autosave'];
	}

	public static function get() {
		$env=new Env();
		$env->__toOutput();
	}

	public function __toOutput() {
		Output::add("maxUploadSize",$this->maxUploadSize);
		Output::add("dirSep",$this->dirSep);
		Output::add("tree_showHidden",$this->tree_showHidden);
		Output::add("lang",$this->lang);
		Output::add("autosave",$this->autosave);
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