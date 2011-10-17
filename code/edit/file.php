<?php

/* 
main editor ##
*/

// default ##
$main['content'] = $code['root'].'/'.$main['path'].'/'.$main['file'];

// check if file and path exists ##
if ( file_exists ( $main['content'] ) ) {
    
    // get contents ##
    $main['content'] = file_get_contents( $main['content'] );

} // exists ##

// build text area ##
#if ( $_SESSION['editor'] == "delux" ) {  // edit_area ##
//$save['contents'] = 'editAreaLoader.getValue( \'editarea\' );'; // function to get textarea contents ## -- 
//$save['contents']="thisTabCodemirror.getValue();";
$save['contents']="function(){}";
echo '
<textarea id="editarea" name="content" style="width: 100%; height: 100%; padding: 0px; margin: 0px;">'.trim ( htmlspecialchars($main['content']) ).'</textarea>';