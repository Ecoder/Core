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
		$result=@mkdir($path."/".$name,$code['permissions_dir']);
		if ($result) {
			Output::add("result","success");
			return;
		} else {
			Output::add("error","unknown");
			return;
		}
	}
}