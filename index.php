<?php include "code.php"; ?>
<!DOCTYPE html>
<html>
<head>
	<script src="http://code.jquery.com/jquery-<?php echo $code['jQuery']; ?>.min.js"></script>
	<script src="code/base/extensions.js" type="text/javascript"></script>

	<link rel="stylesheet" href="plug/codemirror/lib/codemirror.css" />
	<link rel="stylesheet" href="plug/codemirror/theme/default.css" />
	<script src="plug/codemirror/lib/codemirror.js"></script>
	<script src="plug/codemirror/mode/clike/clike.js"></script>
	<script src="plug/codemirror/mode/coffeescript/coffeescript.js"></script>
	<script src="plug/codemirror/mode/css/css.js"></script>
	<script src="plug/codemirror/mode/htmlmixed/htmlmixed.js"></script>
	<script src="plug/codemirror/mode/javascript/javascript.js"></script>
	<script src="plug/codemirror/mode/php/php.js"></script>
	<script src="plug/codemirror/mode/python/python.js"></script>
	<script src="plug/codemirror/mode/ruby/ruby.js"></script>
	<script src="plug/codemirror/mode/xml/xml.js"></script>
	<script src="plug/codemirror/mode/xmlpure/xmlpure.js"></script>
	<script src="plug/codemirror/mode/yaml/yaml.js"></script>

	<title><?php echo $code['name']; ?></title>
	<meta http-equiv="content-Type" content="text/html; charset=UTF-8" />
	<meta name="robots" content="noindex,nofollow" />
	<link rel="stylesheet" href="skin/one/base.css" />
	<script src="code/base/shortcuts.js"></script>
	<script src="code/base/javascript.js"></script>
	<link href="skin/one/design/favicon.ico" rel="shortcut icon" type="image/ico" />
</head>
<body>
	<div id="tree"><h2>Ecoder</h2></div>
	<div id="tabs"><ul id="tablist"></ul><div id="panels"></div></div>
</body>
</html>