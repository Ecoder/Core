
// upload file ##
        function ecoder_save () {	
	        if ( document.upload.file.value == "" ) { // validate ##
	            var e_note = "<p>you have not selected a file to upload</p><p>browse and select a file, then press SAVE to upload.</p>";
                top.ecoder_note ('note', e_note, '5', 'block' );
                return false;        
            } else { // all filled so submit ##
                document.upload.submit(); 
            }
        }
				
				 // show || hide ( div id, display status, message to show, hide again? ) ##
        function ecoder_display ( d_div, display, d_message ) {
            var elem = document.getElementById( d_div );
            if ( display == "none" ) { 
                elem.style.display = "none"; 
            } else {
                elem.style.display = "block"; 
            }
            if ( d_message ) { // change innerHTML of div ##
                document.getElementById( d_div ).innerHTML = d_message; // change message ##
            }
        }	
