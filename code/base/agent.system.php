<?php

/* 
get info about operating system ##
*/

// browser specific settings 
$system['happy'] = 0; // unhappy ##
$system['agent_http'] = $_SERVER['HTTP_USER_AGENT'];

// browser specific settings ( linux, mac, windows )
if ( stristr( $system['agent_http'], "windows" ) ) {
    $system['happy'] = 1; // happy ##
    $system['agent'] = 'windows'; // windows ##

} elseif ( stristr( $system['agent_http'], "linux" ) ) {
    $system['happy'] = 1; // happy ##
    $system['agent'] = 'linux'; // linux ##

} elseif ( stristr( $system['agent_http'], "macintosh" ) ) {
    $system['happy'] = 1; // happy ##
    $system['agent'] = 'mac'; // linux ##
    
} else {
    $system['agent'] = 'unknown'; // what are you using? ##    

}

?>
