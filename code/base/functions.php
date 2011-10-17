<?php

/* 
included functions ##
*/

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

/* usage
$ecoder_tree_file = GM_tree( $code['root'].$path, 0, 0 );
*/

#######################################

// clean frame name made from path & file with dots and /'s replaced ##
function ecoder_iframe_clean ( $iframe ) {
    $iframe_clean = str_replace ( "/", "_", $iframe );
    $iframe_clean = str_replace ( ".", "_", $iframe_clean );
	return $iframe_clean;
}

/* usage
$variable = ecoder_iframe_clean( $s );
*/


##################################################

// truncate function ##
function ecoder_short ( $text, $numb ) {
	$text = html_entity_decode($text, ENT_QUOTES); // take #
	if (strlen($text) > $numb) {
		$half = round(($numb/2)-1); #echo $half; 
		$start = trim(substr($text, 0, $half)); #echo $part_1;
		$end = trim(substr($text, -$half)); #echo $part_2;
		$text = trim($start.'...'.$end);	
	} 
	$text = htmlentities($text, ENT_QUOTES); // return ##
    return $text;
}

/* usage
ecoder_short( $text, 75 );
*/

##################################################

// search for files within a directory ##
function ecoder_check_dir ( $folder ) {
    $c = 0;
    if ( is_dir ( $folder ) ){
        $files = opendir ( $folder );
        while ( $file = readdir ( $files ) ) { $c++; }
        if ( $c > 2 ) {
            return false;
        } else {
            return true;
        }
    }       
}

##################################################

// get directory size ##
function ecoder_dirsize ( $dirname ) {
    if (!is_dir($dirname) || !is_readable($dirname)) {
        return false;        
    }
    $dirname_stack[] = $dirname;
    $size = 0;
    do {
        $dirname = array_shift($dirname_stack);
        $handle = opendir($dirname);
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..' && is_readable($dirname . DIRECTORY_SEPARATOR . $file)) {
                if (is_dir($dirname . DIRECTORY_SEPARATOR . $file)) {
                    $dirname_stack[] = $dirname . DIRECTORY_SEPARATOR . $file;
                }
                $size += filesize($dirname . DIRECTORY_SEPARATOR . $file);
            }
        }
        closedir($handle);
    } while ( count ( $dirname_stack ) > 0 );
    //echo 'directory '.$size;
    return $size;
}

##################################################

// format file sizes ##
function ecoder_filesize ( $size, $retstring = null ) {
   // adapted from code at http://aidanlister.com/repos/v/function.size_readable.php ##
   $sizes = array( 'B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB' );
   if ($retstring === null) { $retstring = '%01.2f %s'; }
   $lastsizestring = end($sizes);
   foreach ($sizes as $sizestring) {
           if ($size < 1024) { break; }
           if ($sizestring != $lastsizestring) { $size /= 1024; }
   }
   if ($sizestring == $sizes[0]) { $retstring = '%01d %s'; } // Bytes aren't normally fractional
   return sprintf($retstring, $size, $sizestring);
}

##################################################

// avoid isset issues ##
function ecoder_request ( $source, &$dest, $default = null ) {
	if ( isset( $source ) )
		$dest = $source;
	else
		$dest = $default;
}

/* usage
@ecoder_request( $_GET['myMode'], $myMode, 0 );
*/

######################################################

// function to make files ##
function ecoder_copy ( $from, $to, $mode ) {
	if ( @copy ( $from, $to ) ) {
		chmod ( $to, octdec( $mode ) );
		touch ( $to, filemtime( $from ) ); // to track last modified time
		ecoder_echo ( '', '', $from.' copied to: '.$to.' | permissions:'. $mode.'' );
	} else {
		ecoder_echo ( '', '', 'cannot copy '.$from.' to '.$to.' | permissions:'. $mode.'' );
	}
}

##################################################

// function to make recursive directories ##
function ecoder_mkdir ( $path, $mode, $way = '' ) {
	umask(0);
	$exp = explode( "/", $path );
	foreach( $exp as $n ) {
		$way .= $n.'/';
		if( !file_exists( $way ) ) mkdir( $way, octdec($mode) );
	}
    ecoder_echo ( '', '', 'directory '.$path.' created | permissions:'. $mode.'' );
}

##################################################

// delete file, avoiding permission issues ##
function ecoder_delete_file ( $path ) {
	if ( file_exists ( $path ) ) {
		@unlink ( $path );
		ecoder_echo( '', '', $path.' ~ file deleted.' );
	} else {
		ecoder_echo( '', '', $path.' ~ does not exist.' );
	}	
}

/* usage - 
ecoder_empty ( $path );
*/

##################################################

// delete contents and then directory ##
function ecoder_delete_dir ( $dir ) {
   if( !$dh = @opendir( $dir ) ) return;
   while ( false !== ( $obj = readdir( $dh ) ) ) {
       if( $obj == '.' || $obj == '..' ) continue;
       if ( !@unlink( $dir.'/'.$obj ) ) ecoder_delete_dir ( $dir.'/'.$obj );
   }
   rmdir( $dir );
   ecoder_echo ('', '', $dir.' ~ deleted.' );
}

/* usage - 
ecoder_empty ( $path );
*/

##################################################

function ecoder_rename ( $from, $to, $mode ) {
	if ( @rename ( $from, $to ) ) {
		//chmod ( $to, octdec( $mode ) ); // TODO -- possibly ##
		//touch ( $to, filemtime( $from ) ); // to track last modified time
		ecoder_echo ( '', '', $from.' ~ renamed: '.$to.' | permissions:'. $mode.'' );
	} else {
		ecoder_echo ( '', '', 'cannot rename '.$from.' to '.$to.' | permissions:'. $mode.'' );
	}
}

#######################################

// remove special characters ## TODO - white list, include ' -, _, space' ##
function ecoder_special_chars ( $GM_chars_in ) {
	if ( $GM_chars_in ) {
		$GM_chars_out = preg_replace('/[^\x30-\x39\x41-\x5a\x61-\x7a\xc0-\xf6]/', '_', $GM_chars_in);
	}		
	return $GM_chars_out;
}

/* usage
$variable = GM_special_chars($s);
*/

######################################################

// split right ##
function ecoder_split_right ( $pattern, $input, $len=0 ) {
	if ($len==0) return explode($pattern,$input);

	$tempInput=array_reverse(explode($pattern,$input));
	$tempArray=array();
	$ArrayIndex=$indexCount=0;
	foreach ($tempInput as $values) {
		if ($indexCount<$len) {
			$tempArray[$ArrayIndex]=$values;
			if ($indexCount<$len-1) $ArrayIndex++;
		} else {
			$tempArray[$ArrayIndex]=$values.$pattern.$tempArray[$ArrayIndex];
		}
		$indexCount++;
	}
	return array_reverse($tempArray);
}

#######################################

// function to echo variable, with formatting & comment ##
function ecoder_echo ( $GM_e, $GM_e_name, $GM_e_comment='' ) {
	if ($GM_e) {
		$GM_e_output = "$GM_e_name = <strong>$GM_e</strong>; ";
	} else { $GM_e_output = '';	
	}
	if ($GM_e_comment) {
		$GM_e_output_comment = $GM_e_comment;
		if ($GM_e) { $GM_e_output_comment = ' ------- '.$GM_e_output_comment; } // add filler if comment and variables to be shown ##
	} else { $GM_e_output_comment = '';
	}
	if ( $_SESSION['debug_functions'] == 1 ) {
		echo "<p style='background: #dddddd; color: #000000; padding: 4px;'>$GM_e_output $GM_e_output_comment</p>";
	}
}

/* usage
GM_e($variable,'$variable','this is my comment');
*/

?>
