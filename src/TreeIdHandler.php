<?php

namespace Molitor\Tree;

use Molitor\Tree\Exceptions\DuplicateIdException;

class TreeIdHandler
{
    private $data = [];

    private $root = [];

    private $children = [];

    private $parents = [];

    public function idExists(string|int $id): bool
    {
        return array_key_exists($id, $this->data);
    }

    public function getData(string|int $id): ?array
    {
        return $this->data[$id] ?? null;
    }

    public function isRootId(string|int|null $id): bool
    {
        return $id === null || $id === 0 || $id === '';
    }

    public function getParentId(string|int $id): string|int|null
    {
        return $this->parents[$id] ?? null;
    }

    public function getChildrenIds(string|int $id): array
    {
        if($this->isRootId($id)) {
            return $this->root;
        }
        return $this->children[$id] ?? [];
    }

    public function add(string|int $id, array $data = [], string|int|null $parentId = null): void
    {
        if($this->idExists($id)) {
            throw new DuplicateIdException($id);
        }

        $this->data[$id] = $data;
        $this->parents[$id] = $parentId;

        if($this->isRootId($parentId)) {
            $this->root[] = $id;
        }
        else {
            if(array_key_exists($parentId, $this->children)) {
                $this->children[$parentId][] = $id;
            }
            else {
                $this->children[$parentId] = [$id];
            }
        }
    }

    public function getPathIds(string|int $id): array
    {
        if($this->isRootId($id) || $this->idExists($id) === false) {
            return [];
        }

        $path = [];
        $currentId = $id;

        while($this->isRootId($currentId) === false && !in_array($currentId, $path)) {
            $path[] = $currentId;
            $currentId = $this->getParentId($currentId);
        }

        return array_reverse($path);
    }

    public function getPath(string|int $id): array
    {
        return array_map(fn($id) => $this->getData($id), $this->getPathIds($id));
    }

    public function getIds(): array
    {
        return array_keys($this->data);
    }
}
