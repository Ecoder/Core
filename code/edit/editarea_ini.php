<?php

/*
build edit_area ##
*/

if ( $_SESSION['editor'] == "delux" ) { // rich version ##    
    
    echo '
    <script type="text/javascript">
    // initialisation
    editAreaLoader.init({
	    id: "editarea"	// id of the textarea to transform		
	    ,start_highlight: true	// if start with highlight
	    ,allow_resize: false
	    ,allow_toggle: false
	    ,autocompletion: false // experimental tag completion ##
	    ,show_line_colors: true
	    ,toolbar: "search, |,go_to_line, |, undo, |, redo, |, change_smooth_selection, |,highlight, |,reset_highlight" // help
	    ,language: "en"
	    ,gecko_spellcheck: true
	    ,change_callback: "ecoder_changed"
	    '.$editarea['options'].' // read-only or editable ##
	    ,syntax: "'.$main['type'].'"	
	    ,syntax_selection_allow: "css,html,js,perl,php,python,ruby,robotstxt,sql,vb,xml"
	    ,EA_load_callback: "ealoaded" 
    }); 
    function ealoaded(id) { 
        editAreaLoader.setSelectionRange(id, 0, 0); // sets the cursor to position zero 
    }
    </script>';

} else { // basic version ##

    echo '
    <script type="text/javascript">
    // initialisation
    editAreaLoader.init({
	    id: "editarea"	// id of the textarea to transform		
	    ,start_highlight: false	// if start with highlight
	    ,allow_resize: false
	    ,allow_toggle: false
	    ,autocompletion: false // experimental tag completion ##
	    ,show_line_colors: false
	    ,toolbar: "search, |,go_to_line, |, undo, |, redo, |, change_smooth_selection, |,highlight, |,reset_highlight" // help
	    ,language: "en"
	    ,gecko_spellcheck: false
	    ,change_callback: "ecoder_changed"
	    '.$editarea['options'].' // read-only or editable ##
	    ,syntax: "'.$main['type'].'"	
	    ,syntax_selection_allow: "css,html,js,perl,php,python,ruby,robotstxt,sql,vb,xml"
	    ,EA_load_callback: "ealoaded" 
    }); 
    function ealoaded(id) { 
        editAreaLoader.setSelectionRange(id, 0, 0); // sets the cursor to position zero 
    }
    </script>';

}

?>
