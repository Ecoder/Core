<div class="dialogcontentwrapper add">
	<h2>create a new <?php echo $this->type; ?></h2>
	<div id='innercontent'>
		<p id="feedback" class="info">
			<?php if ($this->type=="file") { ?>
				enter a name, select a file type and press save.
			<?php } else if ($this->type=="folder") { ?>
				enter a name and press save.
			<?php } ?>
		</p>
		<p>
			<input id="nodename" />
			<?php if ($this->type=="file") { ?>
				<span>.</span>
				<select id="ext">
					<?php foreach ($vv->allowedext as $ext) { ?>
						<option value="<?php echo $ext; ?>"><?php echo $ext; ?></option>
					<?php } ?>
				</select>
			<?php } ?>
		</p>
	</div>
	<footer>
		<span class="submit button">save</span>
	</footer>
</div>