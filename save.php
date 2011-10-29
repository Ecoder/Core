<?php

/*
save settings - via SACK ##
*/

if ( $main['mode'] == 'edit' ) { // file ##

    echo '
    <script type="text/javascript" src="code/save/sack.js"></script>';

} // get sack ##

    echo '
    <script type="text/javascript">
        
        // global variables ##
        var ecoder_path = \''.$main['path'].'\';
        var ecoder_file = \''.$main['file'].'\';
        var ecoder_save_on = \''.$main['save'].'\'; // use save function ##
        ';

    if ( $main['mode'] == 'edit' ) { // file ##
        
        echo '
        // save file
        function ecoder_save ( ) {
            
            // clear all open save timeouts ##
            if ( typeof ( top.hide ) != "undefined" ) { clearTimeout ( top.hide ); }
            
            // sack version ##
            var ajax = new sack();
            var debug = '.$_SESSION['debug'].';
            
            function whenCompleted() {
	            var e = document.getElementById(\'save\'); 
	            if ( debug == 1 ) { // debug ##
	                if ( ajax.responseStatus ){
		                var string = "saved: " + ajax.responseStatus[0] + " | " + ajax.responseStatus[1];
		                
	                } else {
		                var string = "<p>URLString Sent: " + ajax.URLString + "</p>";
		                
	                }
	                
	            } else { // no debugging ##
	                var string = "saved"; // + ecoder_file;
	                
	            }
	            e.innerHTML = string;	
	            //top.ecoder_note ( \'note\', ajax.responseStatus[0] + " | " + ajax.responseStatus[1], \'5\', \'block\' ); // show ajax response ##
            }
                
            // show confirm ##
            ecoder_display( \'save\', \'block\', \'saving\', 1 );	

            // get content from textarea ##   
            var ecoder_content = '.$save['contents'].'

	        ajax.setVar( "ecoder_path", ecoder_path ); // path ##
	        ajax.setVar( "ecoder_file", ecoder_file ); // file ##    
	        ajax.setVar( "ecoder_content", ecoder_content ); // file content ##
	        ajax.requestFile = "'.$save['target'].'"; // target ##
	        ajax.method = \'post\'; // method ##
	        ajax.onCompletion = whenCompleted;
	        ajax.runAJAX();
            
            // autosave ##
            if ( ecoder_autosave == 1 && ecoder_save_on == 1 ) {      
                var autosave = setTimeout( "ecoder_save()", '.$code['autosave_time'].'000 ); // call function every x seconds ##
            }
            
            // reset change counter ##
            ecoder_changed_stop();
            
        }
        
        // reset save changed status ##
        function ecoder_changed_stop () {	
            content_changed = 0; // reset count ##
            content_changed_loop = 1; // update ##
            ecoder_display( \'save_changes\', \'none\', \'\', 0 );
        }';
        
    } elseif ( $main['mode'] == 'add' ) { // add ##

        echo '   
        // add file ## 
        function ecoder_save () {	
	        if ( document.add.file.value == "" ) { // validate ##
	            var e_note = "<p>you have not entered a name for the new <strong>'.$main['type'].'</strong>, please complete the box and then press SAVE.</p>";
                top.ecoder_note ( \'note\', e_note, \'5\', \'block\' );	            
                return false;        
            } else { // all filled so submit ##
                document.add.submit();        
            }    
        }';
        
    } elseif ( $main['mode'] == 'delete' ) { // delete ##

        echo '    
        // delete file ##
        function ecoder_save () {	
            document.borrar.submit(); 
        }';
        
    } elseif ( $main['mode'] == 'rename' ) { // rename ##

        echo '
        // rename file ##
        function ecoder_save () {	
	        if ( document.rename.file_new.value == "" ) { // validate ##
	            var e_note = "<p>you have not entered a new name for <strong>'.$html['title'].'</strong>, please complete the box and then press SAVE.</p>";
                top.ecoder_note ( \'note\', e_note, \'5\', \'block\' );
                return false;        
            } else { // all filled so submit ##
                document.rename.submit(); 
            }
        }';
        
    } elseif ( $main['mode'] == 'upload' ) { // upload ##

        echo '
        // upload file ##
        function ecoder_save () {	
	        if ( document.upload.file.value == "" ) { // validate ##
	            var e_note = "<p>you have not selected a file to upload</p><p>browse and select a file, then press SAVE to upload.</p>";
                top.ecoder_note ( \'note\', e_note, \'5\', \'block\' );
                return false;        
            } else { // all filled so submit ##
                document.upload.submit(); 
            }
        }';

    } // save types ##

        echo '

        // show || hide ( div id, display status, message to show, hide again? ) ##
        function ecoder_display ( d_div, display, d_message, d_hide ) {
            var elem = document.getElementById( d_div );
            if ( display == "none" ) { 
                elem.style.display = "none"; 
            } else {
                elem.style.display = "block"; 
            }
            if ( d_message ) { // change innerHTML of div ##
                document.getElementById( d_div ).innerHTML = d_message; // change message ##
            }
            if ( d_hide == 1 ) { // hide again ##            
                var hide = setTimeout( "ecoder_display ( \'"+d_div+"\', \'none\', \'\' )", 4000 );
            }
        }	

    </script>';

?>