<?php

namespace Molitor\Tree;

use Molitor\Tree\Exceptions\DuplicateIdException;

class TreeBuilder
{
    private $data = [];

    private $root = [];

    private $children = [];

    private $parents = [];

    private string $slugSeparator = "/";
    private array $slugs = [];

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

    protected function prepareSlug(string $slug, string $separator): array
    {
        if(empty($slug)) {
            return [];
        }
        return array_map(fn($part) => trim($part), explode($separator, $slug));
    }

    public function getIdBySlug(string $slug, string $separator = '/'): ?string
    {
        $slugParts = $this->prepareSlug($slug, $separator);
        if(count($slugParts) === 0) {
            return null;
        }
        return md5(serialize($slugParts));
    }

    public function getParentIdBySlug(string $slug, string $separator = '/'): ?string
    {
        $slugParts = $this->prepareSlug($slug, $separator);
        if(count($slugParts) === 0) {
            return null;
        }
        array_pop($slugParts);
        if(count($slugParts) === 0) {
            return null;
        }
        return md5(serialize($slugParts));
    }


    public function addBySlug(string $slug, array $data = [], string $separator = '/'): void
    {
        $slugElements = $this->prepareSlug($slug, $separator);
        if(count($slugElements) === 0) {
            return;
        }

        $parentId = null;
        $currentSlugParts = [];
        foreach ($slugElements as $slugElement) {
            $currentSlugParts[] = $slugElement;
            $id = md5(serialize($currentSlugParts));
            if(!$this->idExists($id)) {
                $this->add($id, [], $parentId);
                $parentId = $id;
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

    protected function buildNode(string|int $id): Node
    {
        $node = new Node($this->getData($id));
        foreach ($this->getChildrenIds($id) as $childrenId) {
            $node->addChild($this->buildNode($childrenId));
        }
        return $node;
    }

    public function buildTree(): Tree
    {
        $tree = new Tree();
        foreach ($this->getChildrenIds(null) as $id) {
            $tree->addChild($this->buildNode($id));
        }
        return $tree;
    }
}
