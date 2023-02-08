<?php
require_once('dataSet.php');

function buildTree(array $flatList)
{
    $grouped = [];
    foreach ($flatList as $node){
        $grouped[$node['PARENT_ID']][] = $node;
    }

    $fnBuilder = function($siblings) use (&$fnBuilder, $grouped) {
        foreach ($siblings as $k => $sibling) {
            $id = $sibling['ID'];
            if(isset($grouped[$id])) {
                $sibling['CHILDREN'] = $fnBuilder($grouped[$id]);
            }
            $siblings[$k] = $sibling;
        }
        return $siblings;
    };

    return $fnBuilder($grouped[0]);
}

function printTree($tree) {
    if(!is_null($tree) && count($tree) > 0) {
        echo '<ul>';
        foreach($tree as $node) {
            echo '<li>'.$node['NAME'];
            if (isset($node['CHILDREN'])) {
               printTree($node['CHILDREN']);
            }
      
            echo '</li>';
        }
        echo '</ul>';
    }
}

$t = buildTree($data);
printTree($t);



