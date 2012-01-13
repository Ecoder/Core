<?php include "code.php"; ?>
<!DOCTYPE html>
<html>
<head>
	<script src="http://code.jquery.com/jquery-<?php echo $code['jQuery']; ?>.min.js"></script>
	<script src="code/base/extensions.js" type="text/javascript"></script>

	<link rel="stylesheet" href="plug/codemirror-2_2-min.css" />
	<!--<link rel="stylesheet" href="plug/codemirror/lib/codemirror.css" />
	<link rel="stylesheet" href="plug/codemirror/mode/diff/diff.css" />
	<link rel="stylesheet" href="plug/codemirror/mode/rpm/spec/spec.css" />
	<link rel="stylesheet" href="plug/codemirror/mode/tiddlywiki/tiddlywiki.css" />-->
	<script src="plug/codemirror-2_2-min.js"></script>
	<!--<script src="plug/codemirror/lib/codemirror.js"></script>
	<script src="plug/codemirror/lib/util/foldcode.js"></script>
	<script src="plug/codemirror/mode/clike/clike.js"></script>
	<script src="plug/codemirror/mode/clojure/clojure.js"></script>
	<script src="plug/codemirror/mode/coffeescript/coffeescript.js"></script>
	<script src="plug/codemirror/mode/css/css.js"></script>
	<script src="plug/codemirror/mode/diff/diff.js"></script>
	<script src="plug/codemirror/mode/gfm/gfm.js"></script>
	<script src="plug/codemirror/mode/groovy/groovy.js"></script>
	<script src="plug/codemirror/mode/haskell/haskell.js"></script>
	<script src="plug/codemirror/mode/htmlembedded/htmlembedded.js"></script>
	<script src="plug/codemirror/mode/htmlmixed/htmlmixed.js"></script>
	<script src="plug/codemirror/mode/javascript/javascript.js"></script>
	<script src="plug/codemirror/mode/jinja2/jinja2.js"></script>
	<script src="plug/codemirror/mode/lua/lua.js"></script>
	<script src="plug/codemirror/mode/markdown/markdown.js"></script>
	<script src="plug/codemirror/mode/ntriples/ntriples.js"></script>
	<script src="plug/codemirror/mode/pascal/pascal.js"></script>
	<script src="plug/codemirror/mode/perl/perl.js"></script>
	<script src="plug/codemirror/mode/php/php.js"></script>
	<script src="plug/codemirror/mode/plsql/plsql.js"></script>
	<script src="plug/codemirror/mode/python/python.js"></script>
	<script src="plug/codemirror/mode/r/r.js"></script>
	<script src="plug/codemirror/mode/rpm/changes/changes.js"></script>
	<script src="plug/codemirror/mode/rpm/spec/spec.js"></script>
	<script src="plug/codemirror/mode/rst/rst.js"></script>
	<script src="plug/codemirror/mode/ruby/ruby.js"></script>
	<script src="plug/codemirror/mode/rust/rust.js"></script>
	<script src="plug/codemirror/mode/scheme/scheme.js"></script>
	<script src="plug/codemirror/mode/smalltalk/smalltalk.js"></script>
	<script src="plug/codemirror/mode/sparql/sparql.js"></script>
	<script src="plug/codemirror/mode/stex/stex.js"></script>
	<script src="plug/codemirror/mode/tiddlywiki/tiddlywiki.js"></script>
	<script src="plug/codemirror/mode/velocity/velocity.js"></script>
	<script src="plug/codemirror/mode/xml/xml.js"></script>
	<script src="plug/codemirror/mode/xmlpure/xmlpure.js"></script>
	<script src="plug/codemirror/mode/yaml/yaml.js"></script>-->

	<title>Ecoder</title>
	<meta http-equiv="content-Type" content="text/html; charset=UTF-8" />
	<meta name="robots" content="noindex,nofollow" />
	<link rel="stylesheet" href="skin/one/base.css" />
	<script src="code/base/shortcuts.js"></script>
	<script src="code/ecoder.js"></script>
	<link href="skin/one/design/favicon.ico" rel="shortcut icon" type="image/ico" />
</head>
<body>
	<div id="tree"><h2>Ecoder</h2></div>
	<div id="tabs"><ul id="tablist"></ul><div id="panels"></div></div>
</body>
</html>