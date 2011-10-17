<?php

/* 
file upload script ##
*/

include "../../code.php"; // single included settings file ##

// settings ##
$save['result_code'] = 0; // nada ##
$save['flag'] = 0; // not flagged ##

// trim details ##
@ecoder_request( $_POST['path'], $save['path'], '' ); // path ##

// Upload directory: remember to give it write permission!
$uploaddir = $code['root'].$save['path'];

// what file types do you want to disallow?
$blacklist = $_SESSION['upload_banned'];

// filter allowable file types from file type array ##
function ecoder_array_clean ( $str ) {
    if ( in_array ( $str, $_SESSION['upload_banned'] ) ) return false; else return true;
}
$allowed_filetypes = array_merge( array(), array_filter( $_SESSION['tree_file_types'], "ecoder_array_clean" ) );

// add dots to file types ##
function add_dots ( &$item1, $key, $prefix ) {
    $item1 = "$prefix$item1";
}
array_walk ( $allowed_filetypes, 'add_dots', '.' );
#print '<pre>'.print_r( $allowed_filetypes ,1 ).'</pre>';
#exit;

if ( !is_dir ( $uploaddir ) ) { // upload directory not there ##
	die ( $save['result'] = "Upload directory does not exists." );
}
if ( !is_writable ( $uploaddir ) ) { // can't write to directory ##
	die ( $save['result'] = "Upload directory is not writable.");
}
#if ( isset ( $_POST['file'] ) ) { // something sent ##
	if ( isset ( $_FILES['file'] ) ) { // file sent ##
		if ( $_FILES['file']['error'] != 0 ) { // no errors ##
			switch ( $_FILES['file']['error'] ) { // check file ##
				case 1:
					$save['result'] = 'the file is too big.'; // php installation max file size error
				    $save['flag'] = 1; // flagged ## //exit;
					break;
				case 2:
					$save['result'] = 'the file is too big.'; // form max file size error - DEPRECATED
				    $save['flag'] = 1; // flagged ## //exit;
					break;
				case 3:
					$save['result'] = 'only part of the file was uploaded.';
				    $save['flag'] = 1; // flagged ## //exit;
					break;
				case 4:
					$save['result'] = 'no file was uploaded.';
				    $save['flag'] = 1; // flagged ## //exit;
					break;
				case 6:
					$save['result'] = "missing a temporary folder.";
				    $save['flag'] = 1; // flagged ## //exit;
					break;
				case 7:
					$save['result'] = "failed to write file to disk";
				    $save['flag'] = 1; // flagged ## //exit;
					break;
				case 8:
					$save['result'] = "file upload stopped by extension";
				    $save['flag'] = 1; // flagged ## //exit;
					break;
			}
		} else {
			foreach ( $blacklist as $item ) {
				if ( preg_match ( "/$item\$/i", $_FILES['file']['name'] ) ) {
					$save['result'] = "invalid filetype";
					unset ( $_FILES['file']['tmp_name'] );					
				    $save['flag'] = 1; // flagged ## //exit;	
				} 
			}
			
			if ( $save['flag'] != 1 ) { // carry on ##
			
			    // Get the extension from the filename.
			    #$ext = substr($_FILES['file']['name'], strpos($_FILES['file']['name'],'.'), strlen($_FILES['file']['name'])-1);			    
                $ext_array = explode ( ".", $_FILES['file']['name'] ); // get extension ##
                $ext = '.' .end ( $ext_array ); // add dot and take last array item ##    
			    
			    // Check if the filetype is allowed, if not log error.
			    if ( !in_array ( $ext, $allowed_filetypes ) ){
				    $save['result'] = 'file type not allowed: '.$ext;
				    $save['flag'] = 1; // flagged ## //exit;
			    
			    } elseif ( !file_exists ( $uploaddir.$_FILES["file"]["name"] ) ) { // file does not already exist ##
				    // Proceed with file upload
				    if ( is_uploaded_file($_FILES['file']['tmp_name'])) {
					    //File was uploaded to the temp dir, continue upload process
					    if ( move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir . $_FILES['file']['name'])) {
						    // uploaded file was moved and renamed succesfuly. Display a message.
						    $save['result'] = "file uploaded";
						    $save['result_code'] = 1; // done ##
					    } else {
						    $save['result'] = "unknown upload error";
						    unset($_FILES['file']['tmp_name']);
					    }
				    } else {
					    //File was NOT uploaded to the temp dir
					    switch ( $_FILES['file']['error'] ) {
						    case 1:
							    $save['result'] = 'the file is too big.'; // php installation max file size error
							    break;
						    case 2:
							    $save['result'] = 'the file is too big.'; // form max file size error
							    break;
						    case 3:
							    $save['result'] = 'only part of the file was uploaded';
							    break;
						    case 4:
							    $save['result'] = 'no file was uploaded';
							    break;
						    case 6:
							    $save['result'] = "missing a temporary folder.";
							    break;
						    case 7:
							    $save['result'] = "failed to write file to disk";
							    break;
						    case 8:
							    $save['result'] = "file upload stopped by extension";
							    break;
					    }
				    }
			    } else { // There's a file with the same name
				    $save['result'] = "file already exists";
				    unset($_FILES['file']['tmp_name']);
				    
			    }
			    
			}
		}
	} else { // user did not select a file to upload
		$save['result'] = "select a file to upload.";
	}
#} else { // upload button was not pressed#
#	$save['result'] = "select a file to upload.";
#}

// send to confirm page ##
#echo $save['result'].' - '.$save['result_code'];
$save['header'] = "../../edit.php?mode=upload&path=".$save['path']."&file=upload file&report=".$save['result'].'&report_code='.$save['result_code'];
header ( "location: ".$save['header'] );


?>
