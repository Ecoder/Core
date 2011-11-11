<?php

/* 
file upload script ##
*/

include "../../code.php"; // single included settings file ##

/*
include_once "code/filestreamer.php";
require_once('Streamer.php');
sleep(20);
$ft = new FileStreamer();
$ft->setDestination('uploads/');
$ft->receive();
*/

class UploadStatuses { //ENUM
	const DIRNOEXIST="dirnoexist";
	const DIRNOWRITE="dirnowrite";
	const NOUPLSLCTD="nouplslctd";
	const FILETOOBIG="filetoobig";
	const PARTIALUPL="partialupl";
	const MISSINGTMP="missingtmp";
	const WRTDSKFAIL="wrtdskfail";
	const UPLSTOPEXT="uplstopext";
	const INVALDTYPE="invaldtype";
	const FLRDYEXSTS="flrdyexsts";
	const UNKNOWNERR="unknownerr";
	const UPLSUCCESS="uplsuccess";
}

function determineError($errcode) {
	global $res;
	switch ($errcode) { // check file ##
		case 1:
		case 2:
			$res=UploadStatuses::FILETOOBIG;
			break;
		case 3:
			$res=UploadStatuses::PARTIALUPL;
			break;
		case 4:
			$res=UploadStatuses::NOUPLSLCTD;
			break;
		case 6:
			$res=UploadStatuses::MISSINGTMP;
			break;
		case 7:
			$res=UploadStatuses::WRTDSKFAIL;
			break;
		case 8:
			$res=UploadStatuses::UPLSTOPEXT;
			break;
	}
}

function endUpload() {
	global $res;
	$save['header'] = "../../upload.php?path=".$save['path']."&report=".$res;
	header("location: ".$save['header']);
	exit;
}

$res=null;
$save['path']=(isset($_POST['path']) ? $_POST['path'] : '');
$uploaddir=$code['root'].$save['path'];

// what file types do you want to disallow?
$blacklist = $_SESSION['upload_banned'];

// filter allowable file types from file type array ## TODO okay, lambda isn't really a readability improvement here. Fix!
$allowed_filetypes=array_filter($_SESSION['tree_file_types'],function($str){
	return !in_array($str,$_SESSION['upload_banned']);
});

foreach ($allowed_filetypes as $k=>$v) {
	$allowed_filetypes[$k]=".".$v;
}

if ( !is_dir ( $uploaddir ) ) { // upload directory not there ##
	$res=UploadStatuses::DIRNOEXIST;
	endUpload();
}
if ( !is_writable ( $uploaddir ) ) {
	$res=UploadStatuses::DIRNOWRITE;
	endUpload();
}

if (!isset($_FILES['file'])) { // file sent ##
	$res=UploadStatuses::NOUPLSLCTD;
	endUpload();
}

if ($_FILES['file']['error']!= 0) {
	determineError($_FILES['file']['error']);
	endUpload();
}

foreach ($blacklist as $item) {
	if (strEndsWith($_FILES['file']['name'],$item,$false)) {
		$res=UploadStatuses::INVALDTYPE;
		unset($_FILES['file']['tmp_name']);
		endUpload();
	}
}

$exta=explode(".",$_FILES['file']['name']);
$ext='.'.end($exta);
unset($exta);
if (!in_array($ext,$allowed_filetypes)) {
	$res=UploadStatuses::INVALDTYPE;
	endUpload();
}

if (file_exists($uploaddir.$_FILES["file"]["name"])) {
	$res=UploadStatuses::FLRDYEXSTS;
	endUpload();
}

if (!is_uploaded_file($_FILES['file']['tmp_name'])) {
	determineError($_FILES['file']['error']);
	endUpload();
}

if (!move_uploaded_file($_FILES['file']['tmp_name'],$uploaddir.$_FILES['file']['name'])) {
	$res=UploadStatuses::UNKNOWNERR;
	unset($_FILES['file']['tmp_name']);
	endUpload();
}

$res=UploadStatuses::UPLSUCCESS;
endUpload();