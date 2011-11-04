
if ( top === self ) { document.location='index.php'; } // pop back in ##
var content_changed = 0; // swallow first change ( focus ) to editarea ##
var content_changed_loop = 0; // from first save, first change is good ##
var ecoder_hours; var ecoder_minutes; var ecoder_seconds;
function ecoder_changed() {
	
	// set time ##
	var ecoder_time = new Date()
	ecoder_hours = ecoder_time.getHours()
	ecoder_minutes = ecoder_time.getMinutes()
	ecoder_seconds = ecoder_time.getSeconds()
	if ( ecoder_hours < 10 ) { ecoder_hours = "0" + ecoder_hours; }
	if ( ecoder_minutes < 10 ) { ecoder_minutes = "0" + ecoder_minutes; }
	if ( ecoder_seconds < 10 ) { ecoder_seconds = "0" + ecoder_seconds; }
	ecoder_changed_time = 'edited: ' + ecoder_hours + ':' + ecoder_minutes + ':' + ecoder_seconds; // build message ##
	if ( content_changed > 0 || content_changed_loop == 1 ) { // update ##                  
			ecoder_display('save_changes','block',ecoder_changed_time); // show changes div ##                    
	} 
	content_changed ++; // iterate ##

} 

// add file ## 
function ecoder_save () {	
	if ( document.add.file.value == "" ) { // validate ##
		var e_note = "<p>you have not entered a name for the new <strong>'.$main['type'].'</strong>, please complete the box and then press SAVE.</p>";
		top.ecoder_note('note',e_note,'5','block');	            
		return false;        
	} else { // all filled so submit ##
		document.add.submit();        
	}    
	return true;
}
				
// show || hide ( div id, display status, message to show, hide again? ) ##
function ecoder_display ( d_div, display, d_message) {
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