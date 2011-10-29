<!DOCTYPE html>
<html>
<head>
	<title>e<?php echo $file->name; ?></title>
	<script src="http://code.jquery.com/jquery-1.6.4.min.js" type="text/javascript"></script>
	<link rel="stylesheet" href="plug/codemirror/lib/codemirror.css" />
	<link rel="stylesheet" href="plug/codemirror/theme/default.css" />
	<script src="plug/codemirror/lib/codemirror.js"></script>
	<script src="plug/codemirror/mode/xml/xml.js"></script>
	<script src="plug/codemirror/mode/javascript/javascript.js"></script>
	<script src="plug/codemirror/mode/css/css.js"></script>
	<script src="plug/codemirror/mode/htmlmixed/htmlmixed.js"></script>
	<script src="plug/codemirror/mode/clike/clike.js"></script>
	<script src="plug/codemirror/mode/php/php.js"></script>
	
	<script src="code/base/shortcuts.js" type="text/javascript"></script>
	<script src="code/editor/js.js" type="text/javascript"></script>
	<link href="code/editor/css.css" rel="stylesheet" type="text/css" media="screen" />
	<script type="text/javascript">
		var ecoder_iframe = "<?php echo $main['frame_clean']; ?>"; // iframe name ## 
		var autosave_interval="<?php echo $code['autosave_time']*1000; ?>";
		top.ecoder_save_type = "<?php echo $main['mode']; ?>"; // declare ##
	</script>
</head>
<body class="editor" data-mime="<?php echo $file->cmMime; ?>" data-ro="<?php echo (int)$file->isReadOnly; ?>" data-filename="<?php echo $file->name; ?>" data-path="<?php echo $file->path; ?>">
	<div id="load_edit"><div class="spin">
		<img src="skin/one/design/loading.gif" width="45" height="45" alt="ecoder loading, please wait..." border="0" />
	</div></div>
	
	<ul class="nav">
		<li id="save" data-status="<?php echo (int)(!$file->isReadOnly); ?>" title="<?php echo ($file->isReadOnly ? "file can't be saved": "save file"); ?>"></li>
		<li id="autosave" data-status="<?php echo $file->autosaveStatus; ?>" title="turn <?php echo ($file->autosaveStatus==1 ? "on" : "off"); ?> autosave feature"></li>
		<li id="reload" title="reload file"></li>
		<li id="close" data-status="<?php echo (int)$file->canClose; ?>" title="<?php echo ($file->canClose ? "close file" : "file can't be closed"); ?>"></li>
		<li id="info" titsle="instructions"></li>
		<li id="synhl" data-status="<?php echo (int)$editor->synhl; ?>" title="turn <?php echo ($editor->synhl ? "off" : "on"); ?> syntax highlighting"></li>
		<li id="delete" data-status="<?php echo (int)$file->canDelete; ?>" title="<?php echo ($file->canDelete ? "delete the file" : "delete option not available"); ?>"></li>
		<li id="rename" data-status="<?php echo (int)$file->canRename; ?>" title="<?php echo ($file->canRename ? "rename the file" : "rename option not available"); ?>"></li>
		<li id="search" data-status="-1" title="Search/Replace"></li>
		<li id="undo" data-status="0" title="Undo"></li>
		<li id="redo" data-status="0" title="Redo"></li>
		<li id="jump" title="Jump to line"></li>
		<li id="reindsel" title="Reformat selection"></li>
		<li id="reinddoc" title="Reformat whole document"></li>
	</ul>
	<!--<div class="edit_nav">
		<div class="options">
			<div class="details">
				<?php echo $file->path; ?><strong><?php echo $file->name; ?></strong><?php echo $html['title_note']; ?>
			</div>
			<div id="save"><?php echo $main['nav']['save_note']; ?></div>
			<div id="save_changes"></div>
		</div>
	</div>-->
	
	<div class="content_wrapper">
		<div class="edit_area">
			<textarea id="editarea" name="content" style="width: 100%; height: 100%; padding: 0px; margin: 0px;"><?php echo $file->content; ?></textarea>
		</div>
	</div>
	
	<?php if ($main['report']) { ?>
		<script type="text/javascript">
			<?php if ($main['report_code']==1) { ?>
				top.ecoder_tree('tree','reload'); // refresh tree ##
        top.ecoder_note('note','<?php echo $main['report']; ?>','5','block'); // show report ##
			<?php } elseif ($main['report_code']==0) { ?>
				ecoder_display('save','block','<?php echo $main['report']; ?>','0');
			<?php } ?>
		</script>
	<?php } ?>
</body>
</html>