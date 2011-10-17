<?php

/* 
add individual tracking variable for each tab ##
*/

        echo '
        <script type="text/javascript">
            if ( top === self ) { document.location=\'index.php\'; } // pop back in if not loaded in iframe ##            
            var ecoder_tree_path = "'.$tree['path'].'"; // current path of tree ##
            var ecoder_tree_hidden = "'.$tree['hidden']['switch'].'" // hidden files status ##
            addLoadEvent ( ecoder_loaded_tree ); // check for js and close loading screen ##
        </script>';

?>
