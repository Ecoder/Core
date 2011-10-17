<?php

/* 
work out file type, assign image and correct link ##
*/

#echo $tree['file_ext']; // test file type ##

// defaults ##
#$tree['file_type'] = 'main.php?path='.$tree['path'].'&file='.$tree_file['file'][$f]; // base link ##

if ( $tree['file_ext'] == 'gif' || $tree['file_ext'] == 'jpg' || $tree['file_ext'] == 'png' ) { // images ##
    $tree['file_ext_clean'] = 'image';
    $tree['file_action'] = 'preview';
    $tree['file_icon'] = 'image';

} elseif ( $tree['file_ext'] == 'swf' ) { // flash ##
    $tree['file_ext_clean'] = 'swf';
    $tree['file_action'] = 'preview';
    $tree['file_icon'] = 'flash';

} elseif ( $tree['file_ext'] == 'html' ) { // html ##
    $tree['file_ext_clean'] = 'html';
    $tree['file_action'] = 'edit';
    $tree['file_icon'] = 'html';

} elseif ( $tree['file_ext'] == 'mp3' ) { // mp3 music ##
    $tree['file_ext_clean'] = 'mp3';
    $tree['file_action'] = 'listen to';
    $tree['file_icon'] = 'mp3';

} elseif ( $tree['file_ext'] == 'zip' || $tree['file_ext'] == 'tar' || $tree['file_ext'] == 'gzip' ) { // tar, gzip, zip ##
    $tree['file_ext_clean'] = 'zip';
    $tree['file_action'] = 'download';
    $tree['file_icon'] = 'zip';
    
} elseif ( $tree['file_ext'] == 'js' ) { // javascript ##
    $tree['file_ext_clean'] = 'js';
    #if ( $code['editor'] == 'edit_area' ) { $tree['file_ext_clean'] = 'js'; } // code_area fix ##
    $tree['file_action'] = 'edit';
    $tree['file_icon'] = 'script'; 

} elseif ( $tree['file_ext'] == 'css' ) { // css ##
    $tree['file_ext_clean'] = 'css';
    $tree['file_action'] = 'edit';
    $tree['file_icon'] = 'css'; 

} elseif ( $tree['file_ext'] == 'txt' || $tree['file_ext'] == 'htaccess' || $tree['file_ext'] == 'ini' ) { // txt ##
    $tree['file_ext_clean'] = 'text';
    $tree['file_action'] = 'edit';
    $tree['file_icon'] = 'text'; 
    
} elseif ( $tree['file_ext'] == 'php' ) { // php ##
    $tree['file_ext_clean'] = 'php';
    $tree['file_action'] = 'edit';
    $tree['file_icon'] = 'php';       

} else { // not known, or not accepted ##
    $tree['blocked'] = 1; // blocked, as unknown ##
    $tree['file_ext_clean'] = '';
    $tree['file_action'] = 'unknown';
    $tree['file_icon'] = 'unknown';

}

?>
