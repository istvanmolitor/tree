<?php

namespace Molitor\Tree\Exceptions;

use Exception;

class DuplicateIdException extends Exception
{
    protected string|int $id;

    public function __construct(string|int $id)
    {
        $this->id = $id;
        parent::__construct("ID {$id} already exists in the tree.");
    }

    public function getId(): string|int
    {
        return $this->id;
    }
}
