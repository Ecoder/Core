<?php
include "../../code.php"; // single included settings file ##

final class RenameStatus {
	const SUCCESS="SUCCESS";
	const NAMEALREADYEXISTS="NAMEALREADYEXISTS";
	const ORIGNOTFOUND="ORIGNOTFOUND";
}

@ecoder_request( $_POST['path'], $save['path'], '' ); // path ##
@ecoder_request( $_POST['file'], $save['file'], '' ); // file ##
@ecoder_request( $_POST['file_new'], $save['file_new'], '' ); // file ##
@ecoder_request( $_POST['ext'], $save['ext'], '' ); // extension -- if file ##
@ecoder_request( $_POST['type'], $save['type'], '' ); // type -- file or folder ##

$save['file_new']=preg_replace('/[^0-9A-Za-z.]/', '_',$save['file_new']);

$save['rename'] = $code['root'].$save['path'].$save['file'];
$save['rename_new'] = $code['root'].$save['path'].$save['file_new'];
$save['permissions'] = $code['permissions_dir'];

if ( $save['type'] == 'file' ) { // change permissions ##
	$save['rename_new'] = $code['root'].$save['path'].$save['file_new'].'.'.$save['ext'];
}

if (file_exists($save['rename']) && ($save['file'] || $save['path'])) {    
	if (!file_exists($save['rename_new'])) {    
		ecoder_rename ( $save['rename'], $save['rename_new'], $save['permissions'] ); // rename ##
		$save['result'] = $save['type']." <strong>".$save['file']."</strong> renamed <strong>".$save['file_new'].'.'.$save['ext']."</strong>"; // confirm ##
		$save['result_code'] = 1;
	} else { // new name exists ##        
		$save['result'] = 'the '.$save['type'].' <strong>'.$save['file_new'].'.'.$save['ext'].'</strong> already exist, please choose a different name.';
		$save['result_code'] = 0;
	} 
} else { // error, not found ##
	$save['result'] = 'the '.$save['type'].' <strong>'.$save['file'].'</strong> does not exist, please close the tab and try again.';
	$save['result_code'] = 0;
}

echo json_encode(array("msg"=>$save['result'],"code"=>$save['result_code']));