<?php

/* main edit nav ## */

echo ' 
<div class="edit_nav">        
    <div class="options">       
        <div id="save_'.$main['nav']['save'].'">';
            if ( $main['nav']['save'] == 1 ) { // file loaded ##
            echo '
            <a href="javascript:void(0);" 
            onclick="ecoder_save();" 
            title="save '.$html['title'].'">SAVE</a>';
            } else { // no file loaded ##
            echo '<span title="file cannot be saved">SAVE</span>';
            }        
        echo '           
        </div>';        
        
        // editor autosave switch ##
        include "nav.autosave.php";
        
        echo '
        <div id="reload_'.$main['nav']['reload'].'">';
            if ( $main['nav']['reload'] == 1 ) { // file loaded, offer reload ##
            echo '
            <a href="javascript:void(0);" 
            onclick="top.ecoder_files( \''.$main['frame_clean'].'\', \'reload\', \''.$main['mode'].'\', \''.$main['file'].'\', \'\', content_changed )" 
            title="reload '.$html['title'].'">RELOAD</a>';
            } else { // no file loaded ##
            echo '<span title="file cannot be reloaded">RELOAD</span>';
            }        
        echo '    
        </div>
        <div id="close_'.$main['nav']['close'].'">';
            if ( $main['nav']['close'] == 1 ) { // file loaded ##
            echo '
            <a href="javascript:void(0);"             
            onclick="top.ecoder_files( \''.$main['frame_clean'].'\', \'close\', \''.$main['path'].'\', \''.$main['file'].'\', \'\', content_changed );"
            title="close '.$html['title'].'">CLOSE</a>';
            } else { // no file loaded ## 
            echo '<span title="file cannot be closed">CLOSE</span>';
            }        
        echo '
        </div>
        
        <div class="details">
            '.$main['path'].'<strong>'.$html['title'].'</strong>'.$html['title_note'].'
        </div>
				
				<div id="save">'.$main['nav']['save_note'].'</div>
					
				<div id="save_changes"></div>
				
				<div class="logo">
            <a href="javascript:void(0);" onclick="top.ecoder_loaded_base( \'block\' );" title="'.$code['name'].' instructions">
                <img src="'.$code['skin_path'].'design/icon_ecoder.png" alt="'.$code['name'].' instructions" />
            </a>
        </div>';
                
        // editor swapper ##
        include "nav.editor.php";

        // delete option ##
        include "nav.delete.php";

        // rename option ##
        include "nav.rename.php";

        // file info ##
        #include "nav.info.php";

    echo '     
    </div>    
</div>';

?>
