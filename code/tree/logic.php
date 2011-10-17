<?php

/* 
file settings, including securing the tree ##
*/

// folders to tree ##
$tree['path'] = ''; // nada ##
@ecoder_request( $_GET['path'], $tree['path_trim'], '' ); // trimmed path ##
$tree['path_up_ok'] = 0; // NO ##

if ( $tree['path_trim'] ) { // allow path to be passed in string ##

    // add to root path ##
    $tree['path'] = $tree['path_trim'];

    // update public path ##
    $tree['path_array'] = explode( "/", $tree['path_trim'] ); // break trimmed path into array at '/' ##
    #print '<br /><br /><pre>'.print_r( $tree['path_array'] ,1 ).'</pre><br />'.$tree['path'];
    
    // update path open ##
    if ( substr( $tree['path'], -1 ) != '/') $tree['path'] .= '/'; // add trailing slash to path ##
    
    // check and build path up ##
    #echo '<br><br><br >';
    $tree['path_up'] = ecoder_split_right( "/" , $tree['path'], 3 );  
    #print '<pre>'.print_r( $tree['path_up'] ,1 ).'</pre>';
    $tree['path_up'] = $tree['path_up'][0]; // take first ( last ) item from array ##
    if ( substr( $tree['path_up'], -1 ) != '/' ) $tree['path_up'] .= '/'; // add trailing slash to path ##    
    if ( $tree['path_up'] == $tree['path_trim'] ) { $tree['path_up'] = ''; }
    #echo $tree['path_up'];
    $tree['path_up_ok'] = 1; // GOOD ##
   
}

// path public ##
$tree['path_public_prefix'] = ecoder_split_right( "/" , $code['root'], 3 ); // take a number of folders from root - to give better location info ##
#print '<pre>'.print_r( $tree['path_public_prefix'] ,1 ).'</pre>';
$tree['path_public'] = $tree['path_public_prefix'][1].'/'.$tree['path'];
if ( substr( $tree['path_public'], -1 ) != '/') $tree['path_public'] .= '/'; // add trailing slash to path ##

// hidden files ##
if ( !isset ( $_SESSION['tree_hidden'] ) ) { $_SESSION['tree_hidden'] == 0; } // default to hidden ##
@ecoder_request( $_GET['hidden'], $trim['hidden'], $_SESSION['tree_hidden'] ); // hidden files ##
$tree['hidden']['status'] = $trim['hidden']; // session changed from tree nav ##
if ( $tree['hidden']['status'] == 0 ) { // not shown ##
    
    $_SESSION['tree_hidden'] = 0;
    $tree['hidden']['switch'] = 1; // switch shows ##
    $tree['hidden']['icon'] = '_inactive'; // inactive icon ##
    $tree['hidden']['title'] = 'show hidden files'; // offer to show ##
    
} elseif ( $tree['hidden']['status'] == 1 ) { // shown ## {
    
    $_SESSION['tree_hidden'] = 1;    
    $tree['hidden']['switch'] = 0; 
    $tree['hidden']['icon'] = ''; // active icon ##
    $tree['hidden']['title'] = 'hide hidden files'; // offer to hide ##

}

// menu ##
if ( $browser['agent'] == 'ie6' || $browser['agent'] == 'ie7' ) { $tree['menu'] = 0; } // hide from IE for now ##

// check given folder ##
include "code/tree/check.php";

?>
