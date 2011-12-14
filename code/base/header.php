<?php

/*
open HTML
*/

echo '<!DOCTYPE html>
<html>
<head>
	<script src="http://code.jquery.com/jquery-'.$code['jQuery'].'.min.js"></script>
	<script src="code/base/extensions.js" type="text/javascript"></script>
	<title>'.$code['name'].'</title>
	<meta http-equiv="content-Type" content="text/html; charset=UTF-8" />
	<meta name="robots" content="noindex,nofollow" />
	<link rel="stylesheet" type="text/css" media="screen" href="'.$code['skin_path'].'base.css" />
	<script type="text/javascript">
		var ecoder_name = "'.$code['name'].'"; // system name ##
		var ecoder_editor = "'.$code['editor'].'"; // editor version ##
		var ecoder_autosave = "'.$code['autosave'].'"; // autosave status ##
	</script>
	<script src="code/base/shortcuts.js" type="text/javascript"></script>
	<script src="code/base/javascript.js" type="text/javascript"></script>
	<link href="'.$code['skin_path'].'design/favicon.ico" rel="shortcut icon" type="image/ico" />';