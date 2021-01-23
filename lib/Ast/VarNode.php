<?php

namespace Phpactor\Docblock\Ast;

class VarNode extends TagNode
{
    /**
     * @var ?TypeNode
     */
    private $type;

    /**
     * @var ?VariableNode
     */
    private $variable;

    public function __construct(?TypeNode $type, ?VariableNode $variable)
    {
        $this->type = $type;
        $this->variable = $variable;
    }

    public function type(): ?TypeNode
    {
        return $this->type;
    }

    public function variable(): ?VariableNode
    {
        return $this->variable;
    }
}
