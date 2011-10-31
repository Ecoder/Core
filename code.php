<?php

// server settings -- REQUIRED -- also contains some advanced configuration options ##
include "code/base/server.php";

// MOST IMPORTANT SETTING -- path to editable files ##
$code['root'] = 'D:/dev/ecoder/testingdata/'; // full path, with trailing slash ##
if ( $_SESSION['live'] == 0 ) { // running locally ##
    $code['root'] = 'D:/dev/ecoder/testingdata/'; // local path -- for testing ##     
} 
$code['domain_cookie'] = ".gmeditor.com"; // domain name ##

/* other advanced settings ## */
$code['editor'] = 'delux'; // basic ( no colours ) || delux ( highlight + plugins ) ##
$code['skin'] = 'one'; // design ##
$code['autosave'] = 0; // disabled on load, can be switched on in each doc. ##
$code['autosave_time'] = 10; // delay in seconds ##
$code['backup'] = 1; // 1 = on || 0 = off -- create backup copy of files opened ( see code/edit/file.backup.php ) ##

/* system settings, changeable, but will not change how the system works */
#$code['domain'] = "http://www.gmeditor.com/"; // domain name ##
$code['name'] = "ecoder"; // system name ##
$code['version'] = "v 0.5.0m1"; // system version ##
//Version keepers
$code['jQuery']="1.6.4";
$code['codemirror']="2.16";
$code['jQ_json']="2.3";

// security settings ##
$code['secure'] = 0; // 0 = not secured || 1 = secured, uses settings below ##
$code['secure_variable'] = 'login_security'; // if isset indicates login active ## 
$code['secure_url'] = 'http://www.gmeditor.com/user/login/?pass=ecoder'; // full url to login area -- ecoder variable allows return link ## 
$code['secure_logouturl']='http://example.com/logout/'; //Full url to logout page
if ( $_SESSION['live'] == 0 ) { $code['secure_url'] = '/loveunit/greenmedia.es/go/1/user/login/?pass=ecoder'; } // local path -- for testing ##
$code['secure_root'] = 1; // 1 || 0 - use varible root - passed in session variable $_SESSION['tree_root'] ##

// home tab settings ##
$tabs['home'] = 'home'; // title of home tab ##
$tabs['home_content'] = 'you can make notes in this file & it also acts as the home page when you close other files.'; // text added to home page doc ##

// array of allowed file types in tree ##
$_SESSION['tree_file_types'] = array( "php", "js", "html", "css", "txt", "htaccess", "ini" );

// array of banned file types for upload ##
$_SESSION['upload_banned'] = array( "exe" );

// array of file names to block in tree ##
$_SESSION['tree_file_ignore'] = array ( ".ftpquota" );

// array of directories to ignore in tree ##
$_SESSION['tree_dir_ignore'] = array( ".", "..", ".files", ".snap", "logic", "cpanel", "ftp", "00", "01" );

// error logging -- includes a php script that can be cronned ##
// note you'll also need to update the path in the .htaccess file in the root directory ##
$dbug['error_path'] = '/home/ecoder/code/logs/error.log'; // full path to error log file ##
$dbug['error_email'] = 'log@website.com'; // email log errors ##

include "code/base/logic.php"; // apply settings ##
include "code/base/functions.php"; // php functions ## 
include "code/base/secure.php"; // secure system ##
include "code/base/editor.php"; // editor hot swapper ## 
include "code/base/controller.php";
include "code/base/io.php";

$i=Input::_get();