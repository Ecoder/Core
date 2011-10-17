<?php

/* 
css switcher / adder for browser compatibility
*/

// search for file and reference if found ##
if ( file_exists ( $code['skin_path'].'base.'.$system['agent'].'.'.$browser['agent'].'.css' ) ) {

    echo '
    <link href="'.$code['skin_path'].'base.'.$system['agent'].'.'.$browser['agent'].'.css" rel="stylesheet" type="text/css" media="screen" />';

}

?>
