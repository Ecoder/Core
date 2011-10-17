<?php

/* 
rename file ##
*/

// settings ##
$save['target'] = 'code/save/rename.php';
$main['file_ext'] = ''; // nada for folder ##

// get extension ##
if ( $main['type'] == 'file' ) {

    $main['ext_array'] = explode ( ".", $html['title'] ); // get extension ##
    $main['file_ext'] = end ( $main['ext_array'] ); // get extension ##  
}

// build simple form ##
echo '
<div class="edit_form">
    <form name="rename" action="'.$save['target'].'" method="post">        
        <input type="hidden" name="path" value="'.$main['path'].'">
        <input type="hidden" name="type" value="'.$main['type'].'">
        <input type="hidden" name="file" value="'.$html['title'].'">
        <input type="hidden" name="ext" value="'.$main['file_ext'].'">
        
        <h1>rename '.$main['type'].'</h1>';        
        
        if ( $main['report_code'] != 1 ) { // not done yet ##
        
        echo '
        <p>to rename '.$main['path'].$html['title'].' enter a new name, without extension and save.</p>';
                        
        }                
                        
        // confirm & close ##
        include "close.php";
        
        echo '
        <div class="filename">
            <input type="text" class="text" name="file_new" value=""></input>
        </div>  
        
        <div class="dot">.</div>  

        <div class="type_rename">'.$main['file_ext'].'</div>    
                 
    </form> 
</div>';

// include save script ##
if ( $main['nav']['save'] == 1 ) { // save active ## 
    include "save.php"; 
}

?>
