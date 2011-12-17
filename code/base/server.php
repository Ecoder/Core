<?php

/* get and set server settings ## */
session_start(); // start session ##

// get server settings ##
$gm_server_http_host = strip_tags(htmlentities($_SERVER['HTTP_HOST'])); // server ##
$gm_server_php_self = strip_tags(htmlentities($_SERVER['PHP_SELF'])); // php self ##
$gm_server_http_host_self = $gm_server_http_host.$gm_server_php_self; // host & self -- complete url minus http:// ##
$gm_server_request_uri = strip_tags(htmlentities($_SERVER["REQUEST_URI"])); // request URI #
$gm_server_script_name = strip_tags(htmlentities($_SERVER["SCRIPT_NAME"])); // script name #
$gm_server_script_filename = basename(strip_tags(htmlentities($_SERVER['SCRIPT_FILENAME']))); // just the file being served
$browser_IP = 'UNKNOWN'; if ( isset ( $_SERVER['REMOTE_ADDR'] ) ) { $browser_IP = $_SERVER['REMOTE_ADDR']; } // IP ##
$browser_referer = 'UNKNOWN'; if ( isset ( $_SERVER['HTTP_REFERER'] ) ) { $browser_referer = $_SERVER['HTTP_REFERER']; } // referer ##
$browser_agent = 'UNKNOWN'; if ( isset ( $_SERVER['HTTP_USER_AGENT'] ) ) { $browser_agent = $_SERVER['HTTP_USER_AGENT']; } // user agent ##

$code=array("domain_cookie"=>""); //Fix notice. Crappy code(tm)
// local / live checker ##
if ( strstr ( $gm_server_http_host, "localhost" ) == TRUE ) { // localhost settings

    $_SESSION['live'] = 0; // live or local ##
    ini_set( "session.cookie_domain", "" ); // cookie domain ##
    $_SESSION['debug'] = 0; // debug ## TODO
    $_SESSION['debug_functions'] = 0; // debug php functions ## TODO

    // permissions for file and folder creation ##
    $code['permissions_file'] = 0777; // file ##
    $code['permissions_dir'] = 0777; // dir ##

    // show all errors ##
    #if ( $code['debug'] == 1 ) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    #}

} else { // live ##

    $_SESSION['live'] = 1; // live or local ##
    ini_set( "session.cookie_domain", $code['domain_cookie'] ); // cookie domain ##
    $_SESSION['debug'] = 0; // debug ## TODO
    $_SESSION['debug_functions'] = 0; // debug php functions ## TODO

    // permissions for file and folder creation ##
    $code['permissions_file'] = 0744; // file ##
    $code['permissions_dir'] = 0755; // dir ##

    // show all errors ##
    if ( $_SESSION['debug'] == 1 ) {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
    }

}

setlocale(LC_ALL, 'gbr_GBR'); // server locale ##
$global_date = date("Y-m-d H:i:s"); //  mySQL formatted date ##
$global_date_cute = date('D\. dS F Y'); // cute date format ##
$global_time_cute = date('H:i:s'); // cute time format ##
$cookie_expire = time()+60*60*24*365; // cookie expire time ##

$garbage_timeout = 3*60*60; // 3 hours -- garbage collection timeout ##
ini_set( 'session.gc_maxlifetime', $garbage_timeout );

$session_expire = 60*60*24*100; // 100 days - cookie expiration ##
session_set_cookie_params( $session_expire );