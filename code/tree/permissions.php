<?php

/* 
check if file is editable ##
*/

// settings ##
#$tree['permissions_write'] = 0; // assume not writeable ##
#$tree['permissions_read'] = 0; // assume not readable ##
$tree['editable'] = 'read'; // editable or read-only ##
$tree['class_li'] = 'no-read'; // li class ##

// delete ##
$tree['delete'] = 0; // nope ##
$tree['delete_link'] = '#'; // nope ##
$tree['delete_title'] = 'delete disabled'; // nope ##
$tree['delete_note'] = 'you cannot delete '.$tree['file_name'].' as you do not have permission'; // nope ##
$tree['delete_icon'] = '_inactive'; // no go icon ##

// rename ##
$tree['rename'] = 0; // nope ##
$tree['rename_link'] = '#'; // nope ##
$tree['rename_title'] = 'rename disabled'; // nope ##
$tree['rename_note'] = 'you cannot rename '.$tree['file_name'].' as you do not have permission'; // nope ##
$tree['rename_icon'] = '_inactive'; // no go icon ##

if ( is_readable ( $code['root'].$tree['path'].'/'.$tree['file_name'] ) ) { // read-only ##
    #$tree['permissions_read'] = 1; // reading ok ##
    $tree['class_li'] = 'read'; // li class ##
    
} else { // no readable -- should not be shown ##
    $tree['class_li'] = 'no-read'; // li class ##
    
}   

if ( is_writeable ( $code['root'].$tree['path'].'/'.$tree['file_name'] ) ) { // read / write ##      
    
    #$tree['permissions_write'] = 1; // editing ok ##
    $tree['editable'] = 'edit'; // read-only ##
    $tree['class_li'] = ''; // li class ##
    
    if ( $tree['file_type'] == 'file' ) { // check if file is deletable ##
        
        // delete ##
        $tree['delete'] = 1; // yes ##
        $tree['delete_link'] = 'top.del(\''.$tree['path'].'\', \''.$tree['file_name'].'\', \''.$tree['file_type'].'\' );'; // link ##
        $tree['delete_icon'] = ''; // swap icon ##
        $tree['delete_title'] = 'delete file'; // title ##
        $tree['delete_note'] = 'you can delete the file '.$tree['file_name']; // delete note ##
        
        // rename ##
        $tree['rename'] = 1; // yes ##
        $tree['rename_link'] = 'top.ecoder_files( \'main\', \'rename\', \''.$tree['path'].'\', \''.$tree['file_name'].'\', \''.$tree['file_type'].'\' );'; // link ##
        $tree['rename_icon'] = ''; // swap icon ##
        $tree['rename_title'] = 'rename file'; // title ##
        $tree['rename_note'] = 'you can rename the file '.$tree['file_name']; // rename note ##
        
    } elseif ( $tree['file_type'] == 'folder' ) { // check if folder is deletable ##
            
        // check if folder has contents - and warm about that - TODO ##
        if ( ecoder_check_dir ( $code['root'].$tree['path'].$tree['file_name'] ) === true ) {
            
            // delete ##
            $tree['delete'] = 1; // yes ##
            $tree['delete_link'] = 'top.ecoder_files( \'main\', \'delete\', \''.$tree['path'].'\', \''.$tree['file_name'].'\', \''.$tree['file_type'].'\' );'; // link ##
            $tree['delete_icon'] = ''; // swap icon ##
            $tree['delete_title'] = 'delete folder'; // delete note ##
            $tree['delete_note'] = 'you can delete the folder '.$tree['file_name']; // delete note ##

            // rename ##
            $tree['rename'] = 1; // yes ##
            $tree['rename_link'] = 'top.ecoder_files( \'main\', \'rename\', \''.$tree['path'].'\', \''.$tree['file_name'].'\', \''.$tree['file_type'].'\' );'; // link ##
            $tree['rename_icon'] = ''; // swap icon ##
            $tree['rename_title'] = 'rename folder'; // title ##
            $tree['rename_note'] = 'you can rename the folder '.$tree['file_name']; // rename note ##

        } else {
            
            $tree['delete_note'] = 'you cannot delete the folder '.$tree['file_name'].' because it is not empty'; // extend delete note ##
        
        }        
    }    
} else {
    if ( $tree['file_type'] == 'file' ) { // check if file is deletable ##
        $tree['editable'] = 'read'; // read-only ##
        $tree['file_action'] = 'open'; // update action ##
    }
}  
    
// clear cache ##
clearstatcache();

?>
