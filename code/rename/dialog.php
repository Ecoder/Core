<div class="dialogcontentwrapper rename">
	<h2><?php echo translation_format($translations->rename->dialogTitle,array("path"=>$this->path,"name"=>$this->name)); ?></h2>
	<div id='innercontent'>
		<p id="feedback" class="info"><?php echo $translations->rename->intro; ?></p>
		<p>
			<input id="filenewname" type="text" value="" />
			<span>.<?php echo $this->ext; ?></span>
		</p>
	</div>
	<footer>
		<span class="submit button"><?php echo $translations->rename->saveButton; ?></span>
	</footer>
</div>