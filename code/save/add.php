<?php

/* add file of folder */

include "../../code.php"; // single included settings file ##

// trim details ##
@ecoder_request( $_POST['path'], $save['path'], '' ); // path ##
@ecoder_request( $_POST['file'], $save['file'], '' ); // file ##
@ecoder_request( $_POST['type'], $save['type'], '' ); // type ##

// settings ##
$save['mode'] = 'folder'; // default ##
$save['permissions'] = $code['permissions_dir'];

// clean up file name ##
$save['file_clean'] = str_replace ( ".", "xxxdotxxx", $save['file'] ); // save dots ##
$save['file_clean'] = ecoder_special_chars ( $save['file_clean'] );  // blitz rest ##
$save['file_clean'] = str_replace ( "xxxdotxxx", ".", $save['file_clean'] ); // replace dots ##
$save['file_clean'] = str_replace ( " ", "_", $save['file_clean'] ); // replace space with _ ##

// compile path + file + type(ext) ##
$save['new'] = $code['root'].$save['path'].$save['file_clean'];

if ( $save['type'] ) { // add extension if passed ##
    $save['new'] .= '.'.$save['type'];
    $save['mode'] = 'file';
    $save['permissions'] = $code['permissions_file'];
}

// clean frame name replace / with _ in file path ##
$main['frame_clean'] = str_replace ( "/", "_", $save['path'] );
$main['frame_clean'] .= 'add_'.$save['mode']; // for close and refresh

$save['result_file'] = $save['file_clean']; // results file ##
if ( $save['mode'] == 'file' ) { $save['result_file'] = $save['file_clean'].".".$save['type']; } // add extension ##
     
// check if file / folder exists already ##
if ( !file_exists ( $save['new'] ) ) {    
    
    if ( $save['mode'] == 'file' ) { // add file ##
        
        // check if template exists ##
        $save['template'] = 'template.'.$save['type'];
        if ( !file_exists ( $save['template'] ) ) { // template not found ##               
            $save['result'] = 'the file type <strong>'.$save['type'].'</strong> is not allowed.';
            $save['result_code'] = 0;
            
        } else { // template found ##        
            ecoder_copy ( $save['template'], $save['new'], $save['permissions'] ); // make new file ##
            $save['result'] = "the file <strong>".$save['result_file']."</strong> has been added to the folder <strong>".$save['path']."</strong>";
            $save['result_code'] = 1;
            
        } // template found
        
    } else { // make directory ##  
        ecoder_mkdir ( $save['new'], $save['permissions'] );       
        $save['result'] = "the folder <strong>".$save['result_file']."</strong> has been added to the folder <strong>".$save['path']."</strong>";
        $save['result_code'] = 1;
        
    }
    
} else { // error, already exists ##
    $save['result'] = 'a '.$save['mode'].' named <strong>'.$save['result_file'].'</strong> already exists in the folder <strong>'.$save['path'].'</strong>.';
    $save['result_code'] = 0;
    
}

// send to confirm page ##
$save['header'] = "../../edit.php?mode=add&path=".$save['path']."&type=".$save['mode']."&report=".$save['result'].'&report_code='.$save['result_code'];
header ( "location: ".$save['header'] );

?>
