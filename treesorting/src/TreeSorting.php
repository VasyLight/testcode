<?php

class TreeSorting
	{
	    protected $tree;
		
	    public function __construct($data)
		{
		    $this->tree = $this->buildTree($data, 'PARENT_ID', 'ID');
		}

        protected function buildTree($flat, $pidKey, $idKey = null)
        {
            $grouped = array();
            foreach ($flat as $sub){
                $grouped[$sub[$pidKey]][] = $sub;
            }

            $fnBuilder = function($siblings) use (&$fnBuilder, $grouped, $idKey) {
                foreach ($siblings as $k => $sibling) {
                    $id = $sibling[$idKey];
                    if(isset($grouped[$id])) {
                        $sibling['CHILDREN'] = $fnBuilder($grouped[$id]);
                    }
                    $siblings[$k] = $sibling;
                }
                return $siblings;
            };

            $tree = $fnBuilder($grouped[0]);
            return $tree;
        }

	    private function renderTree($nodes)
	    {
	        echo "<ul>";
	        foreach($nodes as $node) {
	            echo "<li>{$node['NAME']}";
			    if (isset($node['CHILDREN'])) {
			        $this->renderTree($node['CHILDREN']);
			    }
			    echo "</li>";
		    }
		    echo"</ul>";
	    }
	    
		public function printTree()
		{
		    echo '<nav>';
		    $this->renderTree($this->tree);
			echo '</nav>';
	    }
	}