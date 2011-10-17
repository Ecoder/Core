<?php

/* 
location and tree options - add, refresh - order? ##
*/

// settings ##
$tree['path_show'] = $tree['path'];
$tree['root_show'] = $tree['root'];
if ( $tree['root_show'] == "" ) { $tree['root_show'] = "/"; } // fill display gap ##
if ( $tree['path_show'] == "" ) { $tree['path_show'] = "/"; } // fill display gap ##

// where are we ##
echo '
<div class="tree_nav">

    <div class="trail">
        '.ecoder_short( $tree['path_public'], 32 ).'
    </div>
    <div class="click">    
        <div class="icon">
            <a href="javascript:void(0);" 
            onclick="top.ecoder_tree( \'tree\', \'home\', \''.$tree['root'].'\' );" 
            title="return home: '.$tree['root_show'].'">
                <img src="'.$code['skin_path'].'design/icon_home.png" border="0" />
            </a>
        </div>
        <div class="icon">
            <a href="javascript:void(0);" 
            onclick="top.ecoder_files( \'main\', \'add\', \''.$tree['path'].'\', \'\', \'file\' );" 
            title="add a new file to '.$tree['path_show'].'">
                <img src="'.$code['skin_path'].'design/icon_file_add.png" border="0" />
            </a>
        </div>
        <div class="icon">
            <a href="javascript:void(0);" 
            onclick="top.ecoder_files( \'main\', \'add\', \''.$tree['path'].'\', \'\', \'folder\' );" 
            title="add a new folder to '.$tree['path_show'].'">
                <img src="'.$code['skin_path'].'design/icon_folder_add.png" border="0" />
            </a>
        </div>
        <div class="icon">
            <a href="javascript:void(0);" 
            onclick="top.ecoder_tree( \'tree\', \'hidden\', \''.$tree['path'].'\', \''.$tree['hidden']['switch'].'\' );" 
            title="'.$tree['hidden']['title'].'">
                <img src="'.$code['skin_path'].'design/icon_hidden'.$tree['hidden']['icon'].'.png" border="0" />
            </a>
        </div>
        <div class="icon">
            <a href="javascript:void(0);" 
            onclick="top.ecoder_files( \'main\', \'upload\', \''.$tree['path'].'\', \'\', \'file\' );" 
            title="file upload">
                <img src="'.$code['skin_path'].'design/icon_upload.png" border="0" />
            </a>
        </div>
        <div class="icon">
            <a href="javascript:void(0);" 
            onclick="top.ecoder_tree( \'tree\', \'reload\' );" 
            title="refresh tree list">
                <img src="'.$code['skin_path'].'design/icon_refresh.png" border="0" />
            </a>
        </div>
				';
				if ($code['secure']==1) {
				echo ' <script>function logout(){top.location="'.$code['secure_logouturl'].'";}</script>
				 <div class="icon">
						 <a href="javascript:void(0);"
						 onclick="logout();"
						 title="logout">
								 <img src="'.$code['skin_path'].'design/icon_logout.png" border="0" />
						 </a>
				 </div>';
			}
			 echo '
    </div>

</div>';

?>
