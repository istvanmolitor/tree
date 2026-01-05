<?php

namespace Molitor\Tree;

class Tree
{
    private array $nodes = [];

    public function addChild(Node $node): static
    {
        $node->setParent($this);
        $this->nodes[] = $node;
        return $this;
    }

    public function getNodes(): array
    {
        return $this->nodes;
    }

    public function toArray(): array
    {
        return array_map(fn(Node $item) => $item->toArray(), $this->nodes);
    }
}
