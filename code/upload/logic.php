<?php
class UploadStatuses { //ENUM
	const DIRNOEXIST="dirnoexist";
	const DIRNOWRITE="dirnowrite";
	const NOUPLSLCTD="nouplslctd";
	const FILETOOBIG="filetoobig";
	const PARTIALUPL="partialupl";
	const MISSINGTMP="missingtmp";
	const WRTDSKFAIL="wrtdskfail";
	const UPLSTOPEXT="uplstopext";
	const INVALDTYPE="invaldtype";
	const FLRDYEXSTS="flrdyexsts";
	const UNKNOWNERR="unknownerr";
	const UPLSUCCESS="uplsuccess";
}

class Upload extends Controller {
	public static $DFLT="dialog";
	private $path;
	
	public function __construct() {
		$i=Input::_get();
		$this->path=($i->path ?: "");
	}
	
	public function dialog() {
		global $translations;
		ob_start();
		include "code/upload/dialog.php";
		$html=ob_get_clean();
		Output::add("html",$html);
		return;
	}
	
	public function save() {
		global $code,$translations;
		
		$filename=(isset($_SERVER['HTTP_X_FILE_NAME']) ? $_SERVER['HTTP_X_FILE_NAME'] : '');
		$contentlength=(isset($_SERVER['CONTENT_LENGTH']) ? $_SERVER['CONTENT_LENGTH'] : '');
		$path=$code['root'].(isset($_SERVER['HTTP_X_FILE_PATH']) ? $_SERVER['HTTP_X_FILE_PATH'] : '');
		
		if (empty($filename) || empty($contentlength)) {
			Output::add("error",UploadStatuses::NOUPLSLCTD);
			return;
		}
		
		// what file types do you want to disallow?
		$blacklist = $_SESSION['upload_banned'];

		// filter allowable file types from file type array ## TODO okay, lambda isn't really a readability improvement here. Fix!
		$allowed_filetypes=array_filter($_SESSION['tree_file_types'],function($str){
			return !in_array($str,$_SESSION['upload_banned']);
		});

		foreach ($allowed_filetypes as $k=>$v) {
			$allowed_filetypes[$k]=".".$v;
		}
		
		if ( !is_dir ( $path ) ) { // upload directory not there ##
			Output::add("error",UploadStatuses::DIRNOEXIST);
			return;
		}
		if ( !is_writable ( $path ) ) {
			Output::add("error",UploadStatuses::DIRNOWRITE);
			return;
		}
		
		foreach ($blacklist as $item) {
			if (strEndsWith($filename,$item,false)) {
				Output::add("error",UploadStatuses::INVALDTYPE);
				return;
			}
		}
		
		$exta=explode(".",$filename);
		$ext='.'.end($exta);
		unset($exta);
		if (!in_array($ext,$allowed_filetypes)) {
			Output::add("error",UploadStatuses::INVALDTYPE);
			return;
		}
		
		if (file_exists($path.$filename)) {
			Output::add("error",UploadStatuses::FLRDYEXSTS);
			return;
		}
		
		$arr=explode(",",file_get_contents("php://input"));
		$in=base64_decode($arr[1]);
		file_put_contents($path.$filename,$in);
		
		Output::add("status",1);
		return;
	}
}

$action=(isset($_GET['action']) ? $_GET['action'] : Upload::$DFLT);
Upload::_init($action);