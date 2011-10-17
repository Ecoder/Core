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
    <link href="'.$code['skin_path'].'edit.css" rel="stylesheet" type="text/css" media="screen" />';
    include "css.php"; // browser specific css ##         
    
    if ( $save['file_loaded'] == 1 ) { // file loaded, so colour ##
    echo '
    <script src="plug/'.$_SESSION['editor_name'].'/'.$_SESSION['editor_file'].'" type="text/javascript"></script>';
    
    include "code/edit/editarea_ini.php"; 
    
    } // file loaded ##
    
    echo '
    <script src="code/base/shortcuts.js" type="text/javascript"></script>
    <script src="code/edit/javascript.js" type="text/javascript"></script>';
    
?>
