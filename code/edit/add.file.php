<?php

/* 
add file form ##
*/

// filter allowable file types from file type array ##
function ecoder_array_clean ( $str ) {
    if ( in_array ( $str, array ( "htaccess", "ini" ) ) ) return false; else return true;
}
$array_types = array_merge( array(), array_filter( $_SESSION['tree_file_types'], "ecoder_array_clean" ) );

// build simple form ##
echo '
<div class="edit_form">
    <form name="add" action="code/save/add.php" method="post">        
        <input type="hidden" name="path" value="'.$main['path'].'">
        
        <h1>create a new '.$main['type'].'</h1>';        
        
        if ( $main['report_code'] != 1 ) { // not done yet ##
        
        echo '
        <p>enter a name, select a file type and press save.</p>';
        
        }
        
        // confirm & close ##
        include "close.php";
        
        echo '    
        <div class="filename">
            <input type="text" class="text" name="file" value="new_'.$main['type'].'"></input>
        </div>    

        <div class="dot">.</div>  

        <div class="type">
            <select name="type" class="select">';
                
            // loop all allowed file types ##    
            foreach ( $array_types as $t => $value ) {                
                echo '<option value="'.$array_types[$t].'" />'.$_SESSION['tree_file_types'][$t].'';                
            }
            
            echo '
            </select>
        </div>    
    </form> 
</div>';

?>
