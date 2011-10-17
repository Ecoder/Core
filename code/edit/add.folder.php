<?php

/* 
add file form ##
*/

// build simple form ##
echo '
<div class="edit_form">
    <form name="add" action="code/save/add.php" method="post">   
         
        <input type="hidden" name="path" value="'.$main['path'].'">
       
        <h1>create a new '.$main['type'].'</h1>';
        
        if ( $main['report_code'] != 1 ) { // not done yet ##
        
        echo '
        <p>enter a name and press save</p>';
        
        }
        
        // confirm & close ##
        include "close.php";

        echo '
        <div class="filename">
            <input type="text" class="text" name="file" value="new_'.$main['type'].'"></input>
        </div>    

    </form> 
</div>';

?>
