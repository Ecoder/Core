<?php

/* 
open HTML
*/

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>tree</title>
    <meta http-equiv="content-Type" content="text/html; charset=UTF-8" />
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"; type="text/javascript"></script>
    <link href="'.$code['skin_path'].'tree.css" rel="stylesheet" type="text/css" media="screen" />';
    include "code/tree/css.php"; // browser specific css ##         
    echo '
    <script src="code/base/shortcuts.js" type="text/javascript"></script>
    <script src="code/tree/javascript.js" type="text/javascript"></script>
    <!--<script src="code/tree/menu.js" type="text/javascript"></script>-->';        

?>
