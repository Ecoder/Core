<?php
/**
 * TODO Still lacking searchfunctionality
 * NTH Getting more out of codemirror, like highlighting, folding, ...
 */
include_once "code/editor/editor.php";
include_once "code/editor/file.php";
include_once "code/editor/status.php";
include_once "code/editor/filetype.php";
/***LOGIC***/
//Future parameters:
//-pathname
//Too early to rewrite editor. Need to rewrite all the rest first
//	Future functioning:
//		Pass pathname, get json of relevant data
//		In JS, editor gets built (w/ template)
$newfile="";
@ecoder_request($_GET['file'],$newfile,'');
$f=EditorFile($newfile);

class EditorFile extends SplFileInfo {
	private $cmMime;

	public function __construct($filename) {
		parent::__construct($filename);
		$this->setCodemirrorMime();
	}

	public function getCmMime() { return $this->cmMime; }

	private function setCmMime() {
		$ext=parent::getExtension();
		$mimes=array("php"=>"application/x-httpd-php-open","js"=>"text/javascript","html"=>"text/html","css"=>"text/css","text"=>"text/plain");
		$this->cmMime=$mimes[$ext];
		return;
	}
}

@ecoder_request( $_GET['mode'], $main['mode'], '' ); // mode or action ##
@ecoder_request( $_GET['path'], $main['path'], '' ); // path to file to process ##
@ecoder_request( $_GET['file'], $main['file'], '' ); // file to process ##
@ecoder_request( $_GET['type'], $main['type'], '' ); // type of file ##
@ecoder_request( $_GET['report'], $main['report'], '' ); // report / confirm ##
@ecoder_request( $_GET['report_code'], $main['report_code'], '' ); // report / confirm ##

//TODO later only include relevant pieces
$file=new File();

$file->makeBackupIfNeeded();
$editor=new Editor();
include "code/editor/tpl.php";