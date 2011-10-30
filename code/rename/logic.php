<?php
/*
 * TODO
 *  - Move nonspecific php up
 *  - Use RenameStatus and thus also translations
 *  - Move dialog up from editor
 *  - Refactor view into dialog (inc top.ecoder_files)
 *  - Pass POST JSON instead of shitload GET or POST params
 * LATER
 *  - Make loadable via index
 *  - Extend Controller to be more dynamic, input validation, ...
 */
abstract class Controller {
	public static $DFLT;
	
	public static function _init($action="") {
		if (empty($action)) {
			$action=static::$DFLT;
		}
		$st=new static();
		call_user_func(array($st,$action));
	}
}

final class RenameStatus {
	const SUCCESS="SUCCESS";
	const COULDNOTBERENAMED="COULDNOTBERENAMED";
	const NAMEALREADYEXISTS="NAMEALREADYEXISTS";
	const ORIGNOTFOUND="ORIGNOTFOUND";
}

class Rename extends Controller {
	public static $DFLT="form";
	private $path,$name,$type,$ext;
	
	public function form() {
		global $code; //sigh
		//This should mostly be moved to constructor
		$this->path=(isset($_GET['path']) ? $_GET['path'] : "");
		$this->name=(isset($_GET['file']) ? $_GET['file'] : "");
		$this->type=(isset($_GET['type']) ? $_GET['type'] : "");
		$this->ext="";
		if ($this->_isFile()) {
			$exta=explode('.',$this->name);
			$this->ext=end($exta);
		}
		//Until here
		$vv=new StdClass(); //ViewVariables
		$vv->pframename='rename_'.$this->type;
		$vv->fframename=ecoder_iframe_clean($this->path.$vv->pframename);
		include "code/rename/tpl.php";
	}
	
	public function save() {
		global $code; //sigh
		$this->path=(isset($_POST['path']) ? $_POST['path'] : "");
		$this->name=(isset($_POST['file']) ? $_POST['file'] : "");
		$this->type=(isset($_POST['type']) ? $_POST['type'] : "");
		$this->ext=(isset($_POST['ext']) ? $_POST['ext'] : "");
		$newname=(isset($_POST['file_new']) ? $_POST['file_new'] : "");
		$newname=preg_replace('/[^0-9A-Za-z.]/', '_',$newname);

		$filepath="%s%s%s%s%s";
		$orig=sprintf($filepath,$code['root'],$this->path,$this->name,"","");
		$new=sprintf($filepath,$code['root'],$this->path,$newname,($this->_isFile() ? "." : ""),($this->_isFile() ? $this->ext : ""));

		$res="";
		$resc="";
		if (file_exists($orig) && ($this->name || $this->path)) {    
			if (!file_exists($new)) {
				$res=@rename($orig,$new);
				$res=$this->type." <strong>".$this->name."</strong> renamed <strong>".$newname.'.'.$this->ext."</strong>";
				$resc=1;
			} else { // new name exists ##        
				$res='the '.$this->type.' <strong>'.$newname.'.'.$this->ext.'</strong> already exist, please choose a different name.';
				$resc=0;
			} 
		} else { // error, not found ##
			$res='the '.$this->type.' <strong>'.$this->name.'</strong> does not exist, please close the tab and try again.';
			$resc=0;
		}

		echo json_encode(array("msg"=>$res,"code"=>$resc));
	}
	
	private function _isFile() {
		return ($this->type=="file");
	}
}

$action=(isset($_GET['action']) ? $_GET['action'] : Rename::$DFLT);
Rename::_init($action);