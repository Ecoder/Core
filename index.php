<?php include "code.php"; ?>
<!DOCTYPE html>
<html>
<head>
	<script src="http://code.jquery.com/jquery-<?php echo $code['jQuery']; ?>.min.js"></script>
	<script src="code/base/extensions.js" type="text/javascript"></script>
	<title><?php echo $code['name']; ?></title>
	<meta http-equiv="content-Type" content="text/html; charset=UTF-8" />
	<meta name="robots" content="noindex,nofollow" />
	<link rel="stylesheet" type="text/css" media="screen" href="skin/one/base.css" />
	<script type="text/javascript">
		var ecoder_name = "<?php echo $code['name']; ?>"; // system name ##
		var ecoder_editor = "<?php echo $code['editor']; ?>"; // editor version ##
		var ecoder_autosave = "<?php echo $code['autosave']; ?>"; // autosave status ##
	</script>
	<script src="code/base/shortcuts.js" type="text/javascript"></script>
	<script src="code/base/javascript.js" type="text/javascript"></script>
	<link href="skin/one/design/favicon.ico" rel="shortcut icon" type="image/ico" />
</head>
<body>
	<div id="tree"><h2>Ecoder</h2></div>
	<div id="tabs"><ul></ul><div id="panels"></div></div>
</body>
</html>