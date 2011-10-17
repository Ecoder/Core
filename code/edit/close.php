<?php

/* 
confirm and close tab ##
*/

        if ( $main['report'] && $main['report_code'] == 1 ) { // post submit ##
        
        echo '
        <div id="save_confirm">this tab will close itself now.</div>
        <script type="text/javascript">
        
            // close tab ##
            var ecoder_files;
            var close_tab = setTimeout( "top.ecoder_files( \''.$main['type'].'_file\', \'close\', \'\', \''.$main['type'].'_file\' )", 1000 );

        </script>';
        
        } // update ##

?>
