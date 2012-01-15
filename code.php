<?php

// server settings -- REQUIRED -- also contains some advanced configuration options ##
include "code/base/server.php";

// MOST IMPORTANT SETTING -- path to editable files ##
$code['root'] = 'D:/dev/ecoder/testingdata/'; // full path, with trailing slash ##
if ( $_SESSION['live'] == 0 ) { // running locally ##
    $code['root'] = 'D:/dev/ecoder/testingdata/'; // local path -- for testing ##
}
$code['domain_cookie'] = ".gmeditor.com"; // domain name ##
$code['autosave']=0; // 0 for disabled, otherwise time in seconds

$code['version'] = "v0.5.0-dev";
$code['lang']="en";
$code['jQuery']="1.7.1";
$code['codemirror']="2.2";

// security settings ##
$code['secure'] = 0; // 0 = not secured || 1 = secured, uses settings below ##
$code['secure_variable'] = 'login_security'; // if isset indicates login active ##
$code['secure_url'] = 'http://www.gmeditor.com/user/login/?pass=ecoder'; // full url to login area -- ecoder variable allows return link ##
$code['secure_logouturl']='http://example.com/logout/'; //Full url to logout page
if ( $_SESSION['live'] == 0 ) { $code['secure_url'] = '/loveunit/greenmedia.es/go/1/user/login/?pass=ecoder'; } // local path -- for testing ##
$code['secure_root'] = 1; // 1 || 0 - use varible root - passed in session variable $_SESSION['tree_root'] ##

if ( $code['secure'] == 1 ) { // secured ##
	if ( !isset( $_SESSION[$code['secure_variable']] ) || $_SESSION[$code['secure_variable']] == 0 ) { // check for login variable ##
		echo '<script type="text/javascript">top.location.href = \''.$code['secure_url'].'\';</script>'; // kick-out ##
	}
	if ( $code['secure_root'] == 1 && isset( $_SESSION['tree_root'] ) ) { // assign session to root variable ##
		$code['root'] = $_SESSION['tree_root']; // passed full path to editable root ##
	}
}

// array of allowed file types in tree ##
$_SESSION['tree_file_types'] = array( "php", "js", "html", "css", "txt", "htaccess", "ini" );

// array of banned file types for upload ##
$_SESSION['upload_banned'] = array( "exe" );

// array of file names to block in tree ##
$_SESSION['tree_file_ignore'] = array ( ".ftpquota" );

// array of directories to ignore in tree ##
$_SESSION['tree_dir_ignore'] = array( ".", "..", ".files", ".snap", "logic", "cpanel", "ftp", "00", "01" );

$cnf['showHidden']=false; //Temp.. tree: show hidden files?

include "code/base/io.php";
include "code/filemanipulation.php";
include "code/tree.php";
include "code/env.php";

$i=Input::_get();