<?php

/* add file of folder */

include "../../code.php"; // single included settings file ##

// trim details ##
@ecoder_request( $_POST['path'], $save['path'], '' ); // path ##
@ecoder_request( $_POST['file'], $save['file'], '' ); // file ##
@ecoder_request( $_POST['type'], $save['type'], '' ); // type ##

// compile path + file + type(ext) ##
$save['delete'] = $code['root'].$save['path'].$save['file'];
#echo $save['delete'];
     
// check if file / folder exists ##
if ( file_exists ( $save['delete'] ) && ( $save['file'] || $save['path'] ) ) {    
    
    if ( $save['type'] == 'file' ) { // delete file ##
              
        ecoder_delete_file ( $save['delete'] ); // delete ##
        $save['result'] = "file <strong>".$save['file']."</strong> deleted"; // confirm ##
        $save['result_code'] = 1;
        
    } else { // delete directory ##  
    
        // check again if empty ##
        if ( ecoder_check_dir ( $save['delete'] ) === true ) { // empty ##
            
            ecoder_delete_dir ( $save['delete'] ); // delete ##
            $save['result'] = "folder <strong>".$save['file']."</strong> deleted";
            $save['result_code'] = 1;

        } else { // folder has kids ##
        
            $save['result'] = 'you cannot delete the folder <strong>'.$save['file'].'</strong> as it is not empty'; // extend delete note ##
            $save['result_code'] = 0;
            
        }

    }
    
} else { // error, not found ##
    $save['result'] = 'the '.$save['type'].' <strong>'.$save['file'].'</strong> does not exist.';
    $save['result_code'] = 0;
    
}

// send to confirm page ##
$save['header'] = "../../edit.php?mode=delete&path=".$save['path']."&type=".$save['type']."&file=delete ".$save['type']."&report=".$save['result'].'&report_code='.$save['result_code'];
#echo 'redirect: '.$save['header'];
header ( "location: ".$save['header'] );

?>
