<?php

/* 
create backup copy of file ##
*/

// settings ##
$backup['prefix'] = '.'; // default dot ( . ) - make hidden copy on linux ##
$backup['overwrite'] = 1; // 1 = yes || 0 = no // overwrite if backup exists ##

if ( $code['backup'] == 1 ) { // backup turned on ##

    // make copy ##
    if ( file_exists ( $main['content'] ) && $backup['overwrite'] == 0 ) { // backup exists, and overwrite = 0 ##
        
        // TODO ##
        
    } else { // make backup & overwrite if exists ##
    
        ecoder_copy ( $main['content'], $main['content'] = $code['root'].'/'.$main['path'].'/'.$backup['prefix'].$main['file'], $code['permissions_file'] );
    
    }

} // backup switch ##

?>
