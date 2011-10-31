<div class="dialogcontentwrapper rename" data-json="<?php echo htmlspecialchars(Input::raw(),ENT_QUOTES); ?>">
	<h2>Rename <?php echo $this->path; ?><?php echo $this->name; ?></h2>
	<div id='innercontent'>
		<p id="feedback" class="info">Enter a new name and press save, or cancel by closing the dialog.</p>
		<p>
			<input id="filenewname" type="text" value="" />
			<span>.<?php echo $this->ext; ?></span>
		</p>
	</div>
	<footer>
		<span class="submit button">save</span>
	</footer>
</div>