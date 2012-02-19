<?php
class Filetype {
	private $id,$name,$cmmime;

	public function Filetype($id) {
		$data=Db::getRow("SELECT * FROM filetype WHERE id=?",$id);
		$this->id=$id;
		$this->name=$data->name;
		$this->cmmime=$data->cmmime;
	}

	public function getName() { return $this->name; }
	public function getCmmime() { return $this->cmmime; }

	public static function getByName($name) {
		$id=Db::getValue("SELECT id FROM filetype WHERE name=?",$name);
		return new Filetype($id);
	}
}