// capture certain key presses an search for matching functions ##

// shortcut key ##
//TODO in massive need of refactoring
/*
var isCtrl = false;
var isAlt = false;
document.onkeyup = function( e ) {

	// The event ##
	if (!e) var e = window.event;

	// What's the code? ##
	if (e.keyCode) ecoder_key = e.keyCode;
	else if (e.which) ecoder_key = e.which;

    if ( ecoder_key == 17 || ecoder_key == 224 ) isCtrl = false; // control || apple command false ##
    if ( ecoder_key == 18 ) isAlt = false; // alt false ##

}

document.onkeydown = function ( e ) { // keydown ##

	// The event:
	if (!e) var e = window.event;

	// Whats the code?
	if (e.keyCode) ecoder_key = e.keyCode;
	else if (e.which) ecoder_key = e.which;

    if ( ecoder_key == 17 || ecoder_key == 224 ) isCtrl = true; // control || apple command down ##
    if ( ecoder_key == 18 ) isAlt = true; // alt down ##

    if ( ecoder_key == 83 && isCtrl == true ) { // run code for CTRL + S ##
        if ( top.ecoder_iframe != null && typeof parent.frames[ top.ecoder_iframe ].ecoder_save == 'function' ) { // exists ##
            parent.frames[ top.ecoder_iframe ].ecoder_save();
        } else { // swallowed ##
            var e_note = '<p>keyboards shortcut <strong>( ctrl + s )</strong> blocked, as no open document was found to save.</p>';
            top.ecoder_note ( 'note', e_note, '5', 'block' );
            return false; // swallow reload ##
        }
        return false; // no going back ##

    } else if ( ( ecoder_key == 82 && isCtrl == true ) || ecoder_key == 116 ) { // run code for CTRL + r || Fn5 ##
        var e_note = "<p>keyboard shortcut <strong>( ctrl + r or f5 )</strong> blocked as refreshing the window will close all open documents.<p></p>to close the program close the current tab or browser window.</p>";
        top.ecoder_note ( 'note', e_note, '5', 'block' );
        return false; // swallow reload ##

    } else if ( ecoder_key == 87 && isCtrl == true ) { // run code for CTRL + w ##
        var e_note = "<p>keyboard shortcut <strong>( ctrl + w )</strong> blocked as closing the tab will close all open documents.</p><p>to close the program close the current tab or browser window.</p>";
        top.ecoder_note ( 'note', e_note, '5', 'block' );
        return false; // swallow reload ##

    } else if ( ecoder_key == 81 && isCtrl == true ) { // run code for CTRL + q ##
        var e_note = "<p>keyboard shortcut <strong>( ctrl + q )</strong> blocked as closing the browser will close all open documents.</p><p>to close the program close the current tab or browser window.</p>"; // warn ##
        top.ecoder_note ( 'note', e_note, '5', 'block' );
        return false; // swallow reload ##

    } else if ( ecoder_key == 32 && isCtrl == true ) { // run code for CTRL + SPACE ##
        if ( typeof top.ecoder_loaded_base == 'function' ) { // exists ##
            top.ecoder_loaded_base( 'block' );
        }
        return false; // no going back ##

    } else if ( ecoder_key == 49 && isCtrl == true ) { // CTRL + 1 -- tree home ##
        if ( typeof top.ecoder_tree == 'function' ) { // exists ##
            top.ecoder_tree ( 'tree', 'home', top.ecoder_tree_home );
        }

    } else if ( ecoder_key == 50 && isCtrl == true ) { // CTRL + 2 -- add file ##
        if ( typeof top.ecoder_files == 'function' ) { // exists ##
            top.ecoder_files ( 'main', 'add', top.frames["tree"].ecoder_tree_path, '', 'file' );

        }
    } else if ( ecoder_key == 51 && isCtrl == true ) { // CTRL + 3 -- add folder ##
        if ( typeof top.ecoder_files == 'function' ) { // exists ##
            top.ecoder_files ( 'main', 'add', top.frames["tree"].ecoder_tree_path, '', 'folder' );
        }

    } else if ( ecoder_key == 52 && isCtrl == true ) { // CTRL + 4 -- hidden files ##
        if ( typeof top.ecoder_tree == 'function' ) { // exists ##
            top.ecoder_tree ( 'tree', 'hidden', top.frames["tree"].ecoder_tree_path, top.frames["tree"].ecoder_tree_hidden );
        }

    } else if ( ecoder_key == 53 && isCtrl == true ) { // CTRL + 5 -- upload file ##
        if ( typeof top.ecoder_files == 'function' ) { // exists ##
            top.ecoder_files ( 'main', 'upload', top.frames["tree"].ecoder_tree_path, '', 'file' );
        }

    } else if ( ecoder_key == 54 && isCtrl == true ) { // CTRL + 6 -- reload tree ##
        if ( typeof top.ecoder_tree == 'function' ) { // exists ##
            top.ecoder_tree ( 'tree', 'reload' );
        }

    // alt keys for tabs ---------------------------------------------------------------------------------------------------

    } else if ( ecoder_key == 49 && isAlt == true ) { // ALT + 1 -- home tab ##
        if ( typeof top.ecoder_tabs_focus == 'function' ) { // exists ##
            top.ecoder_tabs_focus ( 'home.txt', 'home_txt', 0 );
        }

    }

}

*/
