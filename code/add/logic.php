<?php
class Add extends Controller {
	public static $DFLT="dialog";
	private $path,$type;
	
	public function __construct() {
		$i=Input::_get();
		$this->path=($i->path ?: "");
		$this->type=($i->type ?: "");
	}
	
	public function dialog() {
		global $translations;
		$vv=new stdClass();
		$vv->allowedext=array_filter($_SESSION['tree_file_types'],array($this,"_isFiletypeAllowed"));
		ob_start();
		include "code/add/dialog.php";
		$html=ob_get_clean();
		Output::add("html",$html);
		return;
	}
	
	public function save() {
		global $translations,$code;
		$i=Input::_get();
		$ext=($i->ext ?: "");
		$name=($i->file ?: "");
		$perm=$code['permissions_dir'];
		$cleanName=preg_replace('/[^0-9A-Za-z.-_]/','_',$name);
		$fullName=$code['root'].$this->path.$cleanName;
		$nameNoPath=$cleanName; // results file ##

		if ($ext) { // add extension if passed ##
			$fullName.='.'.$ext;
			$perm=$code['permissions_file'];
			$nameNoPath=$cleanName.".".$ext; 
		}

		// check if file / folder exists already ##
		if (!file_exists($fullName)) {    
			if ($this->type=='file') { // add file ##
				$tpl='code/save/template.'.$ext;
				if (!file_exists($tpl)) { // template not found ##               
					$res='the file type <strong>'.$ext.'</strong> is not allowed.';
					$resc=0;
				} else { // template found ##        
					ecoder_copy($tpl,$fullName,$perm); // make new file ##
					$res="the file <strong>".$nameNoPath."</strong> has been added to the folder <strong>".$this->path."</strong>";
					$resc=1;
				} // template found
			} else { // make directory ##  
				ecoder_mkdir($fullName,$perm);       
				$res="the folder <strong>".$nameNoPath."</strong> has been added to the folder <strong>".$this->path."</strong>";
				$resc=1;
			}
		} else { // error, already exists ##
			$res='a '.$this->type.' named <strong>'.$nameNoPath.'</strong> already exists in the folder <strong>'.$this->path.'</strong>.';
			$resc=0;
		}
		
		Output::add("msg",$res);
		Output::add("code",$resc);
		return;
	}
	
	private function _isFiletypeAllowed($str) {
		return (!in_array($str,array("htaccess","ini")));
	}
}

$action=(isset($_GET['action']) ? $_GET['action'] : Add::$DFLT);
Add::_init($action);