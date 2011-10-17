<?php

/* 
menu items ##
*/

// settings ##
$tree['menu_position'] = 'top: 10px;';
if ( $tree['count'] > 9 ) { 
    #$tree['menu_position'] = 'top: -102px;'; // files ##
    #if ( $tree['file_type'] == 'folder' ) { 
        $tree['menu_position'] = 'top: -158px;'; // give more space for folders ## 
    #}
}

// use context menu ##
if ( $tree['menu'] == 1 ) {

                echo '
                <div class="menu">
                    <ul id="item"> 
                        <li>
                            <a class="trigger" href="#" title="open file menu">
                                <img src="'.$code['skin_path'].'design/icon_menu.png" border="0" alt="menu" />
                            </a>
                            <ul style="'.$tree['menu_position'].'">
                                <li>
                                    <a 
                                        href="#" 
                                        title="full path: /'.$tree['path'].$tree['file_name'].'"
                                        style="background-image: url('.$code['skin_path'].'design/icon_'.$tree['file_icon'].'.png)">
                                        <strong>'.ecoder_short( $tree['file_name'], 20 ).'</strong>
                                    </a>   
                                </li>';
                                
                                #if ( $tree['file_type'] == 'folder' ) { // folder only options ##
                                
                                echo '
                                <li>
                                    <a 
                                        title="'.$tree['delete_note'].'"';                                        
                                        if ( $tree['delete'] == 1 ) { // ok to delete ##
                                        echo '
                                        style="background-image: url('.$code['skin_path'].'design/icon_delete'.$tree['delete_icon'].'.png);"
                                        href="javascript:void(0);" onclick="'.$tree['delete_link'].'"'; 
                                        
                                        } else { // no delete ##
                                        echo '
                                        class="disabled"
                                        style="background-image: url('.$code['skin_path'].'design/icon_delete'.$tree['delete_icon'].'.png);"
                                        href="#"'; 
                                        
                                        } echo '>
                                          '.$tree['delete_title'].'
                                    </a>                
                                </li>
                                <li>
                                    <a 
                                        title="'.$tree['rename_note'].'"';                                        
                                        if ( $tree['rename'] == 1 ) { // ok to rename ##
                                        echo '
                                        style="background-image: url('.$code['skin_path'].'design/icon_rename'.$tree['rename_icon'].'.png);"
                                        href="javascript:void(0);" onclick="'.$tree['rename_link'].'"'; 
                                        
                                        } else { // no rename ##
                                        echo '
                                        class="disabled"
                                        style="background-image: url('.$code['skin_path'].'design/icon_rename'.$tree['rename_icon'].'.png);"
                                        href="#"'; 
                                        
                                        } echo '>
                                          '.$tree['rename_title'].'
                                    </a>              
                                </li>';
                                
                                #} // folder options ##
                                
                                echo '
                                <li>
                                    <a 
                                        href="#" 
                                        title="file permissions"
                                        style="background-image: url('.$code['skin_path'].'design/icon_locked.png)">
                                        permissions: '.$tree['file_permissions'].'
                                    </a>                
                                </li>
                                <li>
                                    <a 
                                        href="#" 
                                        title="'.$tree['file_type'].' size"
                                        style="background-image: url('.$code['skin_path'].'design/icon_size.png)">
                                        size: '.$tree['file_size'].'
                                    </a>              
                                </li>
                                <li>
                                    <a 
                                        href="#" 
                                        title="file modified date"
                                        style="background-image: url('.$code['skin_path'].'design/icon_date.png)">
                                        '.date ("d M Y - H:i:s", $tree['file_date']).'
                                    </a>   
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>';
                
} // menu ##                

?>
