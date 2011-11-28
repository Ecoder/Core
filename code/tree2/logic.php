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
	
	public $name,$type;
	public $children;
	
	public function __construct($pathname) {
		$sfi=new SplFileInfo($pathname);
		$this->_getPropertiesFromSfi($sfi);
		if ($sfi->isDir()) {
			$this->children=array();
			$di=new DirectoryIterator($pathname);
			foreach ($di as $f) {
				if ($f->isDot()) { continue; }
				$this->children[]=new TreeNode($f->getPathname());
			}
			usort($this->children,array($this,"_sortMyChildrenCallback"));
		} else {
			$this->children=null;
		}
	}
	
	private function _getPropertiesFromSfi(SplFileInfo $sfi) {
		$this->name=$sfi->getFilename();
		$this->type=$sfi->getType();
	}
	
	private function _sortMyChildrenCallback(TreeNode $a,TreeNode $b) {
		$firstCmp=0;
		
		if ($a->isDir() && $b->isFile()) {
			$firstCmp=-1;
		} else if ($a->isFile() && $b->isDir()) {
			$firstCmp=1;
		}
		
		if ($firstCmp!=0) { return $firstCmp; }
		
		if ($a->isHidden() && $b->isHidden()) {
			return 0;
		} else if ($a->isHidden() && (!$b->isHidden())) {
			return -1;
		} else if ((!$a->isHidden()) && $b->isHidden()) {
			return 1;
		}
		
		//TODO add alphabetic sort
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