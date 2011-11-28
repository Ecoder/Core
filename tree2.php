<?php
include "code.php";
include "code/tree2/logic.php";
$node=new TreeNode(realpath($code['root']));
Output::add("tree",$node);
Output::send();