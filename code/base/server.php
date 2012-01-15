<?php
session_start();
$gm_server_http_host=strip_tags(htmlentities($_SERVER['HTTP_HOST']));
$live=true;
$code=array("domain_cookie"=>""); //Fix notice. Crappy code(tm)

if (strstr($gm_server_http_host,"localhost")==TRUE) {
    $live=false;
    ini_set( "session.cookie_domain", "" );

    $code['permissions_file'] = 0777;
    $code['permissions_dir'] = 0777;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    ini_set( "session.cookie_domain", $code['domain_cookie'] );

    $code['permissions_file'] = 0744;
    $code['permissions_dir'] = 0755;
}

setlocale(LC_ALL, 'gbr_GBR');

$garbage_timeout = 3*60*60; // 3 hours -- garbage collection timeout ##
ini_set( 'session.gc_maxlifetime', $garbage_timeout );

$session_expire = 60*60*24*100; // 100 days - cookie expiration ##
session_set_cookie_params( $session_expire );