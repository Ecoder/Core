<?php
class Env {
	private $maxUploadSize,$dirSep,$tree_showHidden,$lang,$autosave,$version;

	public function __construct() {
		global $cnf,$code;
		$this->maxUploadSize=$this->phpIniSizeToBytes(ini_get("upload_max_filesize"));
		$this->dirSep=DIRECTORY_SEPARATOR;
		$this->tree_showHidden=$cnf['showHidden'];
		$this->lang=$code['lang'];
		$this->autosave=$code['autosave'];
		$this->version=$code['version'];
	}

	public static function get() {
		$env=new Env();
		$env->__toOutput();
	}

	public static function getNoOutput() {
		$env=new Env();
		return json_encode($env->__toStdObj());
	}

	public function __toStdObj() {
		$s=new stdClass();
		$s->maxUploadSize=$this->maxUploadSize;
		$s->dirSep=$this->dirSep;
		$s->tree_showHidden=$this->tree_showHidden;
		$s->lang=$this->lang;
		$s->autosave=$this->autosave;
		$s->version=$this->version;
		return $s;
	}

	public function __toOutput() {
		Output::add("maxUploadSize",$this->maxUploadSize);
		Output::add("dirSep",$this->dirSep);
		Output::add("tree_showHidden",$this->tree_showHidden);
		Output::add("lang",$this->lang);
		Output::add("autosave",$this->autosave);
		Output::add("version",$this->version);
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