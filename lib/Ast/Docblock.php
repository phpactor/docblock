<?php

namespace Phpactor\Docblock\Ast;

class Docblock extends Node
{
    /**
     * @param Element[] $children
     */
    public function __construct(array $children)
    {
        $this->children = $children;
    }
}
