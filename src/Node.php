<?php

namespace Molitor\Tree;

class Node extends Tree
{
    private string $id = '';
    private Tree|null $parent = null;
    private null|array $data = null;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function __construct(array $data = null)
    {
        $this->setData($data);
    }

    public function setParent(Tree $parent): static
    {
        $this->parent = $parent;
        return $this;
    }

    public function getParent(): ?Tree
    {
        return $this->parent;
    }

    public function setData(array $data): static
    {
        $this->data = $data;
        return $this;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function set(string $key, mixed $value): static
    {
        $this->data[$key] = $value;
        return $this;
    }

    public function get(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    public function __set(string $name, $value): void
    {
        $this->set($name, $value);
    }

    public function __isset(string $key): bool
    {
        return $this->has($key);
    }

    public function __get(string $key): mixed
    {
        return $this->get($key);
    }

    public function toArray(): array
    {
        return [
            'data' => $this->data,
            'children' => parent::toArray(),
        ];
    }
}
