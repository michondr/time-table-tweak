<?php

namespace App\TimeTableBuilder\Tree;

class TreeNodeList
{
    /**
     * @var TreeNode[]|array
     */
    private $nodes;

    /** @param TreeNode[] $nodes */
    public function __construct(
        array $nodes
    ) {
        $this->nodes = $nodes;
    }

    public function getNodes()
    {
        return $this->nodes;
    }
}