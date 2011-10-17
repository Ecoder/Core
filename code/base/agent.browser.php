<?php

// browser specific settings 
$browser['happy'] = 0; // unhappy ##
$browser['agent_http'] = $_SERVER['HTTP_USER_AGENT'];

# pretty browser name
if ( stristr( $browser['agent_http'], "firefox" ) ) {
	$browser['agent'] = "ff";
    $browser['agent_title'] = "firefox";
	$browser['happy'] = 1;

} elseif ( preg_match( '/Chrome/', $browser['agent_http'] ) ) { 	
	$browser['agent'] = "chrome"; 
	$browser['agent_title'] = "google chrome"; 

} elseif ( stristr ( $browser['agent_http'], "safari" ) ) {
	$browser['agent'] = "safari";
	$browser['agent_title'] = "apple safari";

} elseif ( stristr ( $browser['agent_http'], "opera" ) ) {
	$browser['agent'] = "opera";
	$browser['agent_title'] = "opera";

} elseif (stristr ( $browser['agent_http'], "msie 6" ) ) {
	$browser['agent'] = "ie6";
	$browser['agent_title'] = "internet explorer 6";

} elseif ( stristr ( $browser['agent_http'], "msie 7") ) {
	$browser['agent'] = "ie7";
	$browser['agent_title'] = "internet explorer 7";
	
} else {
	$browser['agent'] = 0;
	$browser['agent_title'] = "unknown";

}

// test ##
#echo 'your browser is: '.$browser['agent_title'].' | tag: '.$browser['agent'].' | happy '.$browser['happy'].'<br />raw: '.$browser['agent_http'];

?>
