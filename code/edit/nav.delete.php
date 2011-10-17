<?php

/* 
delete file ##
save file - close tab - open file deletion tab ##
*/

        echo '        
        <div class="delete">';
        
        if ( $main['mode'] == 'edit' && $main['shut'] == 1 ) { // file open, active & editable ##
               
            echo '
            <a href="javascript:void(0);" 
                onclick="top.ecoder_files( \'main\', \'delete\', \''.$main['path'].'\', \''.$main['file'].'\', \'file\', content_changed );"  
                title="delete the file '.$main['file'].'">
                <img src="'.$code['skin_path'].'design/icon_delete.png" alt="delete the file '.$main['file'].'" />
            </a>';

        } else { // delete disabled ##

            echo '
            <div class="disabled" title="delete option not available">
                <img src="'.$code['skin_path'].'design/icon_delete_inactive.png" alt="delete option not available" />
            </div>';

        }
        
        echo '    
        </div>';

?>
