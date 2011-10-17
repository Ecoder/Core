<?php

/* 
hot swap options ##
*/
        echo '        
        <div class="editor">';
        
        if ( $main['editor_swap'] == 1 ) { // file open and active ##

            // compile path for editor swap ##
            $editor_swap = 'mode='.$main['mode'].'&file='.$main['file'].'&path='.$main['path'].'&type='.$main['type'].'&shut='.$main['shut'];
        
            if ( $_SESSION['editor'] == 'delux' ) { // offer link to use codepress ##
            
            echo '
            <a href="javascript:void(0);" 
                onclick="top.ecoder_files( \''.$main['frame_clean'].'\', \'editor\', \''.$editor_swap.'\', \''.$main['file'].'\', \'basic\', content_changed )" 
                title="swap to basic editor - loads faster, but less options.">
                <img src="'.$code['skin_path'].'design/icon_editor_delux.png" alt="swap to basic editor - loads faster, but less options" />
            </a>';
            
            } elseif ( $_SESSION['editor'] == 'basic' ) { // offer link to use edit_area ##
            
            echo '
            <a href="javascript:void(0);" 
                onclick="top.ecoder_files( \''.$main['frame_clean'].'\', \'editor\', \''.$editor_swap.'\', \''.$main['file'].'\', \'delux\', content_changed )" 
                title="swap to delux editor - more features, but uses more resources.">
                <img src="'.$code['skin_path'].'design/icon_editor_basic.png" alt="swap to delux editor - more features, but uses more resources" />
            </a>';
            
            } // options ##

        } else { // disabled ##

            echo '
            <div class="disabled" title="editor options not available">
                <img src="'.$code['skin_path'].'design/icon_editor_disabled.png" alt="editor options not available" />
            </div>';

        }
        
        echo '    
        </div>';

?>
