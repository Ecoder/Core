<?php

/* 
displays available files and folders
*/

include "code.php"; // single included settings file ##
include "code/tree/header.php"; // html header, branding ##
include "code/tree/logic.php"; // logic ##
    
    // hack html height ##
    echo '
    <style type="text/css">
        html { height: 0; }
    </style>
    
    </head>
<body>';
    
// loader & javascript test ## 
include "code/tree/loader.php"; 

// javascript declerations ##
include "code/tree/javascript.php";

// tree top navigation ##
include "code/tree/nav.php";

// count menu items ##
$tree['count'] = 0; // start at the start ##

echo '
<div class="tree">
    <ul class="ul_tree">';

// check path exists ##
if ( $tree['error'] == 1 ) { // folder not found ## 
   
        echo '
        <li>
            <a 
            style="background-image: url('.$code['skin_path'].'design/icon_error.png);"
            href="javascript:void(0);" 
            title="go home: '.$tree['root'].'"
            onclick="top.ecoder_tree( \'tree\', \'home\', \''.$tree['root'].'\' );">
                error! check settings.
            </a>
        </li>';
    
} else { // path OK ##
    
    if ( $tree['path_up_ok'] == 1 ) { // link back up ##    
    
        echo '
        <li>
            <a 
            style="background-image: url('.$code['skin_path'].'design/icon_up.png);"
            href="javascript:void(0);" 
            title="go back up a level: '.$tree['path_up'].'"
            onclick="top.ecoder_tree( \'tree\', \'up\', \''.$tree['path_up'].'\' );">
                up
            </a>
        </li>';
        #echo $path_up[0];
    
    } // up ##
    
    // dir loop ##
    $tree_dir = ecoder_tree( $code['root'].$tree['path'], 1, $_SESSION['tree_hidden'] ); // call tree function ##
    #print '<pre>'.print_r( $tree_dir['file'] ,1 ).'</pre>';

    // loop ##
    if ( isset ( $tree_dir['file'] )) {
    foreach ( $tree_dir['file'] as $d => $value ) {
    $tree['count']++; // iterate ##

        // assign folder name ##
        $tree['file_name'] = $tree_dir['file'][$d];

        // check folder permissions ##
        $tree['file_permissions'] = $tree_dir['permissions'][$d]; // permissions ##
        $tree['file_size'] = $tree_dir['size'][$d]; // size ##
        if ( $tree['file_size'] == '' ) { $tree['file_size'] = "0"; }
        $tree['file_date'] = $tree_dir['date'][$d]; // date ##
        $tree['file_type'] = 'folder'; // file or folder ##
        $tree['file_icon'] = 'folder'; // icon ##
        include "code/tree/permissions.php";

        echo '
        <li>
        
            <div class="file">
                <a 
                style="background-image: url('.$code['skin_path'].'design/icon_'.$tree['file_icon'].'.png);"
                href="javascript:void(0);" 
                title="open folder '.$tree_dir['file'][$d].'"
                onclick="top.ecoder_tree( \'tree\', \'open\', \''.$tree['path'].$tree_dir['file_path'][$d].'\' );">
                    '.ecoder_short( $tree_dir['file'][$d], 24 ).'
                </a>
            </div>';
                        
            // pop out menu ##  
            include "code/tree/menu.php";
                        
        echo '
        </li>';    
        
    }}
    
    // file loop ##
    $tree_file = ecoder_tree( $code['root'].$tree['path'], 0, $_SESSION['tree_hidden'] ); // call tree function ##
    #print '<pre>'.print_r( $tree_file['file'] ,1 ).'</pre>';
    
    // loop ##
    if ( isset ( $tree_file['file'] )) {
    foreach ( $tree_file['file'] as $f => $value ) {
    $tree['count']++; // iterate ##
        
        // assign file name ##
        $tree['file_name'] = $tree_file['file'][$f];
        
        // work out icon and link ##
        $tree['file_ext'] = $tree_file['ext'][$f]; // assign to variable ##
        $tree['blocked'] = 0; // unblocked ##
        include "code/tree/type.php";

        // check file permissions ##
        $tree['file_permissions'] = $tree_file['permissions'][$f]; // permissions ##
        $tree['file_size'] = $tree_file['size'][$f]; // size ##
        if ( $tree['file_size'] == '' ) { $tree['file_size'] = "0"; } // fill gap -- not sure needed ##
        $tree['file_date'] = $tree_file['date'][$f]; // date ##
        $tree['file_type'] = 'file'; // file or folder ##
        include "code/tree/permissions.php";
        
        if ( $tree['blocked'] == 1 ) { // unknown file type ##

        echo '
        <li style="background: url('.$code['skin_path'].'design/icon_'.$tree['file_icon'].'.png) no-repeat 5px 5px;">
            '.ecoder_short($tree['file_name'], 20).' [UNKNOWN]
        </li>'; 

        } else { // editing ok ##

        echo '
        <li class="'.$tree['class_li'].'">    
                
            <div class="file">
                <a 
                style="background-image: url('.$code['skin_path'].'design/icon_'.$tree['file_icon'].'.png);"
                href="javascript:void(0);" 
                title="'.$tree['file_action'].' '.$tree['file_name'].'"            
                onclick="top.ecoder_files( \'main\', \''.$tree['editable'].'\', \''.$tree['path'].'\', \''.$tree['file_name'].'\', \''.$tree['file_ext_clean'].'\' );">
                    '.ecoder_short($tree['file_name'], 22).'
                </a>
            </div>';
                        
            // pop out menu ##  
            include "code/tree/menu.php";
                    
        echo '                   
        </li>';  

        }        
    }}
}

    echo '
    </ul>
</div>';

include "code/base/footer.php"; // html footer ##

?>
