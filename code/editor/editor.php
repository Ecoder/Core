<?php
include_once "status.php";
include_once "filetype.php";
include_once "file.php";

class Editor {
	//Statuses
	private $stSytxHl, $stAutosave;

	public function __construct() {
		global $main,$f,$code;
		$this->stSytxHl=Status::boolean(($_SESSION['editor'] == 'delux'));
		$this->stAutosave=Status::doubleBoolean(!$f->isWritable(), ($code['autosave']==1));
	}

	public function __get($name) {
		if (isset($this->$name)) {
			return $this->$name;
		}

		$trace = debug_backtrace();
		trigger_error('Undefined property via __get(): '.$name.' in '.$trace[0]['file'].' on line '.$trace[0]['line'],E_USER_NOTICE);
		return null;
	}
}