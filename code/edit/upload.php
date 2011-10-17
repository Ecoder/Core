<?php

/* 
single file upload - goes to current directory ##
*/

// target ##
$save['target'] = 'code/save/upload.php';

// build simple form ##
echo '
<div class="edit_form">
    <form name="upload" action="'.$save['target'].'" method="post" enctype="multipart/form-data">        
        <input type="hidden" name="path" value="'.$main['path'].'">
        
        <h1>upload a file</h1>
        <p>press browse and select a file, then press save.</p>';

        // confirm & close ##
        include "close.php";
        
        echo '    
        <div class="filename">
            <input type="file" name="file" id="file" />            
        </div>    
 
    </form> 
</div>';