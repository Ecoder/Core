<?php

/* 
add individual tracking variable for each tab ##
*/

        echo '
        <script type="text/javascript">
            if ( top === self ) { document.location=\'index.php\'; } // pop back in ##
            var content_changed = 0; // swallow first change ( focus ) to editarea ##
            var content_changed_loop = 0; // from first save, first change is good ##
            var ecoder_hours; var ecoder_minutes; var ecoder_seconds;
            function ecoder_changed () { // iterate & display time of changes ( how to call direct from editarea -- TODO )##
                
                // set time ##
                var ecoder_time = new Date()
                ecoder_hours = ecoder_time.getHours()
                ecoder_minutes = ecoder_time.getMinutes()
                ecoder_seconds = ecoder_time.getSeconds()
                if ( ecoder_hours < 10 ) { ecoder_hours = "0" + ecoder_hours; }
                if ( ecoder_minutes < 10 ) { ecoder_minutes = "0" + ecoder_minutes; }
                if ( ecoder_seconds < 10 ) { ecoder_seconds = "0" + ecoder_seconds; }
                ecoder_changed_time = \'edited: \' + ecoder_hours + \':\' + ecoder_minutes + \':\' + ecoder_seconds; // build message ##
                if ( content_changed > 0 || content_changed_loop == 1 ) { // update ##                  
                    ecoder_display( \'save_changes\', \'block\', ecoder_changed_time, 0 ); // show changes div ##                    
                } 
                content_changed ++; // iterate ##
            
            } 
            var ecoder_autosave = "'.$code['autosave'].'"; // autosave status ##  
            var ecoder_iframe = "'.$main['frame_clean'].'"; // iframe name ## 
            var ecoder_path = "'.$main['path'].'"; // path ##  
            var ecoder_file = "'.$main['file'].'"; // file ##  
            top.ecoder_save_type = "'.$main['save_type'].'"; // declare ##
            addLoadEvent ( ecoder_loaded_edit ); // check for js and close loading screen ##
        </script>';

?>
