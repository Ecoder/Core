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
            var ecoder_agent_browser = "'.$browser['agent'].'"; // browser ##
            var ecoder_agent_system = "'.$system['agent'].'"; // operating system ##
            var ecoder_tree_home = "'.$tree['root'].'"; // tree home destination ##            
        </script>';

?>
