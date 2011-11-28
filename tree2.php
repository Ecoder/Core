<?php
include "code.php";
include "code/tree2/logic.php";
$node=new TreeNode($code['root']);
Output::add("tree",$node);
Output::send();
exit;