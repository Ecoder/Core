// main ecoder javascript ##

// file functions ##
// frame target, action || mode, file path, file name, file extension || file/folder, change tracker ##
function ecoder_files ( frame, mode, path, file, type, changed ) {
    
    var ecoder_tabs_max = 10; // max tabs ##
    
    // make uniquish iframe id ##
    var ecoder_file_full = file; // assign file to variable ##
    var ecoder_path_full = path; // assign path to variable ##
    var ecoder_file_clean = ''; // declare ##
    var ecoder_changed_min = 1; // number of changes to warn on ##
    ecoder_path_full = ecoder_replace_all( ecoder_path_full, [ ["/", "_"] ] ); // replace / with _ in path ##   
    ecoder_file_full = ecoder_replace_all( ecoder_file_full, [ [".", "_"] ] ); // replace . with _ in file ##   
    ecoder_file_clean = ecoder_path_full + ecoder_file_full; // add path & file ##
      
    var ecoder_frame = frame; // get frame ##
    var ecoder_mode = mode; // get mode ##
    var ecoder_type = type; // get frame ##
    var ecoder_file = ''; // file ##

    if ( ecoder_mode == 'close' ) { // close file ##
        var close_do = 0; // false ##
        var close_confirm = 'if you close '+ file +' unsaved changes may be lost.\npress OK to close or CANCEL to stop.'; // message ##
        if ( changed > ecoder_changed_min ) { // delux and changes made -- was > 1 ##
            if ( confirm ( close_confirm ) ) { // confirm ## + changed
                close_do = 1; // ok ##
            }    
        } else { // delux and no changes made ##
            close_do = 1; // ok ##       
        }

        if ( close_do == 1 && top.ecoder_tab > 0 ) {
            top.ecoder_tabs_close(); // close ##
            ecoder_html_title ( 'home' ); // set title ##
        }

    } else if ( ecoder_mode == 'editor' ) { // swap code editor ##
        var swap_do = 0; // false ##
        var swap_confirm = 'swap to '+ ecoder_type +' editor? unsaved changes to '+ file +' will be lost.\npress OK to close or CANCEL to stop.'; // message ##
        if ( changed > ecoder_changed_min ) { // delux and changes made -- was > 1 ##
            if ( confirm( swap_confirm ) ) { // confirm ##
                swap_do = 1; // ok ##
            }
        } else { // delux and no changes made ##
            swap_do = 1; // ok ##
        }       

        if ( swap_do == 1 ) {
            top.frames[frame].location='edit.php?'+ path +'&editor='+ type; // call ##
            ecoder_editor = ecoder_type; // update ecoder_editor variable if swapped ##
        }

    } else if ( ecoder_mode == 'reload' ) { // reload ##
        var reload_do = 0; // false ##
        var reload_confirm = 'if you reload '+ file +' unsaved changes will be lost.\npress OK to close or CANCEL to stop.'; // message ##
        if ( changed > ecoder_changed_min ) { // delux and changes made -- was > 1  ##
            if ( confirm( reload_confirm ) ) { // confirm ## + changed
                reload_do = 1; // ok ##
            }    
        } else { // delux and no changes made ##
            reload_do = 1; // ok ##
        }
        if ( reload_do == 1 ) {
            top.frames[frame].location.reload(true); // call ##
        }
        
    } else if ( ecoder_mode == 'rename' ) { // rename file or folder ##

        var ecoder_rename_clean = ecoder_path_full +'rename_'+type; // new object name ##
        var ecoder_object = ecoder_check_object( ecoder_rename_clean ); // check if object/file is open ##
        var ecoder_rename = 'edit.php?mode='+ mode +'&path='+ path +'&file='+ file +'&type='+ type; // url to open ##
        if ( ecoder_object ) { // rename tab open, so focus ##
            
            var parent_id; // declare ##  
            parent_id = document.getElementById( ecoder_rename_clean ).parentNode.id; // get id from parent ##
            parent_id = parent_id.replace( /tabber_panel_/, "" );// remove 'tabber_panel_' ## //alert ( parent_id );                      
            top.ecoder_tabs_focus ( file, ecoder_rename_clean, parent_id ); // focus tab ##        
            
            // note ##
            var e_note = "<p>you can only rename one file at a time.</p><p>pick a new name or CLOSE the current file rename operation.</p>";
            top.ecoder_note ( 'note', e_note, '5', 'block' );

        } else { // rename tab not open yet ##
            
            //alert ( "check if iframe "+ ecoder_file_clean +" open" );
            // check if file is being edited ##
            var ecoder_object_rename = ecoder_check_object( ecoder_file_clean ); // object name ##
            //alert ( ecoder_file_clean );
            if ( ecoder_object_rename ) { // file open already ##
                //alert ( 'file open' );
                // focus open file tab ##
                var parent_id; // declare ##  
                parent_id = document.getElementById( ecoder_file_clean ).parentNode.id; // get id from parent ##
                parent_id = parent_id.replace( /tabber_panel_/, "" );// remove 'tabber_panel_' ##          
                //alert ( parent_id + ' - ' + ecoder_tab );
                if ( parent_id != ecoder_tab ) { // focus, if not clicked on current tab ##
                    
                    // focus tab ##
                    //alert ( "focus "+ ecoder_tab +" iframe: "+ ecoder_file_clean );
                    top.ecoder_tabs_focus ( file, ecoder_file_clean, parent_id );

                    // note ##
                    var e_note = "<p>as <strong>"+ file +"</strong> is already open for editing you should SAVE any changes and click the rename icon to the top right to continue.</p>";
                    top.ecoder_note ( 'note', e_note, '5', 'block' );

                    // set title ##     
                    ecoder_html_title ( file );   
                
                } else { // current tab - so confirm close if changed and switch to rename tab ##
                    
                    var close_do = 0; // false ##
                    var close_confirm = 'if you close '+ file +' unsaved changes will be lost.\npress OK to close or CANCEL to stop.'; // message ##
                    if ( changed > ecoder_changed_min ) { // changes made -- was > 1 TODO ##
                        if ( confirm ( close_confirm ) ) { // confirm ## + changed
                            close_do = 1; // ok ##
                        }    
                    } else { // no changes made ##
                        close_do = 1; // ok ##       
                    }

                    if ( close_do == 1 ) { // closed confirmed and not home tab ##                     
                    
                        if ( top.ecoder_tab > 0 ) { // close if not focused on home ##
                            //alert ( "close "+ ecoder_tab );
                            top.ecoder_tabs_close();
                        }

                        // add delete tab -- iframe name/id ,label, iframe url, path ##
                        //alert ( "add rename tab" );
                        top.ecoder_tabs_add ( ecoder_rename_clean, 'rename '+type, ecoder_rename, '' );

                        // note ##
                        var e_note = "<p>to rename <strong>"+ file +"</strong> enter a new name and press SAVE, to cancel click CLOSE.</p>";
                        top.ecoder_note ( 'note', e_note, '5', 'block' );

                    } // do close ##
                                        
                }
        
            } else { // file not being edited ##                
                //alert ( "just open rename tab" );
                top.ecoder_tabs_add ( ecoder_rename_clean, 'rename '+type, ecoder_rename, '' ); // add new tab -- iframe name/id ,label, iframe url, path ##  
                ecoder_html_title ( 'rename '+type ); // set title ##          
            }            
        }        

    } else if ( ecoder_mode == 'delete' ) { // delete file or folder ##

        var ecoder_delete_clean = ecoder_path_full +'delete_'+type; // new object name ##
        var ecoder_object = ecoder_check_object( ecoder_delete_clean ); // check if object/file is open ##
        var ecoder_delete = 'edit.php?mode='+ mode +'&path='+ path +'&file='+ file +'&type='+ type; // url to open ##
        if ( ecoder_object ) { // delete tab open, so focus ##
            
            //alert ( "delete tab open" );
            var parent_id; // declare ##  
            parent_id = document.getElementById( ecoder_delete_clean ).parentNode.id; // get id from parent ##
            parent_id = parent_id.replace( /tabber_panel_/, "" );// remove 'tabber_panel_' ## //alert ( parent_id );                      
            top.ecoder_tabs_focus ( file, ecoder_delete_clean, parent_id ); // focus tab ##        

            // note ##
            var e_note = "<p>you can only delete one <strong>"+ type +"</strong> at a time</p><p>press SAVE to delete or CLOSE to the cancel the operation.</p>";
            top.ecoder_note ( 'note', e_note, '5', 'block' );
  
        } else { // delete tab not open yet ##
            
            //alert ( "check if iframe "+ ecoder_file_clean +" open" );
            // check if file is being edited ##
            var ecoder_object_delete = ecoder_check_object( ecoder_file_clean ); // object name ##
            //alert ( ecoder_file_clean );
            if ( ecoder_object_delete ) { // file open already ##
                //alert ( 'file open' );
                // focus open file tab ##
                var parent_id; // declare ##  
                parent_id = document.getElementById( ecoder_file_clean ).parentNode.id; // get id from parent ##
                parent_id = parent_id.replace( /tabber_panel_/, "" );// remove 'tabber_panel_' ##          
                //alert ( parent_id + ' - ' + ecoder_tab );
                if ( parent_id != ecoder_tab ) { // focus, if not clicked on current tab ##
                    
                    // focus tab ##
                    //alert ( "focus "+ ecoder_tab +" iframe: "+ ecoder_file_clean );
                    top.ecoder_tabs_focus ( file, ecoder_file_clean, parent_id );

                    // note ##
                    var e_note = "<p><strong>"+file+"</strong> is already open and has been focused, to delete this file you should click the delete option to the top right.</p>";
                    top.ecoder_note ( 'note', e_note, '5', 'block' );

                    // set title ##     
                    ecoder_html_title ( file );   
                
                } else { // current tab - so confirm close if changes and switch to delete tab ##
                    
                    var close_do = 0; // false ##
                    var close_confirm = 'if you close '+ file +' unsaved changes will be lost.\npress OK to close or CANCEL to stop.'; // message ##
                    if ( changed > ecoder_changed_min ) { // changes made -- was > 1 TODO ##
                        if ( confirm ( close_confirm ) ) { // confirm ## + changed
                            close_do = 1; // ok ##
                        }    
                    } else { // no changes made ##
                        close_do = 1; // ok ##       
                    }

                    if ( close_do == 1 ) { // closed confirmed and not home tab ##                     
                    
                        if ( top.ecoder_tab > 0 ) { // close if not focused on home ##
                            //alert ( "close "+ ecoder_tab );
                            top.ecoder_tabs_close();
                        }

                        // add delete tab -- iframe name/id ,label, iframe url, path ##
                        //alert ( "add delete tab" );
                        top.ecoder_tabs_add ( ecoder_delete_clean, 'delete '+type, ecoder_delete, '' );

                        // note ##
                        var e_note = "<p>to delete <strong>"+ file +"</strong> press SAVE, to cancel click CLOSE.</p>";
                        top.ecoder_note ( 'note', e_note, '5', 'block' );

                    } // do close ##
                                        
                }
        
            } else { // file not being edited ##                
                //alert ( "just open delete tab" );
                top.ecoder_tabs_add ( ecoder_delete_clean, 'delete '+type, ecoder_delete, '' ); // add new tab -- iframe name/id ,label, iframe url, path ##  
                ecoder_html_title ( 'delete '+type ); // set title ##          
            }            
        }        

    } else if ( ecoder_mode == 'add' ) { // add file or folder to tree ##

        var ecoder_file_clean = ecoder_path_full +'add_'+type; // new object name ##
        var ecoder_object = ecoder_check_object( ecoder_file_clean ); // check if object/file is open ##
        var ecoder_file = 'edit.php?mode='+ mode +'&path='+ path +'&file='+ file +'&type='+ type; // url to open ##
        if ( ecoder_object ) { // tab open, so focus ##
        
            var parent_id; // declare ##  
            parent_id = document.getElementById( ecoder_file_clean ).parentNode.id; // get id from parent ##
            parent_id = parent_id.replace( /tabber_panel_/, "" );// remove 'tabber_panel_' ##          
            top.ecoder_tabs_focus ( file, ecoder_file_clean, parent_id ); // focus tab ##        

            // note ##
            var e_note = "<p>the <strong>new "+type+"</strong> tab for the folder <strong>"+path+"</strong> is already open and has been focused.</p>";
            top.ecoder_note ( 'note', e_note, '5', 'block' );
  
        } else { // not open yet ##
            top.ecoder_tabs_add ( ecoder_file_clean, 'new '+type, ecoder_file, '' ); // add new tab -- iframe name/id ,label, iframe url, path ##
            
        }
        ecoder_html_title ( 'new '+type ); // set title ##

    } else if ( ecoder_mode == 'upload' ) { // upload file to tree ##

        var ecoder_file_clean = ecoder_path_full +'upload_file'; // new object name ##
        var ecoder_object = ecoder_check_object( ecoder_file_clean ); // check if object/file is open ##
        var ecoder_file = 'edit.php?mode='+ mode +'&path='+ path +'&file='+ file +'&type=file'; // url to open ##
        if ( ecoder_object ) { // tab open, so focus ##
        
            var parent_id; // declare ##  
            parent_id = document.getElementById( ecoder_file_clean ).parentNode.id; // get id from parent ##
            parent_id = parent_id.replace( /tabber_panel_/, "" );// remove 'tabber_panel_' ##          
            top.ecoder_tabs_focus ( file, ecoder_file_clean, parent_id ); // focus tab ##        

            // note ##
            var e_note = "<p>the <strong>file upload</strong> tab for the folder <strong>"+path+"</strong> is already open and has been focused.";
            top.ecoder_note ( 'note', e_note, '5', 'block' );

        } else { // not open yet ##
            top.ecoder_tabs_add ( ecoder_file_clean, 'upload file', ecoder_file, '' ); // add new tab -- iframe name/id ,label, iframe url, path ##

            // note ##
            var e_note = "<p>to upload a file to the folder <strong>"+path+"</strong> click browse, find the file and then press SAVE.";
            top.ecoder_note ( 'note', e_note, '5', 'block' );
     
        }
        ecoder_html_title ( 'upload file' ); // set title ##
       
    } else if ( ecoder_mode == 'read' || ecoder_mode == 'edit' ) { // edit or read ##            
        
        var ecoder_object = ecoder_check_object( ecoder_file_clean ); // check if object/file is open ##
        var ecoder_file = 'edit.php?mode='+ mode +'&path='+ path +'&file='+ file +'&type='+ type; // url to open ##
        if ( ecoder_object ) { // tab open, so focus ##
            
            var parent_id; // declare ##  
            parent_id = document.getElementById( ecoder_file_clean ).parentNode.id; // get id from parent ##
            parent_id = parent_id.replace( /tabber_panel_/, "" );// remove 'tabber_panel_' ##          
            //alert ( parent_id + ' - ' + ecoder_tab );
            if ( parent_id != ecoder_tab ) { // focus, if not clicked on current tab ##
                top.ecoder_tabs_focus ( file, ecoder_file_clean, parent_id ); // focus tab ##
            }
            
        } else { // not open yet ##       
            if ( top.tabber.tabContainer.cells.length + 1 > ecoder_tabs_max ) { // restrict to x tabs
                
                // note ##
                var e_note = "<p>you already have <strong>"+ ecoder_tabs_max +"</strong> tabs open which is the maximum allowed in the configuration.</p>";
                top.ecoder_note ( 'note', e_note, '5', 'block' );                
                
            } else { // ok to add tab ##     
            
                if ( ecoder_mode == "read" ) { // read only notice ##
                    var e_note = "<p><strong>"+ file +"</strong> is read-only, so you can view but not edit this document.</p>";
                    top.ecoder_note ( 'note', e_note, '5', 'block' );      
                }   
                   
                top.ecoder_tabs_add ( ecoder_file_clean, file, ecoder_file, path ); // add new tab -- iframe name/id ,label, iframe url, path ##
                
            }
        }
        ecoder_html_title ( file ); // set title ## // path+file           
       
    }
    return false; // no return ##
}

// ----------------------------------------------------------------------------------------------------------

// tree functions ##
function ecoder_tree ( frame, mode, path, hidden ) {
    
    var ecoder_mode = mode; // get mode ##
     
    if ( ecoder_mode == 'reload' ) { // reload ##
        top.frames[frame].location.reload(true);
        
    } else if ( ecoder_mode == 'home' ) { // tree home ##
        top.frames[frame].location=''+ frame +'.php?path='+ path; // call ##

    } else if ( ecoder_mode == 'up' ) { // tree up ##
        top.frames[frame].location=''+ frame +'.php?path='+ path; // call ##
        
    } else if ( ecoder_mode == 'open' ) { // tree open folder ##
        top.frames[frame].location=''+ frame +'.php?path='+ path; // call ##

    } else if ( ecoder_mode == 'hidden' ) { // show / hide hidden files ##
        top.frames[frame].location=''+ frame +'.php?path='+ path +'&hidden='+ hidden; // call ##
        
    }
    return false; // no return ##
}

// ----------------------------------------------------------------------------------------------------------

// notes, errors and messages ##
var ecoder_count_time = 0; // set to zero ##
var ecoder_note_open = 0; // timeout ID ##
//var ecoder_note_fade = 1; // timeout ID ##
function ecoder_note ( n_div, n_msg, n_delay, n_display ) {
    
    clearTimeout ( ecoder_note_open ); // clear all open note timeouts ##
      
    // set time if not passed ##
    if ( typeof ( n_delay ) == "undefined" || n_delay < 5 ) n_delay = 5;
    
    // convert n_delay to miliseconds ##
    note_delay = ( n_delay * 1000 );
    
    // show hide ##
    var elem = document.getElementById( n_div );
    if ( n_display == "none" ) { 
        elem.style.display = "none"; 
    } else {
        elem.style.display = "block"; 
    }

    // add close and clock ##
    var n_close = '<div id="note_close"><div id="note_x"><a href="#" onclick="ecoder_note_reset( \'note\', \'none\' )" title="close note"><img src="skin/one/design/icon_close.png" alt="close note" border="0" /></a></div><div id="note_clock">'+n_delay+'</div></div>';

    // alter content ##
    if ( n_msg ) { // change innerHTML of save div ##
        elem.innerHTML = n_close + n_msg; // change message ##
    }
    
    // start clock and set hide timeout ##
    //ecoder_note_fade = setTimeout( "ecoder_fade ( '"+ n_div +"', '100', '0', '1000' )", ( note_delay - 1000 ) ); // TODO -- fade for a second ##
    ecoder_note_track ( '1' ); // running ##
    ecoder_note_open = setTimeout( "ecoder_note_reset ( '"+ n_div +"', 'none' )", note_delay ); // hide after a delay ##
    var note_clock = setTimeout( "ecoder_count ( 'note_clock', '"+ n_delay +"' )", 0 ); // count it ##
    
    // swallow return ##
    return false; 
}

// hide note ##
function ecoder_note_reset ( r_div, r_display ) {
    document.getElementById( r_div ).style.display = r_display; // just hide ##
    ecoder_note_track ( '0' ); // not running ##
    //ecoder_note_open = 0; // not running ##
}

// track note ##
function ecoder_note_track ( track ) {
    if ( track == 1 ) {
        //if ( ecoder_count_time > 0 ) { // note counting.. ##
        //}            
    } else if ( track == 0 ) { 
        //alert ( "note stopped.." );         
    }
}

// ----------------------------------------------------------------------------------------------------------

// countdown ( div, seconds )
function ecoder_count ( c_div, c_time ){
     if ( typeof ( note_clock ) != "undefined" ) { clearTimeout ( note_clock ); } // clear if counting already ##
     document.getElementById( c_div ).innerHTML = c_time; // return value to div ##
     if ( c_time > 0 ){ // continue ##
        ecoder_count_time = c_time; // update ##   
        note_clock = setTimeout ( "top.ecoder_count( '"+c_div+"', '"+( c_time - 1 )+"' )", 1000 ); // each second ##
     }
}

// ----------------------------------------------------------------------------------------------------------

// fade out boxes ##
function ecoder_fade ( id, opacStart, opacEnd, millisec ) { 
   
    var speed = Math.round(millisec / 100); //speed for each frame
    var timer = 0;

    //determine the direction for the blending, if start and end are the same nothing happens
    if(opacStart > opacEnd) {
        for(i = opacStart; i >= opacEnd; i--) {
            setTimeout("ecoder_fade_do(" + i + ",'" + id + "')",(timer * speed));
            timer++;
        }
    } else if(opacStart < opacEnd) {
        for(i = opacStart; i <= opacEnd; i++)
            {
            setTimeout("ecoder_fade_do(" + i + ",'" + id + "')",(timer * speed));
            timer++;
        }
    }
}

// change the opacity for different browsers
function ecoder_fade_do(opacity, id) {

    var object = document.getElementById(id).style;
    object.opacity = (opacity / 100);
    object.MozOpacity = (opacity / 100);
    object.KhtmlOpacity = (opacity / 100);
    object.filter = "alpha(opacity=" + opacity + ")";
    
} 

// ----------------------------------------------------------------------------------------------------------

// html title function ## remove mode ##
function ecoder_html_title ( file ) {
    
    var ecoder_title = 'home'; // declare default ##
    var ecoder_file = file; // file name from function ##
    if ( ecoder_file ) ecoder_title = ecoder_file; // add file if set ##    
    top.document.title = ecoder_name + ' | '+ ecoder_title; // apply title ##
    return false; // no return ##

}

// ----------------------------------------------------------------------------------------------------------

// track changes to editarea ##
var content_changed = 0; // swallow first change ( focus ) to editarea ##
function ecoder_changed () {	

    content_changed ++; // iterate to count changes ##
}

// ----------------------------------------------------------------------------------------------------------

// check if a variable is defined ##
function ecoder_var_defined ( variable ) {
    return (!(!(document.getElementById(variable))))
}

// ----------------------------------------------------------------------------------------------------------

// track id of top tab ##
function ecoder_track ( what, reference ) {

    // track tab ##
    if ( what == 'tab' ) {
        ecoder_tab = reference; // update variable ##
        //alert ( ecoder_tab );
    }
    // track tab ##
    if ( what == 'iframe' ) {  
        ecoder_iframe = reference; // update iframe reference ## -- document.getElementById ( reference )
        //alert ( ecoder_iframe );
    }
    //return false; // no return ##

}

// ----------------------------------------------------------------------------------------------------------

// recursive replace ##
function ecoder_replace_all ( str, replacements ) {

    for ( i = 0; i < replacements.length; i++ ) {
        var idx = str.indexOf( replacements[i][0] );
        while ( idx > -1 ) {
            str = str.replace( replacements[i][0], replacements[i][1] );
            idx = str.indexOf( replacements[i][0] );
        }
    }
    return str;
}

// ----------------------------------------------------------------------------------------------------------

// object checker ##
function ecoder_check_object(Id, Tag) {

  var o = document.getElementById(Id);
  if (o) { //alert ( '1 > found '+ o.id );
    if (Tag) { //alert ( '2 > found '+ o.id );
      if (o.tagName.toLowerCase() == Tag.toLowerCase()) { //alert ( '3 > found '+ o.id );
        return o;
      }
    } else {
      return o;
    }
  }
  return null;
}

// ----------------------------------------------------------------------------------------------------------

// loader & script test ##
function ecoder_loaded_base( mode ) {

    b_div = 'load_base';
    var elem = document.getElementById( b_div );
    elem.style.display = ( elem.style.display == "none" ) ? "" : "none";
    
    // also hide notes div -- get out hack ##
    //setTimeout( "ecoder_note ( 'note', 'no messages to display.', '', 'none' )", 0 ); // hide ##
    
}

// ----------------------------------------------------------------------------------------------------------

// get sizes ##
function ecoder_size() {

  var ecoder_width = 0, ecoder_height = 0;
  if( typeof( window.innerWidth ) == 'number' ) {
    // Non-IE
    ecoder_width = window.innerWidth;
    ecoder_height = window.innerHeight;
  } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
    // IE 6+ in 'standards compliant mode'
    ecoder_width = document.documentElement.clientWidth;
    ecoder_height = document.documentElement.clientHeight;
  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
    // IE 4 compatible
    ecoder_width = document.body.clientWidth;
    ecoder_height = document.body.clientHeight;
  }
  //window.alert( 'Width = ' + ecoder_width + 'Height = ' + ecoder_height );  
}

// ----------------------------------------------------------------------------------------------------------

// onload events ##
function addLoadEvent( func ) {

  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      oldonload();
      func();
    }
  }
}

// ----------------------------------------------------------------------------------------------------------

