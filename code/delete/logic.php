<?php

class Delete extends Controller {
	public static $DFLT="dialog";
	private $path,$name,$type;
	
	public function __construct() {
		$i=Input::_get();
		$this->path=($i->path ?: "");
		$this->name=($i->file ?: "");
		$this->type=($i->type ?: "");
	}
	
	public function dialog() {
		global $translations,$code;
		ob_start();
		include "code/delete/dialog.php";
		$html=ob_get_clean();
		Output::add("html",$html);
		return;
	}
	
	public function save() {
		global $code;
		$res="";
		$resc=-1;
		$fullpath=$code['root'].$this->path.$this->name;
		if (file_exists($fullpath) && ($this->name || $this->path)) {
			if ($this->type=="file") {
				ecoder_delete_file($fullpath);
				$res="file <strong>".$this->name."</strong> deleted";
				$resc=1;
			} else {
				if (ecoder_check_dir($fullpath)===true) {
					ecoder_delete_dir($fullpath);
					$res="folder <strong>".$save['file']."</strong> deleted";
					$resc=1;
				} else {
					$res='you cannot delete the folder <strong>'.$this->name.'</strong> as it is not empty';
					$resc=0;
				}
			}
		} else {
			$res='the '.$this->type.' <strong>'.$this->name.'</strong> does not exist.';
			$resc=0;
		}
		
		Output::add("msg",$res);
		Output::add("code",$resc);
		return;
	}
}

$action=(isset($_GET['action']) ? $_GET['action'] : Delete::$DFLT);
Delete::_init($action);