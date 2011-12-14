<?php
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

function translation_format($str,$args) {
	return preg_replace_callback("({[A-Za-z0-9_]+})",function($matches) use ($args) {
		return $args[trim($matches[0],"{}")];
	},$str);
}

//////////////////////////////////////
//http://stackoverflow.com/questions/834303/php-startswith-and-endswith-functions/860509#860509
function strStartsWith($haystack,$needle,$case=true)
{
   if($case)
       return strpos($haystack, $needle, 0) === 0;

   return stripos($haystack, $needle, 0) === 0;
}

function strEndsWith($haystack,$needle,$case=true)
{
  $expectedPosition = strlen($haystack) - strlen($needle);

  if($case)
      return strrpos($haystack, $needle, 0) === $expectedPosition;

  return strripos($haystack, $needle, 0) === $expectedPosition;
}