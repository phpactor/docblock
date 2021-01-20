<?php

namespace Phpactor\Docblock\Ast;

class ParamNode extends Node
{
    /**
     * @var TypeNode
     */
    private $type;
    /**
     * @var VariableNode
     */
    private $variable;

    public function __construct(TypeNode $type, VariableNode $variable)
    {
        $this->type = $type;
        $this->variable = $variable;
    }
}
