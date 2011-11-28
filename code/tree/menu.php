<ul class="menu">
	<li id="<?php echo $node->action; ?>" class="default"><?php echo $node->action; ?></li>
	<li 
		id="delete" 
		data-status="<?php echo (int)$node->delete; ?>"
		onclick="top.del(tree_path,'<?php echo $node->name; ?>','<?php echo $node->type; ?>');">
		<?php echo ($node->delete ? "delete item" : "delete disabled"); ?>
	</li>
	<li 
		id="rename" 
		data-status="<?php echo (int)$node->rename; ?>"
		onclick="top.ecoder_files('main','rename',tree_path,'<?php echo $node->name; ?>','<?php echo $node->type; ?>');">
		<?php echo ($node->rename ? "rename item" : "rename disabled"); ?>
	</li>
</ul>