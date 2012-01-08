<?php
include "code.php";

$controller=(isset($_GET['controller']) ? $_GET['controller'] : "");
$action=(isset($_GET['action']) ? $_GET['action'] : "");
switch ($controller) {
	case "filemanipulation":
		switch ($action) {
			case "remove":
				FileManipulation::remove($i->file,$i->allowedRecursive);
				break;
			case "rename":
				FileManipulation::rename($i->file,$i->newname);
				break;
			case "addFolder":
				FileManipulation::addFolder($i->path,$i->name);
				break;
			case "addFile":
				FileManipulation::addFile($i->path,$i->name);
				break;
			case "upload":
				FileManipulation::upload();
				break;
			case "getFileEditingInfo":
				FileManipulation::getFileEditingInfo($i->file);
				break;
			case "editSave":
				FileManipulation::editSave($i->file,$i->content);
				break;
			default:
				//error
				break;
		}
		break;
	case "tree":
		TreeNode::init();
		break;
	case "env":
		Env::get();
		break;
	default:
		//error
		break;
}
Output::send();