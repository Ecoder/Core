<?php
class Input {
	private static $_instance;
	private $data;
	
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
		if (isset($_POST['json'])) {
			$j=json_decode($_POST['json'],true);
			foreach ($j as $k=>$v) {
				$this->$k=$v;
			}
		}
	}
	
	public static function _get() {
		if (self::$_instance==null) {
			self::$_instance=new Input();
		}
		return self::$_instance;
	}
}