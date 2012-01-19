<?php
include "code/base/server.php";

// MOST IMPORTANT SETTING -- path to editable files ##
$code['root'] = 'D:/dev/ecoder/testingdata/'; // full path, with trailing slash ##
if (!$live) { // running locally ##
    $code['root'] = 'D:/dev/ecoder/testingdata/'; // local path -- for testing ##
}
$code['domain_cookie'] = ".gmeditor.com"; // domain name ##
$code['autosave']=0; // 0 for disabled, otherwise time in seconds

$code['version'] = "v0.5.0-b1";
$code['lang']="en";
$code['jQuery']="1.7.1";
$code['codemirror']="2.2";

// security settings ##
$code['secure'] = 0; // 0 = not secured || 1 = secured, uses settings below ##
$code['secure_variable'] = 'login_security'; // if isset indicates login active ##
$code['secure_url'] = 'http://www.gmeditor.com/user/login/?pass=ecoder'; // full url to login area -- ecoder variable allows return link ##
$code['secure_logouturl']='http://example.com/logout/'; //Full url to logout page
if (!$live) { $code['secure_url'] = '/loveunit/greenmedia.es/go/1/user/login/?pass=ecoder'; } // local path -- for testing ##
$code['secure_root'] = 1; // 1 || 0 - use varible root - passed in session variable $_SESSION['tree_root'] ##

if ( $code['secure'] == 1 ) { // secured ##
	if ( !isset( $_SESSION[$code['secure_variable']] ) || $_SESSION[$code['secure_variable']] == 0 ) { // check for login variable ##
		echo '<script type="text/javascript">top.location.href = \''.$code['secure_url'].'\';</script>'; // kick-out ##
	}
	if ( $code['secure_root'] == 1 && isset( $_SESSION['tree_root'] ) ) { // assign session to root variable ##
		$code['root'] = $_SESSION['tree_root']; // passed full path to editable root ##
	}
}

$cnf['uploadWhitelist'] = array( "php", "js", "html", "css", "txt", "htaccess", "ini" );
$cnf['showHidden']=false; //Show hidden files in tree?

include "code/base/io.php";
include "code/filemanipulation.php";
include "code/tree.php";
include "code/env.php";

$i=Input::_get();