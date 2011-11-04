<?php
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>e'.$html['title'].'</title>
    <meta http-equiv="content-Type" content="text/html; charset=UTF-8" />
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js" type="text/javascript"></script>
    ';
    include "css.php"; // browser specific css ##         
    
   /* if ( $save['file_loaded'] == 1 ) { // file loaded, so colour ##
    echo '
    <script src="plug/'.$_SESSION['editor_name'].'/'.$_SESSION['editor_file'].'" type="text/javascript"></script>';
    
    include "code/edit/editarea_ini.php"; 
    
    } // file loaded ##*/
		
    echo '
    <script src="code/base/shortcuts.js" type="text/javascript"></script>
    <script src="code/edit/javascript.js" type="text/javascript"></script>
		<link href="'.$code['skin_path'].'edit.css" rel="stylesheet" type="text/css" media="screen" />';
    include "css.php";
	echo '
        <style type="text/css">
            html { height: 96%; }
            body { margin:0; padding:0; }
        </style>';
	echo '
<script type="text/javascript">
var ecoder_autosave = "'.$code['autosave'].'"; // autosave status ##  
var ecoder_iframe = "'.$main['frame_clean'].'"; // iframe name ## 
var ecoder_path = "'.$main['path'].'"; // path ##  
var ecoder_file = "'.$main['file'].'"; // file ##  
top.ecoder_save_type = "'.$main['save_type'].'"; // declare ##
addLoadEvent ( ecoder_loaded_edit ); // check for js and close loading screen ##
</script>';
	 echo '
    </head>
    <body data-mime="'.$main["cmMime"].'" data-ro="'.(int)$main["isReadOnly"].'">';

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
				  echo '        
        <div class="autosave">';

        if ( $main['auto_save'] == 0 || $main['nav']['save'] == 0 ) { // no auto save option ## 

            echo '
            <div class="disabled">
                <span class="note" title="autosave not available">AUTO</span>
            </div>';

        } elseif ( $main['active'] == 1 ) { // file open and active ##
            
            echo '
            <div class="swap_'.$code['autosave_class'].'" id="swap">
                <a href="javascript:void(0);" 
                onclick="ecoder_autosave_switch();" 
                title="'.$code['autosave_title'].'">AUTO</a>
            </div>';
            
        } // options ##
        
        echo '    
        </div>';

        // javascript swapper ##
        echo '
        <script type="text/javascript">

        // swap autosave status ##
        function ecoder_autosave_switch () {
            
            //alert ( \'status = \'+ ecoder_autosave );
            
            // default ##
            var ecoder_autosave_class = \'swap_on\'; // class on ## 
            var ecoder_autosave_new = 1; // new value ##
            var ecoder_autosave_title = \'turn on autosave feature\';
            
            if ( ecoder_autosave == 1 ) { // already on ##
                var ecoder_autosave_class = \'swap_off\'; // class off ##     
                var ecoder_autosave_new = 0; // new value ##
                var ecoder_autosave_title = \'turn off autosave feature\';
                clearTimeout ( autosave ); // stop autosave script ##
                
            } else { // switching on, so call save function ##
                var autosave = setTimeout( "ecoder_save()", 0 );
                
            }
            
            // switch variable 
            ecoder_autosave = ecoder_autosave_new;
            
            // update button ##
            var ecoder_autosave_id; // declare ##
            ecoder_autosave_id = document.getElementById ( \'swap\' ); // get element ##
            ecoder_autosave_id.className = ecoder_autosave_class; // swap class ##
            ecoder_autosave_id.childNodes[0].title = ecoder_autosave_title; // TODO -- set a title tag ##
            
            //alert ( \'new status = \'+ ecoder_autosave + \' | class = \' +ecoder_autosave_class );
            
        }

        </script>';
				
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
				
				        echo '        
        <div class="delete">';
        
        if ( $main['mode'] == 'edit' && $main['shut'] == 1 ) { // file open, active & editable ##
               
            echo '
            <a href="javascript:void(0);" 
                onclick="top.del(\''.$main['path'].'\', \''.$main['file'].'\', \'file\', content_changed );"  
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
				
	    echo '     
    </div>    
</div>';			
			
			    echo '
			<div class="content_wrapper">
    <div class="edit_area">';
					
					
			/////////////////////CNTENT
					
					echo '
    </div></div>';
					
					if ( $main['report'] ) { // save report ##
        
    echo '
    <script type="text/javascript">';
        
        if ( $main['report_code'] == 1 ) { // ok ##
        
        echo '
        top.ecoder_tree ( \'tree\', \'reload\' ); // refresh tree ##
        top.ecoder_note ( \'note\', \''.$main['report'].'\', \'5\', \'block\' ); // show report ##';
        
        } elseif ( $main['report_code'] == 0 ) { // failed ##

        echo '
        ecoder_display ( \'save\', \'block\', \''.$main['report'].'\', \'0\' );';

        }
        
    echo '    
    </script>';

    #echo '<div id="save_report">'.$main['report'].'</div>';    

} // report filed ##

    // close html ##
    echo '	    
    </body>
</html>';