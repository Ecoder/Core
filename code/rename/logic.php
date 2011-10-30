<?php
//TODO
// * Further cleanup
// * Review layout
// * Commit
// * Idea for translations: file in json, so it can be used in php and js
@ecoder_request( $_GET['path'], $main['path'], '' ); // path to file to process ##
@ecoder_request( $_GET['file'], $main['file'], '' ); // file to process ##
@ecoder_request( $_GET['type'], $main['type'], '' ); // type of file ##

$html['title'] = $main['file'];
$main['file'] = 'rename_'.$main['type'];
$main['frame_clean'] = ecoder_iframe_clean ( $main['path'].'rename_'.$main['type'] ); // for close and refresh

$main['file_ext'] = ''; // nada for folder ##

if ( $main['type'] == 'file' ) {
	$main['ext_array'] = explode ( ".", $html['title'] ); // get extension ##
	$main['file_ext'] = end ( $main['ext_array'] ); // get extension ##  
}

include "code/rename/tpl.php";