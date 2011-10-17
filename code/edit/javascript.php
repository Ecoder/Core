<?php

/* 
add individual tracking variable for each tab ##
*/
echo '
<script type="text/javascript">
var ecoder_autosave = "'.$code['autosave'].'"; // autosave status ##  
var ecoder_iframe = "'.$main['frame_clean'].'"; // iframe name ## 
var ecoder_path = "'.$main['path'].'"; // path ##  
var ecoder_file = "'.$main['file'].'"; // file ##  
top.ecoder_save_type = "'.$main['save_type'].'"; // declare ##
addLoadEvent ( ecoder_loaded_edit ); // check for js and close loading screen ##
</script>';