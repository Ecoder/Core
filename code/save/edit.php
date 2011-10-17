<?php

/*
save file ##
*/

include "../../code.php"; // single included settings file ##

// trim details ##
@ecoder_request( $_POST['ecoder_path'], $save['path'], '' ); // path ##
@ecoder_request( $_POST['ecoder_file'], $save['file'], '' ); // file ##
@ecoder_request( $_POST['ecoder_content'], $save['content'], '' ); // content ##

if ( $_SESSION['debug'] == 1 ) { // debugging ##

    #$debug = '<div style="margin: 90px 0 0 0;"><div style="float: left; width: 300px;">POST:<br />'.print_r( $_POST ).'</div><div style="float: left; width: 300px;">POST:<br />'.print_r( $_GET ).'</div><div>';
    #echo $debug;
    
} // debugging ##

// slashes and encoding ##
#$save['content'] = utf8_decode( $save['content'] ); // added for special characters ##
#$save['content'] = stripslashes ( $save['content'] );

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
        
            #file_put_contents ( $save['compiled'], $save['content'] );
            
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

/*
if ( $save['result_code'] == 0 ) { // something went wrong - so tell ##

    echo '
    <script type="text/javascript">
        
        // notify ##
        alert ( "'.$save['result'].'" );
        var e_note = "<p>'.$save['result'].'</p>";
        top.ecoder_note ( \'note\', e_note, \'5\', \'block\' );

    </script>';

}
*/
?>
