<div class="dialogcontentwrapper upload">
	<h2>upload a file</h2>
	<div id='innercontent'>
		<p id="feedback" class="info">press browse and select a file, then press save.</p>
		<p>
			<input type="file" name="file" id="file" multiple />
		</p>
		<div id="files">
			<h3>File list</h3>
			<a id="reset" href="#" title="Remove all files from list">Clear list</a>
			<ul id="fileList"></ul>
			<a id="upload" href="#" title="Upload all files in list">Upload files</a>
		</div>
	</div>
	<footer>
		<span class="submit button"><?php echo $translations->upload->savebtn; ?></span>
	</footer>
</div>