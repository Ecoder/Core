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
@ecoder_request( $_GET['mode'], $main['mode'], '' ); // mode or action ##
@ecoder_request( $_GET['path'], $main['path'], '' ); // path to file to process ##
@ecoder_request( $_GET['file'], $main['file'], '' ); // file to process ##
@ecoder_request( $_GET['type'], $main['type'], '' ); // type of file ##
@ecoder_request( $_GET['report'], $main['report'], '' ); // report / confirm ##
@ecoder_request( $_GET['report_code'], $main['report_code'], '' ); // report / confirm ##
@ecoder_request( $_GET['shut'], $main['shut'], 1 ); // close override ##

//TODO later only include relevant pieces
$file=new File();

$isReadOnly=false;	//TODO maybe we should turn this one around...

// defaults ##
$html['title_note'] = ''; // notes about file ##

// buttons ##
$main['nav']['save_note'] = ''; // nada ##
// get file name, without dot or slash ##    
$main['frame_clean'] = ecoder_iframe_clean ( $file->path.$file->name ); // for close and refresh ##

if ($file->name && $main['type'] ) { // loaded ##
	if ( $main['mode'] == 'read' ) { // read-only ##       
		$html['title_note'] = ' [READ ONLY]';
		$isReadOnly=true;
	} else { // editable ##
		$main['nav']['save_note'] = 'saving...'; // save note ##
	}
}


//Longing for the day we can throw this function out...
function checkHomeFile() {
	global $code,$tabs,$main;
	if (!file_exists($code['root'].$tabs['home'].".txt")) {
		ecoder_copy(getcwd()."/docs/home.txt",$code['root'].$tabs['home'].".txt",$code['permissions_file']);
		if (is_writable($code['root'].$tabs['home'].".txt")) {
			file_put_contents($code['root'].$tabs['home'].".txt",$tabs['home_content']);
			$main['report'] = $tabs['home'].'.txt added';
      $main['report_code'] = 1;
		} else {
			$main['report'] = 'error creating home tab';
			$main['report_code'] = 0;
		}
	}
}
checkHomeFile();

$file->makeBackupIfNeeded();
$editor=new Editor();
include "code/editor/tpl.php";