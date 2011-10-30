<!DOCTYPE html>
<html>
	<head>
		<title>e<?php echo $this->name; ?></title>
		<meta http-equiv="content-Type" content="text/html; charset=UTF-8" />
		<script src="http://code.jquery.com/jquery-<?php echo $code['jQuery']; ?>.min.js"></script>
		<script src="plug/jq_json-<?php echo $code['jQ_json']; ?>.min.js"></script>
		<script src="code/base/shortcuts.js"></script>
		
		<script>
			var ecoder_iframe="<?php echo $vv->fframename; ?>";
			var ecoder_path="<?php echo $this->path; ?>";
			var ecoder_file="<?php echo $vv->pframename; ?>";
			var ecoder_type="<?php echo $this->type; ?>";
			var ec_html_title="<?php echo $this->name; ?>";
			var ec_ext="<?php echo $this->ext; ?>";
			top.ecoder_save_type="rename";
		</script>
		
		<script src="code/rename/js.js"></script>
		<link href="code/rename/css.css" rel="stylesheet" />
	</head>
	<body>
		<div class="edit_nav">
			<div class="options">
				<div id="savebtn">
					<span title="save <?php echo $this->name; ?>">SAVE</span>
				</div>
				<div id="close">
					<span title="close <?php echo $this->name; ?>">CLOSE</span>
				</div>
				<div class="details"><?php echo $this->path; ?><strong><?php echo $this->name; ?></strong></div>
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
					<h1>rename <?php echo $this->type; ?></h1>

					<div class="filename"><input id="filenewname" type="text" class="text" name="file_new" value="" /></div>
					<div class="dot">.</div>
					<div class="type_rename"><?php echo $this->ext; ?></div>
				</div>
			</div>
		</div>
	</body>
</html>