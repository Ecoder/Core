<?php

/* rename file of folder */

include "../../code.php"; // single included settings file ##

// trim details ##
@ecoder_request( $_POST['path'], $save['path'], '' ); // path ##
@ecoder_request( $_POST['file'], $save['file'], '' ); // file ##
@ecoder_request( $_POST['file_new'], $save['file_new'], '' ); // file ##
@ecoder_request( $_POST['ext'], $save['ext'], '' ); // extension -- if file ##
@ecoder_request( $_POST['type'], $save['type'], '' ); // type -- file or folder ##

// clean up file name ##
$save['file_clean'] = str_replace ( ".", "xxxdotxxx", $save['file_new'] ); // save dots ##
$save['file_clean'] = ecoder_special_chars ( $save['file_clean'] );  // blitz rest ##
$save['file_clean'] = str_replace ( "xxxdotxxx", ".", $save['file_clean'] ); // replace dots ##
$save['file_new'] = str_replace ( " ", "_", $save['file_clean'] ); // replace space with _ ##

// compile path + file + type(ext) ##
$save['rename'] = $code['root'].$save['path'].$save['file'];
$save['rename_new'] = $code['root'].$save['path'].$save['file_new'];

// settings ##
$save['permissions'] = $code['permissions_dir'];

if ( $save['type'] == 'file' ) { // change permissions ##
    $save['permissions'] = $code['permissions_file'];
    $save['rename'] = $code['root'].$save['path'].$save['file'];
    $save['rename_new'] = $code['root'].$save['path'].$save['file_new'].'.'.$save['ext'];
}

// test ##     
#echo $save['rename'].' | '.$save['rename_new'].'<br />';

// check if original file / folder exists ##
if ( file_exists ( $save['rename'] ) && ( $save['file'] || $save['path'] ) ) {    

    // check if new file / folder exists ##
    if ( !file_exists ( $save['rename_new'] ) && ( $save['file'] || $save['path'] ) ) {    
                  
        ecoder_rename ( $save['rename'], $save['rename_new'], $save['permissions'] ); // rename ##
        $save['result'] = $save['type']." <strong>".$save['file']."</strong> renamed <strong>".$save['file_new'].'.'.$save['ext']."</strong>"; // confirm ##
        $save['result_code'] = 1;
        
    } else { // new name exists ##        
        $save['result'] = 'the '.$save['type'].' <strong>'.$save['file_new'].'.'.$save['ext'].'</strong> already exist, please choose a different name.';
        $save['result_code'] = 0;
    
    } 
        
} else { // error, not found ##
    $save['result'] = 'the '.$save['type'].' <strong>'.$save['file'].'</strong> does not exist, please close the tab and try again.';
    $save['result_code'] = 0;

}

// send to confirm page ##
$save['header'] = "../../edit.php?mode=rename&path=".$save['path']."&type=".$save['type']."&file=".$save['file']."&report=".$save['result'].'&report_code='.$save['result_code'];
#echo 'redirect: '.$save['header'];
header ( "location: ".$save['header'] );

?>
