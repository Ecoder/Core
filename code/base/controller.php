<?php
/*
 * LATER
 *  - Extend Controller to be more dynamic, input validation, ...
 */
abstract class Controller {
	public static $DFLT;
	
	public static function _init($action="") {
		if (empty($action)) {
			$action=static::$DFLT;
		}
		$st=new static();
		call_user_func(array($st,$action));
		Output::send();
	}
}