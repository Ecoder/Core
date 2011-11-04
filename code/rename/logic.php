<?php
/*
 * TODO
 *  - Use RenameStatus and thus also translations
 * LATER
 *  - Make loadable via index
 *  - Extend VC-framework so a controller can have a definition
 *			of its actions including needed input.
 */
final class RenameStatus {
	const SUCCESS="SUCCESS";
	const COULDNOTBERENAMED="COULDNOTBERENAMED";
	const NAMEALREADYEXISTS="NAMEALREADYEXISTS";
	const ORIGNOTFOUND="ORIGNOTFOUND";
}

class Rename extends Controller {
	public static $DFLT="dialog";
	private $path,$name,$type,$ext;
	
	public function __construct() {
		$i=Input::_get();
		$this->path=($i->path ?: "");
		$this->name=($i->file ?: "");
		$this->type=($i->type ?: "");
		$this->ext=($i->ext ?: "");
	}
	
	public function dialog() {
		global $translations;
		ob_start();
		include "code/rename/dialog.php";
		$html=ob_get_clean();
		Output::add("html",$html);
		return;
	}
	
	public function save() {
		global $code; //sigh
		$newname=($i->file_new ?: "");
		$newname=preg_replace('/[^0-9A-Za-z.-_]/', '_',$newname);

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
		Output::add("msg",$res);
		Output::add("code",$resc);
		return;
	}
	
	private function _isFile() {
		return ($this->type=="file");
	}
}

$action=(isset($_GET['action']) ? $_GET['action'] : Rename::$DFLT);
Rename::_init($action);