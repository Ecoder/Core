<?php
class Extension {
	const DEFAULTFILETYPE="text";
	private $name,$filetype_id;
	private $filetype;

	public function Extension($ext) {
		if (empty($ext)) { return null; }
		$data=Db::getRow("SELECT * FROM extension WHERE name=?",$ext);
		if (empty($data)) { return null; }
		$this->name=$data->name;
		$this->filetype_id=$data->filetype_id;
		$this->filetype=new Filetype($this->filetype_id);
	}

	public function getName() { return $this->name; }
	public function getFiletype() { return $this->filetype; }

	public static function findFileTypeByExtension($ext) {
		$exob=new Extension($ext);
		$name=$exob->getName();
		if (empty($name)) {
			return Filetype::getByName(self::DEFAULTFILETYPE);
		}
		return $exob->getFiletype();
	}
}