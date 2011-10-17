<?php

/* 
add new file ##
path known, so just need to add name and select type from list ( use array from settings ) ##
auto save and return to edit file ##
*/

// options ##
if ( $main['type'] == 'file' ) { // add file ##
    include "add.file.php";

} elseif ( $main['type'] == 'folder' ) { // add folder ## 
    include "add.folder.php";

}

// include save script ##
$save['target'] = 'code/save/add.php';
if ( $main['nav']['save'] == 1 ) { // save active ## 
    include "save.php"; 
}


?>
