<?php

/* 
secure settings, if code['secure'] = 1
replace with your own custom variables ##
*/

// secure system if set ##
if ( $code['secure'] == 1 ) { // secured ##
  
    if ( !isset( $_SESSION[$code['secure_variable']] ) || $_SESSION[$code['secure_variable']] == 0 ) { // check for login variable ##

        echo '<script type="text/javascript">top.location.href = \''.$code['secure_url'].'\';</script>'; // kick-out ##
        
    } // check login ##
    
    if ( $code['secure_root'] == 1 && isset( $_SESSION['tree_root'] ) ) { // assign session to root variable ##

        $code['root'] = $_SESSION['tree_root']; // passed full path to editable root ##

    } // set root ##

}

?>
