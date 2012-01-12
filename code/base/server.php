<?php

/* get and set server settings ## */
session_start(); // start session ##

// get server settings ##
$gm_server_http_host = strip_tags(htmlentities($_SERVER['HTTP_HOST'])); // server ##
$gm_server_php_self = strip_tags(htmlentities($_SERVER['PHP_SELF'])); // php self ##

$code=array("domain_cookie"=>""); //Fix notice. Crappy code(tm)
// local / live checker ##
if ( strstr ( $gm_server_http_host, "localhost" ) == TRUE ) { // localhost settings

    $_SESSION['live'] = 0; // live or local ##
    ini_set( "session.cookie_domain", "" ); // cookie domain ##

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

    // permissions for file and folder creation ##
    $code['permissions_file'] = 0744; // file ##
    $code['permissions_dir'] = 0755; // dir ##
}

setlocale(LC_ALL, 'gbr_GBR'); // server locale ##

$garbage_timeout = 3*60*60; // 3 hours -- garbage collection timeout ##
ini_set( 'session.gc_maxlifetime', $garbage_timeout );

$session_expire = 60*60*24*100; // 100 days - cookie expiration ##
session_set_cookie_params( $session_expire );