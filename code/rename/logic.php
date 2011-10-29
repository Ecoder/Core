<?php
//TODO
// * Further cleanup
// * Review layout
// * Commit
// * Idea for translations: file in json, so it can be used in php and js
@ecoder_request( $_GET['path'], $main['path'], '' ); // path to file to process ##
@ecoder_request( $_GET['file'], $main['file'], '' ); // file to process ##
@ecoder_request( $_GET['type'], $main['type'], '' ); // type of file ##
@ecoder_request( $_GET['report'], $main['report'], '' ); // report / confirm ##
@ecoder_request( $_GET['report_code'], $main['report_code'], '' ); // report / confirm ##

$html['title'] = $main['file'];
$main['file'] = 'rename_'.$main['type'];
$main['frame_clean'] = ecoder_iframe_clean ( $main['path'].'rename_'.$main['type'] ); // for close and refresh

$main['file_ext'] = ''; // nada for folder ##

if ( $main['type'] == 'file' ) {
	$main['ext_array'] = explode ( ".", $html['title'] ); // get extension ##
	$main['file_ext'] = end ( $main['ext_array'] ); // get extension ##  
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

include "code/rename/tpl.php";