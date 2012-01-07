<!DOCTYPE html>
<html>
<head>
	<title>e<?php echo $file->name; ?></title>
	<script src="http://code.jquery.com/jquery-<?php echo $code['jQuery']; ?>.min.js"></script>

	<!--<link rel="stylesheet" href="plug/codemirror-<?php echo $code['codemirror']; ?>.min.css" />
	<script src="plug/codemirror-<?php echo $code['codemirror']; ?>.min.js"></script>-->

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

	<script src="code/base/shortcuts.js"></script>
	<script src="code/editor/js.js"></script>
	<link href="code/editor/css.css" rel="stylesheet" />
	<script type="text/javascript">
		var autosave_interval="<?php echo $code['autosave_time']*1000; ?>";
	</script>
</head>
<body class="editor" data-mime="<?php echo $f->getCmMime(); ?>" data-filename="<?php echo $file->name; ?>" data-path="<?php echo $file->path; ?>" data-canWrite="<?php echo (int)$f->isWritable(); ?>">
	<ul class="nav">
		<li id="save" class="subToPerm" title="<?php echo ($f->isWritable() ? "save file" : "file can't be saved"); ?>"></li>
		<li id="autosave" data-status="<?php echo $editor->stAutosave->i(); ?>" title="turn <?php echo $editor->stAutosave->not()->s(); ?> autosave feature"></li>
		<li id="reload" title="reload file"></li>
		<li id="synhl" data-status="<?php echo $editor->stSytxHl->i(); ?>" title="turn <?php echo $editor->stSytxHl->not()->s(); ?> syntax highlighting"></li>
		<li id="delete" class="subToPerm" title="<?php echo ($f->isWritable() ? "delete the file" : "delete option not available"); ?>"></li>
		<li id="rename" class="subToPerm" title="<?php echo ($f->isWritable() ? "rename the file" : "rename option not available"); ?>"></li>
		<li id="search" data-status="-1" title="Search/Replace"></li>
		<li id="undo" data-status="0" title="Undo"></li>
		<li id="redo" data-status="0" title="Redo"></li>
		<li id="jump" title="Jump to line"></li>
		<li id="reindsel" title="Reformat selection"></li>
		<li id="reinddoc" title="Reformat whole document"></li>
	</ul>

	<div class="content_wrapper">
		<div class="edit_area">
			<textarea id="editarea" name="content"><?php echo $f->getContent(); ?></textarea>
		</div>
	</div>

	<?php if ($main['report']) { ?>
		<script type="text/javascript">
			<?php if ($main['report_code']==1) { ?>
				top.ecoder_tree('tree','reload'); // refresh tree ##
        top.ecoder.infodialog('<?php echo $main['report']; ?>'); // show report ##
			<?php } elseif ($main['report_code']==0) { ?>
				ecoder_display('save','block','<?php echo $main['report']; ?>','0');
			<?php } ?>
		</script>
	<?php } ?>
</body>
</html>