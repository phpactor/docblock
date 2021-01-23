<?php

namespace Phpactor\Docblock\Ast;

class ParameterNode extends Node
{
    /**
     * @var TypeNode|null
     */
    private $type;
    /**
     * @var VariableNode|null
     */
    private $name;

    public function __construct(?TypeNode $type, ?VariableNode $name)
    {
        $this->type = $type;
        $this->name = $name;
    }

    public function name(): ?VariableNode
    {
        return $this->name;
    }

    public function type(): ?TypeNode
    {
        return $this->type;
    }
}
