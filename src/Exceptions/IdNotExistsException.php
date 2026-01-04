<?php

namespace Molitor\Tree\Exceptions;

use Exception;

class IdNotExistsException extends Exception
{
    protected int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
        parent::__construct("ID {$id} does not exist in the tree.");
    }

    public function getId(): int
    {
        return $this->id;
    }
}
