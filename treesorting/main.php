<?php 
require_once('src/dataset.php');
require_once ('src/TreeSorting.php');

echo "Задание 3";
$t = new TreeSorting($data);
$t->printTree();
