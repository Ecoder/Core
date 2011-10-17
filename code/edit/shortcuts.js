// capture certain key presses an search for matching functions ##

// shortcut key ##
var isCtrl = false; 
document.onkeyup = function( e ) {

	// The event:
	if (!e) var e = window.event;
	
	// Whats the code?
	if (e.keyCode) ecoder_key = e.keyCode;
	else if (e.which) ecoder_key = e.which;
 
    if ( ecoder_key == 17 || ecoder_key == 224 ) isCtrl = false; // control || apple command false ##
    
} 

document.onkeydown = function ( e ) { 

	// The event:
	if (!e) var e = window.event;
	
	// Whats the code?
	if (e.keyCode) ecoder_key = e.keyCode;
	else if (e.which) ecoder_key = e.which;
    
    if ( ecoder_key == 17 || ecoder_key == 224 ) isCtrl = true; // control || apple command down ##
    
    if ( ( ( ecoder_key == 82 || ecoder_key == 114 ) && isCtrl == true || ecoder_key == 116 ) ) { // run code for CTRL + R || Fn5 ##   
        return false; // swallow reload ##
    
    } else if ( ( ecoder_key == 83 || ecoder_key == 115 ) && isCtrl == true ) { // run code for CTRL + S ##
        if ( typeof top.frames[ top.ecoder_iframe ].ecoder_save == 'function' ) { // exists ##
            top.frames[ top.ecoder_iframe ].ecoder_save();
        } else { // swallowed ##
            var e_note = "<p>keyboard shortcut <strong>( ctrl + s )</strong> blocked as no file found to save.</p>"; // warn ##
            top.ecoder_note ( 'note', e_note, '5', 'block' );           
        }    
        return false; // no going back ##       
    
    } else if ( ( ecoder_key == 119 || ecoder_key == 87 ) && isCtrl == true ) { // run code for CTRL + W ##       
        if ( top.ecoder_tab > 0 ) { // not home tab ##        
            top.ecoder_files( parent.ecoder_iframe, 'close', '', parent.ecoder_file, '', parent.content_changed );
        } else { // home tab, so swallow ##
            var e_note = "<p>keyboard shortcut <strong>( ctrl + w )</strong> blocked as you cannot close the home tab.</p>"; // warn ##
            top.ecoder_note ( 'note', e_note, '5', 'block' );        
        }
        return false; // swallow reload ##
        
    } else if ( ( ecoder_key == 81 || ecoder_key == 113 ) && isCtrl == true ) { // run code for CTRL + Q ##   
        var e_note = "<p>keyboard shortcut <strong>( ctrl + q )</strong> blocked as closing the browser will close all open documents.</p><p>to close the program close the current tab or browser window.</p>"; // warn ##
        top.ecoder_note ( 'note', e_note, '5', 'block' );
        return false; // swallow reload ##  

    } else if ( ecoder_key == 32 && isCtrl == true ) { // run code for CTRL + SPACE ##   
        if ( typeof top.ecoder_loaded_base == 'function' ) { // exists ##
            top.ecoder_loaded_base();
        }        
        return false; // no going back ##
 
    }
} 

