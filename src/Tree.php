<?php

namespace Molitor\Tree;

class Tree
{
    private array $nodes = [];

    public function createNode(string $name): Node
    {
        $node = new Node();
        $node->setParent($this);
        $this->nodes[$name] = $node;
        return $node;
    }

    public function getNode(string $name): ?Node
    {
        return $this->nodes[$name] ?? null;
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
