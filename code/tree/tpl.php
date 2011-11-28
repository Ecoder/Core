<!DOCTYPE html>
<html>
	<head>
		<title>tree</title>
		<meta http-equiv="content-Type" content="text/html; charset=UTF-8" />
		<script src="http://code.jquery.com/jquery-<?php echo $code['jQuery']; ?>.min.js"></script>
		<link href="skin/one/tree.css" rel="stylesheet" />
		
		<script src="code/base/shortcuts.js"></script>
		<script src="code/tree/javascript.js"></script>
		
		<script>
			if ( top === self ) { document.location='index.php'; } // pop back in if not loaded in iframe ##            
			var ecoder_tree_path = "<?php echo $tree['path']; ?>"; // current path of tree ##
			var ecoder_tree_hidden = "<?php echo $tree['hidden']['switch']; ?>" // hidden files status ##
			var tree_root="<?php echo $tree['root']; ?>";
			var secure_louturl="<?php echo $code['secure_logouturl']; ?>";
			var tree_pathup="<?php echo $tree['path_up']; ?>";
		</script>
	</head>
</body>
<div class="menuoverlay"></div>
<div id="load_tree"><div class="spin">
	<img src="skin/one/design/loading.gif" width="45" height="45" alt="ecoder loading, please wait..." border="0" />
</div></div>
<div class="tree_nav">
	<div class="trail"><?php echo ecoder_short( $tree['path_public'], 32 ); ?></div>
	<div class="click">
		<div id="home" title="return home: <?php echo $tree['root_show']; ?>"></div>
		<div id="fileadd" title="add a new file to <?php echo $tree['path_show']; ?>"></div>
		<div id="folderadd" title="add a new folder to <?php echo $tree['path_show']; ?>"></div>
		<div id="hidden<?php echo $tree['hidden']['icon']; ?>" title="<?php echo $tree['hidden']['title']; ?>"></div>
		<div id="upload" title="file upload"></div>
		<div id="refresh" title="refresh tree list"></div>
		<?php if ($code['secure']==1) { ?>
			<div id="secure" title="logout"></div>
		<?php } ?>
	</div>
</div>
<ul class="nodes">