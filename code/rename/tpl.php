<!DOCTYPE html>
<html>
	<head>
		<title>e<?php echo $html['title']; ?></title>
		<meta http-equiv="content-Type" content="text/html; charset=UTF-8" />
		<script src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
		<script src="code/base/shortcuts.js"></script>
		
		<script>
			var ecoder_iframe="<?php echo $main['frame_clean']; ?>";
			var ecoder_path="<?php echo $main['path']; ?>";
			var ecoder_file="<?php echo $main['file']; ?>";
			var ecoder_type="<?php echo $main['type']; ?>";
			var ec_html_title="<?php echo $html['title']; ?>";
			var ec_ext="<?php echo $main['file_ext']; ?>";
			top.ecoder_save_type="rename";
		</script>
		
		<script src="code/rename/js.js"></script>
		<link href="code/rename/css.css" rel="stylesheet" />
	</head>
	<body>
		<div id="load_edit">
			<div class="spin">
				<img src="skin/one/design/loading.gif" width="45" height="45" alt="ecoder loading, please wait..." border="0">
			</div>
		</div>
		
		<div class="edit_nav">
			<div class="options">
				<div id="savebtn">
					<span title="save <?php echo $html['title']; ?>">SAVE</span>
				</div>
				<div id="close">
					<span title="close<?php echo $html['title']; ?>">CLOSE</span>
				</div>
				<div class="details"><?php echo $main['path']; ?><strong><?php echo $html['title']; ?></strong></div>
				<div id="save">enter the new <?php echo $main['type']; ?> name</div>
				<div id="save_changes"></div>
				<div class="logo">
					<span title="<?php echo $code['name']; ?> instructions">
						<img src="skin/one/design/icon_ecoder.png" alt="<?php echo $code['name']; ?> instructions" />
					</span>
				</div>
			</div>
		</div>
		
		<div class="content_wrapper">
			<div class="edit_area">
				<div class="edit_form">
					<p id="feedback"></p>
					<h1>rename <?php echo $main['type']; ?></h1>

					<div class="filename"><input id="filenewname" type="text" class="text" name="file_new" value="" /></div>
					<div class="dot">.</div>
					<div class="type_rename"><?php echo $main['file_ext']; ?></div>
				</div>
			</div>
		</div>
	</body>
</html>