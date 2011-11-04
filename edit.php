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
    <body data-mime="'.$main["cmMime"].'" data-ro="'.(int)$main["isReadOnly"].'">';

    // loader & javascript test ## 
    include "code/edit/loader.php"; 

    // nav bar ##
    include "code/edit/nav.php";

    echo '
			<div class="content_wrapper">
    <div class="edit_area">';
    
        // action area ##
        if ( $main['mode'] == 'upload' ) { // upload files ##
            include "code/edit/upload.php";

        } // main options ##
				
				if ( $main['nav']['save'] == 1 ) { // save active ## 
					include "save.php"; 
				}
        
    echo '
    </div></div>';

    // save report ##
    include "code/edit/report.php";

include "code/base/footer.php"; // html footer ##

?>
