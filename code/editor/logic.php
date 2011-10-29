<?php
//TODO later only include relevant pieces
/***LOGIC***/
@ecoder_request( $_GET['mode'], $main['mode'], '' ); // mode or action ##
@ecoder_request( $_GET['path'], $main['path'], '' ); // path to file to process ##
@ecoder_request( $_GET['file'], $main['file'], '' ); // file to process ##
@ecoder_request( $_GET['type'], $main['type'], '' ); // type of file ##
@ecoder_request( $_GET['report'], $main['report'], '' ); // report / confirm ##
@ecoder_request( $_GET['report_code'], $main['report_code'], '' ); // report / confirm ##
@ecoder_request( $_GET['shut'], $main['shut'], 1 ); // close override ##

$file=new stdClass();		//For things about this file
$editor=new stdClass();	//For more general things about the editor

//For codemirror (TODO) cleanup later
$convertFileTypes=array("file"=>"file","folder"=>"folder","php"=>"application/x-httpd-php-open","js"=>"text/javascript","html"=>"text/html","css"=>"text/css","text"=>"text/plain");
$file->cmMime=$convertFileTypes[$main['type']];
$file->isReadOnly=false;	//TODO maybe we should turn this one around...
$file->path=$main['path'];
$file->name=$main['file'];
$file->fullpath=$code['root'].'/'.$file->path.'/'.$file->name;

$file->canClose=($main['shut']!=0);	//Later, this should be allowed by default. But for now (home tab) allowing override
$file->canDelete=($main['mode'] == 'edit' && $main['shut'] == 1);
$file->canRename=($main['mode'] == 'edit' && $main['shut'] == 1);

$editor->synhl=($_SESSION['editor'] == 'delux');
// defaults ##
$html['title_note'] = ''; // notes about file ##

// buttons ##
$main['nav']['save_note'] = ''; // nada ##
// get file name, without dot or slash ##    
$main['frame_clean'] = ecoder_iframe_clean ( $file->path.$file->name ); // for close and refresh ##

if ($file->name && $main['type'] ) { // loaded ##
	if ( $main['mode'] == 'read' ) { // read-only ##       
		$html['title_note'] = ' [READ ONLY]';
		$file->isReadOnly=true;
	} else { // editable ##
		$main['nav']['save_note'] = 'saving...'; // save note ##
	}
}


/***TABS/HOME***/
if ( !file_exists ( $code['root'].$tabs['home'].".txt" ) ) { // base file not found ##

    // make file ##
    ecoder_copy ( getcwd()."/docs/home.txt", $code['root'].$tabs['home'].".txt", $code['permissions_file'] );
    
    if ( is_writable( $code['root'].$tabs['home'].".txt" ) ) { // can write ##
    
        // add to file ##
        file_put_contents ( $code['root'].$tabs['home'].".txt", $tabs['home_content'] );
        $main['report'] = $tabs['home'].'.txt added';
        $main['report_code'] = 1; // error code for info window ##

    } else { // can't write file ##
        
        $main['report'] = 'error creating home tab';
        $main['report_code'] = 0; // error code for info window ##
        
    }
}

/***NAV.AUTOSAVE***/
// compile path for editor swap ##

$file->autosaveStatus=($file->isReadOnly ? -1 : ($code['autosave']==1 ? 1 : 0));

/***FILE.BACKUP***/
function makeBackup() {
	global $file,$code; //Yuck
	$backupPfx=".";
	if ($code['backup']==1) {
		$backupPath=$code['root'].'/'.$file->path.'/'.$backupPfx.$file->name;
		ecoder_copy($file->fullpath,$backupPath,$code['permissions_file']);
	}
}

makeBackup();

/**FILE**/
if ( file_exists ($file->fullpath) ) {
	$file->contentraw=file_get_contents($file->fullpath);
	$file->content=trim(htmlspecialchars($file->contentraw));
}