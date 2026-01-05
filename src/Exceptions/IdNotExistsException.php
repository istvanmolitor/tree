<?php

namespace Molitor\Tree\Exceptions;

use Exception;

class IdNotExistsException extends Exception
{
    protected string|int $id;

    public function __construct(string|int $id)
    {
        $this->id = $id;
        parent::__construct("ID {$id} does not exist in the tree.");
    }

    public function getId(): string|int
    {
        return $this->id;
    }
}
