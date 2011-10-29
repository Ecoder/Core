<?php
/*
 * Move to more general place later
 */
final class Status {
	private $status;
	const DISABLED=-1;
	const OFF=0;
	const ON=1;
	
	private function __construct($status) { 
		$this->status=$status;
	}
	
	//CONVERTORS
	public function i() {
		return $this->status;
	}
	public function s() {
		if ($this->status==Status::DISABLED) {
			return "disabled";
		} else if ($this->status==Status::OFF) {
			return "off";
		} else if ($this->status==Status::ON) {
			return "on";
		}
	}
	public function b() {
		if ($this->status==Status::ON) {
			return true;
		}
		return false;
	}
	
	public function not() {
		if ($this->status==Status::DISABLED) {
			return $this;
		} else if ($this->status==Status::ON) {
			return new Status(Status::OFF);
		}
		return new Status(Status::ON);
	}
	
	//INITIALIZORS
	public static function boolean($b) {
		if ($b) {
			return new Status(Status::ON);
		}
		return new Status(Status::DISABLED);
	}
	public static function doubleBoolean($disBool,$onOffBool) {
		if ($disBool) {
			return new Status(Status::DISABLED);
		}
		if ($onOffBool){
			return new Status(Status::ON);
		}
		return new Status(Status::OFF);
	}
}