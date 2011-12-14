<?php
class Input {
	private static $_instance;
	private $data;
	private $raw;

	public function __set($name, $value) {
		$this->data[$name] = $value;
	}
	public function __get($name) {
		if (array_key_exists($name, $this->data)) {
			return $this->data[$name];
		}
		return null;
	}
	public function __isset($name) {
		return isset($this->data[$name]);
	}
	public function __unset($name) {
		unset($this->data[$name]);
	}

	private function __construct() {
		$this->data=array();
		if (isset($_POST['json'])) {
			$this->raw=$_POST['json'];
			$arr=json_decode($_POST['json'],true);
			foreach ($arr as $k=>$v) {
				$this->data[$k]=$v;
			}
		}
	}

	public static function _get() {
		if (self::$_instance==null) {
			self::$_instance=new Input();
		}
		return self::$_instance;
	}

	public static function raw() {
		return self::_get()->raw;
	}
}

class Output {
	private static $data;

	public static function add($k,$v) {
		self::$data[$k]=$v;
	}

	public static function send() {
		echo json_encode(self::$data);
		exit;
	}
}