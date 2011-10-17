<?php

/* 
delete file ##
*/

// settings ##
$save['target'] = 'save/delete.php';

// build simple form ##
echo '
<div class="edit_form">
    <form name="borrar" action="code/'.$save['target'].'" method="post">        
        <input type="hidden" name="path" value="'.$main['path'].'">
        <input type="hidden" name="type" value="'.$main['type'].'">
        <input type="hidden" name="file" value="'.$html['title'].'">
        
        <h1>delete '.$main['type'].'</h1>';        
        
        if ( $main['report_code'] != 1 ) { // not done yet ##
        
        echo '
        <p>to delete <strong>'.$main['path'].$html['title'].'</strong> press save.</p>';
        
        }
        
        // confirm & close ##
        include "close.php";
        
    echo '           
    </form> 
</div>';

// include save script ##
if ( $main['nav']['save'] == 1 ) { // save active ## 
    include "save.php"; 
}

?>
