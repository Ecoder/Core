<?php
class FileManipulation {

	public static function remove($file,$allowedRecursive=false) {
		if (!file_exists($file)) {
			Output::add("error","notexist");
			return;
		}
		if (!is_writable($file)) {
			Output::add("error","notwritable");
			return;
		}

		$result=false;
		if (is_dir($file)) {
			if (!self::_dirIsEmpty($file)) {
				if (!$allowedRecursive) {
					Output::add("error","dirnotempty");
					return;
				} else {
					$result=@self::recursiveDirRemove($file);
				}
			} else {
				$result=@rmdir($file);
			}
		} else {
			$result=@unlink($file);
		}

		if ($result) {
			Output::add("result","success");
			return;
		} else {
			Output::add("error","unknown");
			return;
		}
	}

	public static function recursiveDirRemove($dir) {
		$result=true;
		$objects=scandir($dir);
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
				if (filetype($dir."/".$object) == "dir") {
					$result=(self::recursiveDirRemove($dir."/".$object) ? $result : false);
				} else {
					$result=(@unlink($dir."/".$object) ? $result : false);
				}
			}
		}
		unset($objects);
		$result=(@rmdir($dir) ? $result : false);
		return $result;
	}

	private static function _dirIsEmpty($file) {
		$files=opendir($file);
		$c=0;
		while (readdir($files)) {
			$c++;
		}
		return ($c<=2);
	}

	public static function rename($file,$newname) {
		if (empty($newname)) {
			Output::add("error","emptynewname");
			return;
		}
		if (!file_exists($file)) {
			Output::add("error","originalnotexist");
			return;
		}
		$sfi=new SplFileInfo($file);
		if (file_exists($sfi->getPath()."/".$newname)) {
			Output::add("error","newalreadyexist");
			return;
		}
		if ($sfi->getBaseName()==$newname) {
			Output::add("error","origandnewequal");
			return;
		}
		if (!$sfi->isWritable()) {
			Output::add("error","notwritable");
			return;
		}

		$newname=preg_replace('/[^0-9A-Za-z.-_]/', '_',$newname);
		$result=@rename($file,$sfi->getPath()."/".$newname);

		if ($result) {
			Output::add("result","success");
			return;
		} else {
			Output::add("error","unknown");
			return;
		}
	}

	public static function addFolder($path,$name) {
		global $code;
		if (empty($path)) {
			Output::add("error","nopathspecified");
			return;
		}
		if (empty($name)) {
			Output::add("error","nonamespecified");
			return;
		}
		if (file_exists($path."/".$name)) {
			Output::add("error","alreadyexists");
			return;
		}
		if (!is_writable($path)) {
			Output::add("error","notwritable");
			return;
		}
		$name=preg_replace('/[^0-9A-Za-z.-_]/','_',$name);
		$result=@mkdir($path."/".$name,$code['permissions_dir']);
		if ($result) {
			Output::add("result","success");
			return;
		} else {
			Output::add("error","unknown");
			return;
		}
	}

	public static function addFile($path,$name) {
		global $code;
		if (empty($path)) {
			Output::add("error","nopathspecified");
			return;
		}
		if (empty($name)) {
			Output::add("error","nonamespecified");
			return;
		}
		if (file_exists($path."/".$name)) {
			Output::add("error","alreadyexists");
			return;
		}
		if (!is_writable($path)) {
			Output::add("error","notwritable");
			return;
		}

		$name=preg_replace('/[^0-9A-Za-z.-_]/','_',$name);
		$template="template.txt";
		if (self::_strEndsWith($name, ".css")) {
			$template="template.css";
		} else if (self::_strEndsWith($name, ".html")) {
			$template="template.html";
		} else if (self::_strEndsWith($name, ".js")) {
			$template="template.js";
		} else if (self::_strEndsWith($name, ".php")) {
			$template="template.php";
		}

		$result=@copy("code/save/".$template,$path."/".$name);
		if ($result) {
			chmod($path."/".$name,$code['permissions_file']);
			Output::add("result","success");
			return;
		} else {
			Output::add("error","unknown");
			return;
		}
	}

	//http://stackoverflow.com/a/619725
	private static function _strEndsWith($string, $test) {
		$strlen = strlen($string);
		$testlen = strlen($test);
		if ($testlen > $strlen) return false;
		return substr_compare($string, $test, -$testlen) === 0;
	}

	private static function _strEndsWithAnyOf($string,$testarr) {
		$res=false;
		foreach ($testarr as $test) {
			$res+=self::_strEndsWith($string, $test);
		}
		return $res;
	}

	public static function upload() {
		global $cnf;
		$path=(isset($_SERVER['HTTP_X_FILE_PATH']) ? $_SERVER['HTTP_X_FILE_PATH'] : '');
		$filename=(isset($_SERVER['HTTP_X_FILE_NAME']) ? $_SERVER['HTTP_X_FILE_NAME'] : '');
		$contentlength=(isset($_SERVER['CONTENT_LENGTH']) ? $_SERVER['CONTENT_LENGTH'] : '');

		if (empty($filename) || empty($contentlength)) {
			Output::add("error","nouploadselected");
			return;
		}

		if ((!is_dir($path)) || (!is_writable($path))) {
			Output::add("error","pathnotwritable");
			return;
		}

		$whitelist=$cnf['uploadWhitelist'];
		if(!self::_strEndsWithAnyOf($filename, $whitelist)) {
			Output::add("error","invalidextension");
			return;
		}

		if (file_exists($path.'/'.$filename)) {
			Output::add("error","alreadyexists");
			return;
		}

		$arr=explode(",",file_get_contents("php://input"));
		$in=base64_decode($arr[1]);
		$res=file_put_contents($path.'/'.$filename,$in);
		if ($res) {
			Output::add("result","success");
			return;
		} else {
			Output::add("error","unknown");
			return;
		}
	}

	public static function getFileEditingInfo($file) {
		$sfi=new SplFileInfo($file);
		$content=trim(htmlspecialchars(file_get_contents($sfi->getRealPath())));
		$ext=$sfi->getExtension();
		$mimes=array("php"=>"application/x-httpd-php-open","js"=>"text/javascript","html"=>"text/html","css"=>"text/css","text"=>"text/plain","cpp"=>"text/x-c++src", "c"=>"text/x-csrc","py"=>"text/x-python","pl"=>"text/x-perl");
		$cmMime=$mimes[$ext];
		Output::add("content",$content);
		Output::add("cmMime",$cmMime);
		Output::add("isWritable",$sfi->isWritable());
	}

	public static function editSave($file,$newcontent) {
		if (get_magic_quotes_gpc()) {
			$newcontent=stripslashes($newcontent);
		}

		if (!file_exists($file)) {
			Output::add("error","filenotexist");
			return;
		}

		if (!is_file($file)) {
			Output::add("error","notafile");
			return;
		}

		if (!is_writable($file)) {
			Output::add("error","notwritable");
			return;
		}

		$handler=fopen($file,'w');
		$res=fwrite($handler,$newcontent);
		fclose($handler);
		if ($res!==false) {
			Output::add("result","success");
			return;
		} else {
			Output::add("error","unknown");
			return;
		}
	}
}