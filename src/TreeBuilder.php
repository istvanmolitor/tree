<?php

namespace Molitor\Tree;

use Molitor\Tree\Exceptions\DuplicateIdException;
use Molitor\Tree\Exceptions\IdNotExistsException;

class TreeBuilder
{
    private array $items = [];
    private array $rootIds = [];
    private array $parents = [];
    private array $children = [];

    public function idExists(int $id): bool
    {
        return array_key_exists($id, $this->items);
    }

    protected function validateId(int $id): void
    {
        if(!$this->idExists($id)) {
            throw new IdNotExistsException($id);
        }
    }

    public function hasParent(int $id): bool
    {
        $parentId = $this->getParent($id);
        if($parentId === 0) {
            return false;
        }
        return $this->idExists($parentId);
    }

    public function getParent(int $id): int|null
    {
        $this->validateId($id);
        if(!$this->hasParent($id)) {
            return $this->parents[$id];
        }
        return $this->parents[$id] ?? 0;
    }

    public function isRoot(int $id): bool
    {
        return $this->getParent($id) === 0;
    }

    public function getDataById(int $id): array|null
    {
        return $this->items[$id] ?? null;
    }

    public function add(int $id, int $parentId, array|null $data = null): void
    {
        if($this->idExists($id)) {
            throw new DuplicateIdException($id);
        }

        $this->parents[$id] = $parentId;

        if($parentId === 0) {
            $this->rootIds[] = $id;
        }
        else {
            if(array_key_exists($parentId, $this->children)) {
                $this->children[$parentId][] = $id;
            }
            else {
                $this->children[$parentId] = [$id];
            }
        }

        $this->items[$id] = $data;
    }

    public function getPath(int $id): array
    {
        $this->validateId($id);

        $path = [];
        while($this->hasParent($id) && !in_array($id, $path)) {
            $id = $this->getParent($id);
            $path[] = $id;
        }
        $path = array_reverse($path);
        $path[] = $id;
        return $path;
    }

    protected function getChildrenIds(int $id): array
    {
        if($id === 0) {
            return $this->rootIds;
        }
        if(array_key_exists($id, $this->children)) {
            return $this->children[$id];
        }
        return [];
    }

    public function buildTreeItem(int $id): TreeItem
    {
        $this->validateId($id);
        $item = new TreeItem($this->getDataById($id));
        foreach ($this->getChildrenIds($id) as $childId) {
            $item->addItem($this->buildTreeItem($childId));
        }
        return $item;
    }

    public function getTree(): Tree
    {
        $tree = new Tree();
        foreach ($this->getChildrenIds(0) as $id) {
            $tree->addItem($this->buildTreeItem($id));
        }
        return $tree;
    }
}
