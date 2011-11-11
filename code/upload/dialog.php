<div class="dialogcontentwrapper upload">
	<h2>upload a file</h2>
	<div id='innercontent'>
		<p id="feedback" class="info">press browse and select a file, then press save.</p>
		<p>
			<input type="file" name="file" id="file" />
			<span>.<?php echo $this->ext; ?></span>
		</p>
	</div>
	<footer>
		<span class="submit button"><?php echo $translations->rename->saveButton; ?></span>
	</footer>
</div>