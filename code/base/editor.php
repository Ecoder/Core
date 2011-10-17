<?php
#$_SESSION['editor'] = "";
/* allow editor to be hot swapped && set editor choose to session ## */
@ecoder_request( $_GET['editor'], $code['editor_swap'], '' ); // hot switch editor ##

if ( $code['editor_swap'] ) { // if swap called ##    
    if ( $code['editor_swap'] == "basic" ) { // codepress ##        
        $code['editor_message'] = "1 - swapped to basic"; // where changed ##    
        $_SESSION['editor'] = 'basic';
        $_SESSION['editor_name'] = 'editarea'; // system name ##
				$_SESSION['editor_file']  = 'edit_area_loader.js'; // options ##
         
    } elseif ( $code['editor_swap'] == "delux" ) { // editarea        
        $code['editor_message'] = "2 - swapped to delux"; // where changed ##
        $_SESSION['editor'] = 'delux';
        $_SESSION['editor_name'] = 'editarea'; // system ##
        $_SESSION['editor_file'] = 'edit_area_loader.js'; // options ##
 
    } else {
        $code['editor_message'] = "3 - swap error"; // where changed ##        
    }
        
} elseif ( !isset ( $_SESSION['editor'] ) || $_SESSION['editor'] == "" ) { // use code setting ##

    if ( $code['editor'] == "basic" ) { // codepress ##        
        $code['editor_message'] = "4 - basic from code settings"; // where changed ##
        $_SESSION['editor'] = 'basic';
        $_SESSION['editor_name'] = 'editarea'; // system name ##
        $_SESSION['editor_file'] = 'edit_area_loader.js'; // options ##
    
    } elseif ( $code['editor'] == "delux" ) { // editarea
        $code['editor_message'] = "5 - delux from code settings"; // where changed ##
        $_SESSION['editor'] = 'delux';
        $_SESSION['editor_name'] = 'editarea'; // system ##
        $_SESSION['editor_file'] = 'edit_area_loader.js'; // options ##
        
    } else {
        $code['editor_message'] = "6 - code settings error"; // where changed ##    
    }

} else {    
    $code['editor_message'] = "7 - none of the above"; // where changed ##    
}

// build editor test message ##
$code['editor_message'] .= " [ editor: ".$_SESSION['editor']." ]"; // add session info ##
   
?>
