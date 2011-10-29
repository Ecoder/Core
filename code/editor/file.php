<?php
class File {
	private $path,$name,$pathname,$type,$contentraw,$content;
	
	public function __construct() {
		global $main,$code;
		$this->path=$main['path'];
		$this->name=$main['file'];
		$this->pathname=$code['root'].'/'.$this->path.'/'.$this->name;
		$this->type=Filetype::typestring($main['type']);
		if (file_exists($this->pathname)) {
			$this->contentraw=file_get_contents($this->pathname);
			$this->content=trim(htmlspecialchars($this->contentraw));
		}
	}
	
	function makeBackupIfNeeded() {
		global $code; //Yuck
		$backupPfx=".";
		if ($code['backup']==1) {
			$backupPath=$code['root'].'/'.$this->path.'/'.$backupPfx.$this->name;
			ecoder_copy($this->pathname,$backupPath,$code['permissions_file']);
		}
	}
}