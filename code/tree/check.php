<?php

/* 
first run script - checks for session and a .txt file - if not found, runs script ##
*/

// settings ##
$tree['error'] = 0; // all good ##
$check['path'] = $code['root'].$tree['path']; // folder to test ##
$check['file'] = '.ecoder_permissions_check.txt'; // make unique file name ##
$check['message'] = 'this file was created on '.$global_date_cute.' at '.$global_time_cute.', after '.$code['name'].' checked the permissions of the folder '.$check['path'].'.';

// check if path exists ##
if ( !file_exists ( $code['root'].$tree['path'] ) ) { 

    $tree['error'] = 1; // path does not exist ##
    $check['report'] = '<p>the folder <strong>'.$check['path'].'</strong> does not exist.</p><p>configuration changes can be made to the file <strong>/code.php</strong></p>';  // msg ##  

} else { // folder exists, so check permissions ##

    if ( !file_exists ( $check['path'].$check['file'] ) ) { // run checks, if check file not found ##

        // create check file ##
        ecoder_copy ( getcwd().'/docs/home.txt', $check['path'].$check['file'], $code['permissions_file'] );
        
        // now check if copied ##
        if ( file_exists ( $check['path'].$check['file'] ) ) {
            
            if ( is_writable( $check['path'].$check['file'] ) ) { // can write ##
                
                $tree['error'] = 2; // can write ##
                $check['report'] = '<p>permissions for the folder <strong>'.$check['path'].'</strong> seem good.</p><p>configuration changes can be made to the file <strong>/code.php</strong></p>';  // error msg ##
                
                // add note to file ##
                file_put_contents ( $check['path'].$check['file'], $check['message'] );

                // now delete ##
                #ecoder_delete_file ( $check['path'].$check['file'] );

                    // now try and delete ##
                    #if ( !file_exists ( $check['path'].$check['file'] ) ) {
                    
                    #$check['report'] = '<p>permissions for the folder <strong>'.$check['path'].'</strong> seem good.</p><p>configuration changes can be made to the file <strong>/code.php</strong></p>';  // error msg ##
                    
                    #}

            } else { // can't write file ##
                
                $tree['error'] = 3; // no write permission ##
                $check['report'] = '<p>ecoder does not have write permission to files in <strong>'.$check['path'].'</strong></p><p>configuration changes can be made to the file <strong>/code.php</strong></p>';  // msg ##  
                
            } // write ##
        
        } else { // not copied ##
        
            $tree['error'] = 4; // no write permission ##
            $check['report'] = '<p>ecoder does not have write permission to the folder <strong>'.$check['path'].'</strong></p><p>configuration changes can be made to the file <strong>/code.php</strong></p>';  // msg ##      
        
        } // copied or not ##

    } // test file not found ##

} // file exists ##

if ( $tree['error'] > 0 ) { // error found, so report ##
    
    echo '
    <script type="text/javascript">
        
        // notify ##
        top.ecoder_note ( \'note\', \''.$check['report'].'\', \'5\', \'block\' );

    </script>';

}

?>
