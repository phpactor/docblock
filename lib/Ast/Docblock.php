<?php

namespace Phpactor\Docblock\Ast;

class Docblock extends Node
{
    protected const CHILD_NAMES = [
        'children'
    ];

    /**
     * @var ElementList
     */
    public $children = [];

    /**
     * @param Element[] $children
     */
    public function __construct(array $children)
    {
        $this->children = new ElementList($children);
    }
}
