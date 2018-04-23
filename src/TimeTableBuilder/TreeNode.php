<?php

namespace App\TimeTableBuilder;

class TreeNode
{
    /** @var TreeNode[] */
    protected $children;
    /** @var int */
    protected $generation;
    /** @var TimeTable */
    protected $item;
    /** @var TreeNode */
    protected $parent;

    public static function create(
        TimeTable $item,
        TreeNode $parent = null
    ) {
        $node = new TreeNode();
        $node->item = $item;
        $node->parent = $parent;
        $node->children = [];

        if (!is_null($parent)) {
            $node->generation = $parent->getGeneration() + 1;
            $parent->addChild($node);
        } else {
            $node->generation = 0;
        }

        return $node;
    }

    public function getGeneration(): int
    {
        return $this->generation;
    }

    public function getParent(): ?TreeNode
    {
        return $this->parent;
    }

    public function getItem(): TimeTable
    {
        return $this->item;
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    public function addChild(TreeNode $node)
    {
        $this->children[] = $node;
    }

    public function getRoot()
    {
        $currentParent = $this->parent;
        while (true) {
            $parent = $currentParent->getParent();

            if (is_null($parent)) {
                break;
            } else {
                $currentParent = $parent;
            }
        }

        return $currentParent;
    }

    public function getLeaves()
    {
        $currentChildren = $this->getChildren();
        $leaves = [];
        $notLeaves = [];

        if (empty($currentChildren)) {
            $leaves = [$this];
        }

        while (true) {
            /** @var TreeNode $child */
            foreach ($currentChildren as $child) {
                if (empty($child->getChildren())) {
                    $leaves[] = $child;
                } else {
                    $notLeaves = array_merge($notLeaves, $child->getChildren());
                }
            }
            if (empty($notLeaves)) {
                break;
            }
            $currentChildren = $notLeaves;
            $notLeaves = [];
        }

        return $leaves;
    }

    public function getItemsOnHighestLeaves()
    {
        $leaves = $this->getLeaves();
        $items = [];
        $oldest = 0;

        /** @var TreeNode $leaf */
        foreach ($leaves as $leaf) {
            $generation = $leaf->getGeneration();
            if ($generation > $oldest) {
                $oldest = $generation;
                $items = [$leaf->getItem()];
                continue;
            }
            if ($generation = $oldest) {
                $items[] = $leaf->getItem();
            }
        }

        return $items;
    }

}
