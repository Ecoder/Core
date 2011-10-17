<?php

/* 
file info ##
click to open full screen info about file...
*/

        echo '        
        <div class="info">';
        
        if ( $main['active'] == 1 ) { // file open ##
               
            echo '
            <a href="javascript:void(0);" 
                onclick="top.ecoder_files( \'main\', \'delete_flip\', \''.$main['path'].'\', \''.$main['file'].'\', \'file\', content_changed );"  
                title="info about the file '.$main['file'].'">
                <img src="'.$code['skin_path'].'design/icon_info.png" alt="info about the file '.$main['file'].'" />
            </a>';

        } else { // info unavailable ##

            echo '
            <div class="disabled" title="info option not available">
                <img src="'.$code['skin_path'].'design/icon_info_inactive.png" alt="info option not available" />
            </div>';

        }
        
        echo '    
        </div>';

?>
