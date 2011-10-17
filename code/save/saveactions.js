function ecoder_save_edit() {
	
}

function ecoder_save_add() {
	if ( document.add.file.value == "" ) { // validate ##
		var e_note = "<p>you have not entered a name for the new <strong>"+ec_main_type+"</strong>, please complete the box and then press SAVE.</p>";
		top.ecoder_note('note',e_note,'5','block');	            
		return false;        
	}
	document.add.submit();     
	return true;
}

function ecoder_save_delete() {
	document.borrar.submit();
}

function ecoder_save_rename() {
	if ( document.rename.file_new.value == "" ) { // validate ##
		var e_note = "<p>you have not entered a new name for <strong>"+ec_html_title+"</strong>, please complete the box and then press SAVE.</p>";
		top.ecoder_note ('note',e_note,'5','block');
		return false;        
	}
	document.rename.submit();
	return true
}

function ecoder_save_upload() {
	if ( document.upload.file.value == "" ) { // validate ##
		var e_note = "<p>you have not selected a file to upload</p><p>browse and select a file, then press SAVE to upload.</p>";
		top.ecoder_note ('note',e_note,'5','block');
		return false;        
	}
	document.upload.submit();
	return true;
}

// reset save changed status ##
function ecoder_changed_stop () {	
	content_changed = 0; // reset count ##
	content_changed_loop = 1; // update ##
	ecoder_display('save_changes','none','',0);
}

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