<?php
include "../../code.php";

final class SaveStatus {
	const SUCCESS=1;
	const NOTWRITEABLE=-1;
	const NOTAFILE=-2;
	const NOEXISTS=-3;
}

function save_edit() {
	global $code;
	$path="";
	$file="";
	$content="";
	
	@ecoder_request($_POST['ecoder_path'],$path,'');
	@ecoder_request($_POST['ecoder_file'],$file,'');
	@ecoder_request($_POST['ecoder_content'],$content,'');
	
	if (get_magic_quotes_gpc()) {
		$content=stripslashes($content);
	}
	
	$fullpath=$code['root'].$path.$file;
	if (!file_exists($fullpath)) { return SaveStatus::NOEXISTS; }
	if (!is_file($fullpath)) { return SaveStatus::NOTAFILE; }
	if (!is_writable($fullpath)) { return SaveStatus::NOTWRITEABLE; }
	
	fwrite(fopen($fullpath,'w'),$content);
	return SaveStatus::SUCCESS;
}

$status=save_edit();