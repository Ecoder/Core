<?php

/*
simple javascript > php passer ##
*/

        echo '
        <script type="text/javascript">
            var ecoder_name = "'.$code['name'].'"; // system name ##
            var ecoder_editor = "'.$code['editor'].'"; // editor version ##
            var ecoder_autosave = "'.$code['autosave'].'"; // autosave status ##
            var ecoder_save_type = "home"; // declare ##
            var ecoder_tab = 0; // current tab ##
            var ecoder_iframe = "home_txt"; // start at home ##
        </script>';

?>
