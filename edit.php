<?php

/* 
stripped down editor ##
*/

include "code.php"; // single included settings file ##    
include "code/edit/logic.php"; // edit logic ##
include "code/tabs/home.php"; // check home tab exists ##
include "code/edit/header.php"; // main header ##

        // hack html height ##
        echo '
        <style type="text/css">
            html { height: 96%; }
            body { margin:0; padding:0; }
        </style>';
            
        // edit - javascript declerations ##
        include "code/edit/javascript.php";
    
    echo '
    </head>
    <body>';

    // loader & javascript test ## 
    include "code/edit/loader.php"; 

    // nav bar ##
    include "code/edit/nav.php";

    echo '
    <div class="edit_area">';
    
        // action area ##
        if ( $main['mode'] == 'edit' || $main['mode'] == 'read' ) { // edit or read file ##
            $main['content'] = $code['root'].'/'.$main['path'].'/'.$main['file']; // path & file name ##
            include "code/edit/file.backup.php"; // create backup copy of file ##
            include "code/edit/file.php"; // get file contents & build editor ##

        } elseif ( $main['mode'] == 'add' ) { // add file or folder ##
            include "code/edit/add.php";

        } elseif ( $main['mode'] == 'delete' ) { // delete file or folder ##
            include "code/edit/delete.php";

        } elseif ( $main['mode'] == 'rename' ) { // rename file or folder ##
            include "code/edit/rename.php";

        } elseif ( $main['mode'] == 'upload' ) { // upload files ##
            include "code/edit/upload.php";

        } // main options ##
        
    echo '
    </div>';

    // save report ##
    include "code/edit/report.php";

include "code/base/footer.php"; // html footer ##

?>
