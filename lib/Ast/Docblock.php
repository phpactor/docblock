<?php

namespace Phpactor\Docblock\Ast;

class Docblock extends Node
{
    /**
     * @var Element[]
     */
    private $children = [];

    /**
     * @param Element[] $children
     */
    public function __construct(array $children)
    {
        $this->children = $children;
    }

    /**
     * @return Element[]
     */
    public function children(): array
    {
        return $this->children;
    }
}
