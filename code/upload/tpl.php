<!DOCTYPE html>
<html>
	<head>
		<title>eupload file</title>
		<meta http-equiv="content-Type" content="text/html; charset=UTF-8" />
		<script src="http://code.jquery.com/jquery-<?php echo $code['jQuery']; ?>.min.js"></script>
		<script src="code/base/shortcuts.js"></script>
    <script src="code/upload/upload.js"></script>
		<link href="code/upload/upload.css" rel="stylesheet" />
		<script>
			var ecoder_autosave = "<?php echo $code['autosave']; ?>"; // autosave status ##  
			var ecoder_iframe = "<?php echo $main['frame_clean']; ?>"; // iframe name ## 
			var ecoder_path = "<?php echo $main['path']; ?>"; // path ##  
			var ecoder_file = "upload file"; // file ##  
			top.ecoder_save_type = "upload"; // declare ##
			var ecoder_save_on = '0'; // use save function ##
		</script>
	</head>
	<body>
		<div class="edit_nav">
			<div class="options">
				<div id="save_1"><a href="javascript:void(0);" onclick="ecoder_save();" title="save upload file">SAVE</a></div>
				<div id="close_1">
					<a href="javascript:void(0);" onclick="top.ecoder_files(ecoder_iframe,'close',ecoder_path,'upload file','',content_changed);" title="close upload file">CLOSE</a>
				</div>
				<div class="details"><?php echo $main['path']; ?><strong>upload file</strong></div>
				<div id="save">select a file to upload</div>
				<div id="save_changes"></div>
				<div class="logo">
					<a href="javascript:void(0);" onclick="top.ecoder_loaded_base('block');" title="<?php echo $code['name']; ?> instructions">
						<img src="skin/one/design/icon_ecoder.png" alt="<?php echo $code['name']; ?> instructions" />
					</a>
				</div>
			</div>
		</div>
		<div class="content_wrapper">
			<div class="edit_area">
				<div class="edit_form">
					<form name="upload" action="code/upload/save.php" method="post" enctype="multipart/form-data">        
						<input type="hidden" name="path" value="<?php echo $main['path']; ?>" />
						<h1>upload a file</h1>
						<p>press browse and select a file, then press save.</p>
						<?php if ($main['report']) { ?>
							<div id="save_confirm"><?php echo $main['report']; ?> - this tab will close itself now.</div>
							<script type="text/javascript">
								//var fn=top.ecoder_files('file_file','close','','file_file');
								//setTimeout(fn,1000);
							</script>
						<?php } ?>
						<div class="filename"><input type="file" name="file" id="file" /></div> 	
					</form>
				</div>
			</div>
		</div>
		<?php if ($main['report']) { ?>
			<script>
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