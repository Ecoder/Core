<?php
final class Filetype {
	private $type;
	const PHP="php";
	const JS="js";
	const HTML="html";
	const CSS="css";
	const TEXT="text";
	
	private function __construct($type) {
		$this->type=$type;
	}
	
	//Convertors
	public function typestring() {
		return $this->type;
	}
	public function mime() {
		switch ($this->type) {
			case Filetype::PHP:
				return "application/x-httpd-php-open";
			case Filetype::JS:
				return "text/javascript";
			case Filetype::HTML:
				return "text/html";
			case Filetype::CSS:
				return "text/css";
			case Filetype::TEXT:
				return "text/plain";
		}
	}
	
	//Initializers
	public static function typestring($type) {
		switch ($type) {
			case "php":
				return new Filetype(Filetype::PHP);
			case "js":
				return new Filetype(Filetype::JS);
			case "html":
				return new Filetype(Filetype::HTML);
			case "css":
				return new Filetype(Filetype::CSS);
			case "text":
				return new Filetype(Filetype::TEXT);
		}
	}
}