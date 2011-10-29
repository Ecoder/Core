<?php

/*
save file ##
*/

include "../../code.php"; // single included settings file ##

// trim details ##
@ecoder_request( $_POST['ecoder_path'], $save['path'], '' ); // path ##
@ecoder_request( $_POST['ecoder_file'], $save['file'], '' ); // file ##
@ecoder_request( $_POST['ecoder_content'], $save['content'], '' ); // content ##

if (get_magic_quotes_gpc()) {
    $save['content'] = stripslashes ( $save['content'] );
}

// compile file path ##
$save['compiled'] = $code['root'].$save['path'].$save['file'];

// does file exists ##
if ( file_exists ( $save['compiled'] ) ) {    
    
    // is it a file ##
    if ( is_file( $save['compiled'] ) ) {
    
        if ( is_writable( $save['compiled'] ) ) {
            
            // special characters and replace function ##
            function fpc( $file, $contents ){
                return fwrite ( fopen ( $file, 'w' ), $contents );
            }
            fpc( $save['compiled'], $save['content'] );
            
            $save['result'] = $save['file'].' saved successfully';
            $save['result_code'] = 1;

        } else { // not writable ##

        // not a file ##
        $save['result'] = $save['file'].' is not writable';
        $save['result_code'] = 0;

        }
    
    } else {
        
        // not a file ##
        $save['result'] = $save['file'].' is not a file';
        $save['result_code'] = 0;
    
    }
    
} else { // perhaps file deleted while being edited -- TODO ##
    
    // does not exist ##
    $save['result'] = 'the file <strong>'.$save['file'].'</strong> does not exist.</p><p>perhaps it has recently been deleted or renamed.';
    $save['result_code'] = 0;

    // add file ##
    
    // put contents ##

}