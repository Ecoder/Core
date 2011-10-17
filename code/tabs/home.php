<?php

/* 
copy over home page, if missing....
*/
if ( !file_exists ( $code['root'].$tabs['home'].".txt" ) ) { // base file not found ##

    // make file ##
    ecoder_copy ( getcwd()."/docs/home.txt", $code['root'].$tabs['home'].".txt", $code['permissions_file'] );
    
    if ( is_writable( $code['root'].$tabs['home'].".txt" ) ) { // can write ##
    
        // add to file ##
        file_put_contents ( $code['root'].$tabs['home'].".txt", $tabs['home_content'] );
        $main['report'] = $tabs['home'].'.txt added';
        $main['report_code'] = 1; // error code for info window ##

    } else { // can't write file ##
        
        $main['report'] = 'error creating home tab';
        $main['report_code'] = 0; // error code for info window ##
        
    }
}

?>
