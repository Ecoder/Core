<?php
/**
 * TreeNode is something close to DirectoryIterator, but with
 *	public properties so that they can be json_encode'd
 *	Also making it a recursive object
 *	The code isn't so clean, but because of the requirements
 *	I see no alternatives
 */
class TreeNode {
	const HIDDEN='.';

	const TYPE_DIR="dir";
	const TYPE_FILE="file";

	public $name,$type,$path,$pathname,$ext="",$subtype="unknown";
	public $children;

	public function __construct($pathname,$showHidden=true) {
		$sfi=new SplFileInfo($pathname);
		$this->_getPropertiesFromSfi($sfi);

		if ($sfi->isDir()) {
			$this->children=array();
			$di=new DirectoryIterator($pathname);
			foreach ($di as $f) {
				if ($f->isDot()) { continue; }
				if (substr($f->getFilename(),0,1)==self::HIDDEN && $showHidden===false) { continue; }
				$this->children[]=new TreeNode($f->getPathname(),$showHidden);
			}
			usort($this->children,array($this,"_sortMyChildrenCallback"));
		} else {
			$this->children=null;
		}
	}

	public static function init() {
		global $cnf,$code;
		$i=Input::_get();
		$showHidden=($i->showHidden ?: $cnf["showHidden"]);
		$node=new TreeNode(realpath($code['root']),$showHidden);
		Output::add("tree",$node);
	}

	private function _getPropertiesFromSfi(SplFileInfo $sfi) {
		//Dirty solution for the subtypes for now
		//TODO / TOFIX when we introduce better actions
		$extToSubtype=array("html"=>"html","script"=>"js","css"=>"css","text"=>array("txt","htaccess","ini"),"php"=>"php","cpp"=>"cpp","c"=>array("c","h"),"py"=>"py","pl"=>"pl");

		$this->name=$sfi->getFilename();
		$this->type=$sfi->getType();
		$this->path=$sfi->getPath();
		$this->pathname=$sfi->getPathname();
		//Could use $sfi->getExtension() but that's only
		//available from php 5.3.6
		$this->ext=pathinfo($sfi->getPathname(),PATHINFO_EXTENSION);
		foreach ($extToSubtype as $k=>$v) {
			if (is_array($v)) {
				if (!in_array($this->ext,$v)) { continue; }
			} else if ($this->ext!=$v) {
				continue;
			}
			$this->subtype=$k;
		}
	}

	private function _sortMyChildrenCallback(TreeNode $a,TreeNode $b) {
		//First DIR, FILE
		$firstCmp=0;
		if ($a->isDir() && $b->isFile()) {
			$firstCmp=-1;
		} else if ($a->isFile() && $b->isDir()) {
			$firstCmp=1;
		}

		if ($firstCmp!=0) { return $firstCmp; }

		//Then HIDDEN, NOT HIDDEN
		if ($a->isHidden() && $b->isHidden()) {
			return 0;
		} else if ($a->isHidden() && (!$b->isHidden())) {
			return -1;
		} else if ((!$a->isHidden()) && $b->isHidden()) {
			return 1;
		}

		//Then alphabetical
		return strcasecmp($a->name,$b->name);
	}

	public function isDir() {
		return $this->type==self::TYPE_DIR;
	}
	public function isFile() {
		return $this->type==self::TYPE_FILE;
	}

	public function isHidden() {
		return $this->name[0]==self::HIDDEN;
	}
}