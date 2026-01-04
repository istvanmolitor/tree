<?php

namespace Molitor\Tree\Exceptions;

use Exception;

class DuplicateIdException extends Exception
{
    protected int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
        parent::__construct("ID {$id} already exists in the tree.");
    }

    public function getId(): int
    {
        return $this->id;
    }
}
