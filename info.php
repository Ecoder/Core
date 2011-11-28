<?php
//Could be cleaner... way cleaner...
include "code.php";

function return_bytes($val) {
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

function getInfo(){
	global $cnf,$code;
	$upload_max_filesize=return_bytes(ini_get("upload_max_filesize"));

	Output::add("maxUploadSize",$upload_max_filesize);
	Output::add("dirSep",DIRECTORY_SEPARATOR);
	Output::add("tree_showHidden",$cnf['showHidden']);
	Output::add("lang",$code['lang']);
}
getInfo();
Output::send();