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

    /**
     * @var ValueNode|null
     */
    private $default;

    public function __construct(?TypeNode $type, ?VariableNode $name, ?ValueNode $default)
    {
        $this->type = $type;
        $this->name = $name;
        $this->default = $default;
    }

    public function name(): ?VariableNode
    {
        return $this->name;
    }

    public function type(): ?TypeNode
    {
        return $this->type;
    }

    public function default(): ?ValueNode
    {
        return $this->default;
    }
}
