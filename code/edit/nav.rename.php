<?php

/* 
rename file ##
save file - close tab - open file rename tab ##
*/

        echo '        
        <div class="rename">';
        
        if ( $main['mode'] == 'edit' && $main['shut'] == 1 ) { // file open, active & editable ##
               
            echo '
            <a href="javascript:void(0);" 
                onclick="top.ecoder_files( \'main\', \'rename\', \''.$main['path'].'\', \''.$main['file'].'\', \'file\', content_changed );"  
                title="rename the file '.$main['file'].'">
                <img src="'.$code['skin_path'].'design/icon_rename.png" alt="rename the file '.$main['file'].'" />
            </a>';

        } else { // delete disabled ##

            echo '
            <div class="disabled" title="rename option not available">
                <img src="'.$code['skin_path'].'design/icon_rename_inactive.png" alt="rename option not available" />
            </div>';

        }
        
        echo '    
        </div>';

?>
