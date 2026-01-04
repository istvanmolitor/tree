<?php

namespace Molitor\Tree;

class Tree
{
    private array $items = [];

    public function addItem(TreeItem $item): static
    {
        $this->items[] = $item;
        return $this;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function toArray(): array
    {
        return array_map(fn(TreeItem $item) => $item->toArray(), $this->items);
    }
}
