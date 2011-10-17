<?php

/* 
sort out all the possible options for the main area
*/

@ecoder_request( $_GET['mode'], $main['mode'], '' ); // mode or action ##
@ecoder_request( $_GET['path'], $main['path'], '' ); // path to file to process ##
@ecoder_request( $_GET['file'], $main['file'], '' ); // file to process ##
@ecoder_request( $_GET['type'], $main['type'], '' ); // type of file ##
@ecoder_request( $_GET['report'], $main['report'], '' ); // report / confirm ##
@ecoder_request( $_GET['report_code'], $main['report_code'], '' ); // report / confirm ##
@ecoder_request( $_GET['shut'], $main['shut'], 1 ); // close override ##

//For codemirror (TODO) cleanup later
$convertFileTypes=array("file"=>"file","folder"=>"folder","php"=>"application/x-httpd-php-open","js"=>"text/javascript","html"=>"text/html","css"=>"text/css","text"=>"text/plain");
$main["cmMime"]=$convertFileTypes[$main['type']];
$main["isReadOnly"]=false;

// defaults ##
$main['lock'] = 0; // editable ##
$main['active'] = 0; // inactive ##
$main['save'] = 0; // no save script ##
$main['save_type'] = $main['mode']; // type of save action for javascript ##
$main['auto_save'] = 0; // auto save button ##
$main['editor_swap'] = 0; // editor swap button ##
$save['contents'] = ''; // save content from file ##
$save['file_loaded'] = 0; // file loaded ##
$save['target']="";
$html['title'] = 'no file loaded';
$html['title_note'] = ''; // notes about file ##
$main['frame_clean'] = ''; // nada ##
$main['tabs'] = 0; // not yet ##
$editarea['editable'] = 'true';

// buttons ##
$main['nav']['save'] = 0; // save ## 
$main['nav']['save_note'] = ''; // nada ##
$main['nav']['reload'] = 1; // reload ## 
$main['nav']['close'] = 1; // close ## 
if ( $main['shut'] == 0 ) { $main['nav']['close'] = 0; } // file may not be closed ## 

// case switch ##
if ( $main['mode'] == 'home' ) { // home ##

    $main['nav']['close'] = 0; // close ##
    $main['save'] = 1; // save script ##

} elseif ( $main['mode'] == 'delete' ) { // deleting file || folder ##

    $html['title'] = $main['file'];
    $main['file'] = 'delete_'.$main['type'];
    $main['frame_clean'] = ecoder_iframe_clean ( $main['path'].'delete_'.$main['type'] ); // for close and refresh
    $main['save'] = 1; // save script ##
    $main['nav']['save'] = 1; // save ## 
    $main['active'] = 1; // active ##
    $main['nav']['reload'] = 0; // reload ## 

} elseif ( $main['mode'] == 'rename' ) { // renaming file || folder ##

    $html['title'] = $main['file'];
    $main['file'] = 'rename_'.$main['type'];
    $main['frame_clean'] = ecoder_iframe_clean ( $main['path'].'rename_'.$main['type'] ); // for close and refresh
    $main['save'] = 1; // save script ##
    $main['nav']['save'] = 1; // save ## 
    $main['nav']['save_note'] = 'enter the new '.$main['type'].' name'; //
    $main['active'] = 1; // active ##
    $main['nav']['reload'] = 0; // reload ##

} elseif ( $main['mode'] == 'add' ) { // adding new file ##
    
    $html['title'] = 'new '.$main['type'];
    $main['file'] = 'add_'.$main['type'];
    $main['frame_clean'] = ecoder_iframe_clean ( $main['path'].'add_'.$main['type'] ); // for close and refresh
    $main['nav']['save'] = 1; // save ## 
    $main['nav']['save_note'] = 'enter a '.$main['type'].' name'; // save note ##

} elseif ( $main['mode'] == 'upload' ) { // upload file ##
    
    $html['title'] = 'upload file';
    $main['file'] = 'upload file';
    $main['frame_clean'] = ecoder_iframe_clean ( $main['path'].'upload file' ); // for close and refresh
    $main['nav']['save'] = 1; // save ## 
    $main['nav']['save_note'] = 'select a file to upload'; // save note ##
    $main['nav']['reload'] = 0; // reload ##
    
} elseif ( $main['mode'] == 'edit' || $main['mode'] == 'read' ) { // edit / read ##

    // get file name, without dot or slash ##    
    $main['frame_clean'] = ecoder_iframe_clean ( $main['path'].$main['file'] ); // for close and refresh ##
    $main['active'] = 1; // active ##
    $main['auto_save'] = 1; // auto save button ##
    $main['editor_swap'] = 1; // editor swap button ##
    $save['file_loaded'] = 1; // file loaded ##
    
    if ( $main['file'] && $main['type'] ) { // loaded ##

        if ( $main['mode'] == 'read' ) { // read-only ##       
            $html['title'] = $main['file']; 
            $html['title_note'] = ' [READ ONLY]';
            $editarea['editable'] = 'false'; // pass to editarea textarea ##
						
						$main["isReadOnly"]=true;
            
        } else { // editable ##
            $main['save'] = 1; // save script ##
            $html['title'] = $main['file'];            
            $main['nav']['save'] = 1; // save ## 
            $main['nav']['save_note'] = 'saving...'; // save note ##
  
        }
        
        $main['active'] = 1; // activate ##
            
    }

} // modes ##