<?php

/* 
open HTML
*/

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>e'.$html['title'].'</title>
    <meta http-equiv="content-Type" content="text/html; charset=UTF-8" />
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"; type="text/javascript"></script>
    ';
    include "css.php"; // browser specific css ##         
    
   /* if ( $save['file_loaded'] == 1 ) { // file loaded, so colour ##
    echo '
    <script src="plug/'.$_SESSION['editor_name'].'/'.$_SESSION['editor_file'].'" type="text/javascript"></script>';
    
    include "code/edit/editarea_ini.php"; 
    
    } // file loaded ##*/
		?>
		<link rel="stylesheet" href="plug/codemirror-ui/lib/CodeMirror-2.0/lib/codemirror.css" />
		<link rel="stylesheet" href="plug/codemirror-ui/lib/CodeMirror-2.0/theme/default.css" />
    <script src="plug/codemirror-ui/lib/CodeMirror-2.0/lib/codemirror.js"></script>
    <script src="plug/codemirror-ui/lib/CodeMirror-2.0/mode/xml/xml.js"></script>
    <script src="plug/codemirror-ui/lib/CodeMirror-2.0/mode/javascript/javascript.js"></script>
    <script src="plug/codemirror-ui/lib/CodeMirror-2.0/mode/css/css.js"></script>
    <script src="plug/codemirror-ui/lib/CodeMirror-2.0/mode/htmlmixed/htmlmixed.js"></script>
		<script src="plug/codemirror-ui/lib/CodeMirror-2.0/mode/clike/clike.js"></script>
    <script src="plug/codemirror-ui/lib/CodeMirror-2.0/mode/php/php.js"></script>
		
		<script src="plug/codemirror-ui/js/codemirror-ui.js" type="text/javascript"></script>
		<link rel="stylesheet" href="plug/codemirror-ui/css/codemirror-ui.css" type="text/css" media="screen" />
		<?php
    echo '
    <script src="code/base/shortcuts.js" type="text/javascript"></script>
    <script src="code/edit/javascript.js" type="text/javascript"></script>
		<link href="'.$code['skin_path'].'edit.css" rel="stylesheet" type="text/css" media="screen" />';
    include "css.php";
?>
