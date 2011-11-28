<?php

/* 
file settings, including securing the tree ##
*/

// folders to tree ##
$tree['path'] = ''; // nada ##
@ecoder_request( $_GET['path'], $tree['path_trim'], '' ); // trimmed path ##
$tree['path_up_ok'] = false;
$tree['path_up']='';

if ( $tree['path_trim'] ) { // allow path to be passed in string ##

    // add to root path ##
    $tree['path'] = $tree['path_trim'];

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
    $tree['path_up_ok'] = true;
}

// path public ##
$rootsplit=ecoder_split_right( "/" , $code['root'], 3 ); // take a number of folders from root - to give better location info ##
$tree['path_public']=$rootsplit[1].'/'.$tree['path'];
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

// check given folder ##
include "code/tree/check.php";


// settings ##
$tree['path_show'] = $tree['path'];
$tree['root_show'] = $tree['root'];
if ( $tree['root_show'] == "" ) { $tree['root_show'] = "/"; } // fill display gap ##
if ( $tree['path_show'] == "" ) { $tree['path_show'] = "/"; } // fill display gap ##

/// scan directory ##
function ecoder_tree ( $scan_path, $scan_dirs = 0, $hidden, $recurse = 0, $tree=array() ){
    // only include files with these extensions
    $allow_extensions = $_SESSION['tree_file_types'];
    
    // make any specific files you wish to be excluded
    $ignore_files = $_SESSION['tree_file_ignore'];
    $ignore_regex = '/^_/';
    
    // skip these directories
    $ignore_dirs = $_SESSION['tree_dir_ignore'];
    
    // hidden files & folders ##
    $hidden_stuff = '.'; // first char . ##
    if ( $hidden == 1 ) { $hidden_stuff = ""; }
    
    // scan away ##
    $scan = scandir( $scan_path );
    foreach ( $scan as $k => $content ) {

    // build path ##
    $path = $scan_path.'/'.$content;
      
    // file ##      
    if ( is_file ( $path ) && is_readable ( $path ) && $scan_dirs == 0 ) {     
        if ( $content[0] != $hidden_stuff ) {   
            if ( !in_array ( $content, $ignore_files ) ) { // skip ignored files
                if ( preg_match ( $ignore_regex, $content ) == 0 ) {                
                    $content_chunks = explode( ".", $content );
                    $ext = $content_chunks[count( $content_chunks ) - 1];            
                    if ( in_array( $ext, $allow_extensions ) ) { // only include files with desired extensions
                        $path_array = explode ( "/", $path ); // break into array ##
                        $tree['file'][] = end ( $path_array ); // save file ##
                        $path_array_ext = explode ( ".", end ( $path_array ) ); // get extension ##
                        $tree['ext'][] = end ( $path_array_ext );      
                        $tree['permissions'][] = substr ( sprintf ( '%o', fileperms( $path ) ), -4 ); // get permissions ##
                        $tree['date'][] = filemtime ( $path ); // get file date ##                
                        $tree['size'][] = ecoder_filesize ( filesize ( $path ) ); // get filesize in byte ##
                    }                
                }
            }
        }
    }
      
    // directory ##
    elseif ( is_dir ( $path ) && is_readable ( $path ) && $scan_dirs == 1 ) {    
        if ( $content[0] != $hidden_stuff ) {     
            if ( !in_array ( $content, $ignore_dirs ) ) { // skip any ignored dirs 
                $path_array_dir = explode( '/', $path ); // build array ##
                $tree['file'][] = end ( $path_array_dir );  // directory name ##
                $tree['file_path'][] = end ( $path_array_dir ).'/';  // directory name with seperator ##
                $tree['permissions'][] = substr ( sprintf ( '%o', fileperms( $path ) ), -4 ); // get permissions ##
                // get date ##
                $tree['date'][] = filemtime ( $path.'/.' ); // get file date ##    
                // get size ##
                $tree['size'][] = ecoder_filesize ( ecoder_dirsize ( $path ) ); // get filesize in byte ##

                // recursive callback to open new directory ##
                if ( $recurse == 1 ) { // go back again ##
                    $tree = ecoder_tree( $path, $scan_dirs, $hidden, $recurse, $tree );
                } 
            }
        }
    }

    }
    return $tree;
}

function tempRefactorOutput($input,$isDir) {
	global $code,$tree;
	$output=array();
	if (empty($input['file'])) { return $output; }
	foreach ($input['file'] as $k => $v) {
		$x=new stdClass();
		$x->name=$input['file'][$k];
		$x->perm=$input['permissions'][$k];
		$x->size=$input['size'][$k];
		if ($x->size=="") {
			$x->size=0;
		}
		$x->date=$input['date'][$k];
		$x->type='folder';
		$x->icon='folder';
		$x->blocked=false;
		if (!$isDir) {
			$x->ext=$input['ext'][$k];
			switch ($x->ext) {
				case 'gif':
				case 'jpg':
				case 'png':
					$x->extc='image';
					$x->action='preview';
					$x->icon='image';
					break;
				case 'swf':
					$x->extc='swf';
					$x->action='preview';
					$x->icon='flash';
					break;
				case 'html':
					$x->extc='html';
					$x->action='edit';
					$x->icon='html';
					break;
				case 'mp3':
					$x->extc="mp3";
					$x->action="listen to";
					$x->icon="mp3";
					break;
				case "zip":
				case "tar":
				case "gzip":
					$x->extc="zip";
					$x->action="download";
					$x->icon="zip";
					break;
				case "js":
					$x->extc="js";
					$x->action="edit";
					$x->icon="script";
					break;
				case "css":
					$x->extc="css";
					$x->action="edit";
					$x->icon="css";
					break;
				case "txt":
				case "htaccess":
				case "ini":
					$x->extc="text";
					$x->action="edit";
					$x->icon="text";
					break;
				case "php":
					$x->extc="php";
					$x->action="edit";
					$x->icon="php";
					break;
				default:
					$x->blocked=true;
					$x->extc="";
					$x->action='unknown';
					$x->icon="unkno<n";
					break;
			}
			$x->type='file';
		}
		
		$x->editable="read";
		$x->delete=false;
		$x->rename=false;
		$x->class_li="no-read";
		$x->action="open";
		if (is_readable($code['root'].$tree['path'].'/'.$x->name)) {
			$x->class_li="read";
		}
		
		if (is_writable($code['root'].$tree['path'].'/'.$x->name)) {
			$x->class_li="";
			$x->editable="edit";
			if ($x->type=="file") {
				$x->delete=true;
				$x->rename=true;
			} else if ($x->type=="folder") {
				if (ecoder_check_dir($code['root'].$tree['path'].'/'.$x->name)===true) {
					//TODO Still can't delete non-empty dirs atm. Fix!!
					$x->delete=true;
					$x->rename=true;
				}
			}
		}
		
		$output[]=$x;
		clearstatcache();
	}
	
	return $output;
}