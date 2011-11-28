<?php

/* 
open HTML
*/

echo '<!--[if IE]><?xml version="1.0" encoding="UTF-8"?><![endif]-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
				<script src="http://code.jquery.com/jquery-'.$code['jQuery'].'.min.js"></script>
				<script src="code/base/extensions.js" type="text/javascript"></script>
        <title>'.$code['name'].'</title>
        <meta http-equiv="content-Type" content="text/html; charset=UTF-8" />
        <meta name="robots" content="noindex,nofollow" />
        <link rel="stylesheet" type="text/css" media="screen" href="'.$code['skin_path'].'base.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="'.$code['skin_path'].'tabs.css" />';
        include "code/base/css.php"; // browser specific base css ## 
        include "code/tabs/css.php"; // browser specific tabs css ##        
        echo '
        <!--<script src="code/base/debug.js" type="text/javascript"></script>-->
        <script src="code/tabs/javascript.js" type="text/javascript"></script>';
        include "code/base/javascript.php"; // pass required variables from javascript to php ## 
        echo '        
        <script src="code/base/shortcuts.js" type="text/javascript"></script>
        <script src="code/base/javascript.js" type="text/javascript"></script>
        <link href="'.$code['skin_path'].'design/favicon.ico" rel="shortcut icon" type="image/ico" />';

?>
