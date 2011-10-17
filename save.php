<?php
echo '<script type="text/javascript">

// global variables ##
var ecoder_path = \''.$main['path'].'\';
var ecoder_file = \''.$main['file'].'\';
var ecoder_save_on = \''.$main['save'].'\'; // use save function ##
var debug = '.$_SESSION['debug'].';
var ecoder_content = '.$save['contents'].';
var ec_save_target="'.$save['target'].'";
var ec_autosave_time='.$code['autosave_time'].'000;
var ec_main_type="'.$main['type'].'";
var ec_html_title="'.$html['title'].'";
';

if ( $main['mode'] == 'edit' ) { // file ##
	echo 'function ecoder_save() { ecoder_save_edit(); }';
} elseif ( $main['mode'] == 'add' ) { // add ##
	echo 'function ecoder_save() { ecoder_save_add(); }';
} elseif ( $main['mode'] == 'delete' ) { // delete ##
	echo 'function ecoder_save() { ecoder_save_delete(); }';
} elseif ( $main['mode'] == 'rename' ) { // rename ##
	echo 'function ecoder_save() { ecoder_save_rename(); }';
} elseif ( $main['mode'] == 'upload' ) { // upload ##
	echo 'function ecoder_save() { ecoder_save_upload(); }';
} // save types ##

echo '</script>
<script type="text/javascript" src="code/save/saveactions.js"></script>';