<?php

namespace Molitor\Tree;

class TreeItem extends Tree
{
    private null|array $data = null;

    public function __construct(array $data = null)
    {
        $this->setData($data);
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
