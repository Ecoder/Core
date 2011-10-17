<?php

/* 
main page - include all items, in iframes ##
*/

include "code.php"; // single included settings file ##
include "code/base/header.php"; // html header, branding ##

    // build main structure ##
    echo '
    </head>
    <body>';
    
    // test if javascript enabled ##
    include "code/base/loader.php"; 
        
        echo '
        <div id="left">
            <iframe name="tree" id="iframe_tree" src="tree.php"></iframe>
        </div>

        <div id="content">';

            // include required tabs mark-up ##
            include "code/tabs/build.php";                
            
        echo '
        </div>';
            
    include "code/tabs/init.php"; // build tabs ##

    // notes and messages ##
    include "code/base/notes.php";

include "code/base/footer.php"; // html footer ##

?>
