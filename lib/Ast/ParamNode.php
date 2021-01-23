<?php

namespace Phpactor\Docblock\Ast;

class ParamNode extends TagNode
{
    /**
     * @var ?TypeNode
     */
    private $type;

    /**
     * @var ?VariableNode
     */
    private $variable;

    /**
     * @var TextNode|null
     */
    private $text;

    public function __construct(?TypeNode $type, ?VariableNode $variable, ?TextNode $text = null)
    {
        $this->type = $type;
        $this->variable = $variable;
        $this->text = $text;
    }

    public function type(): ?TypeNode
    {
        return $this->type;
    }

    public function variable(): ?VariableNode
    {
        return $this->variable;
    }

    public function text(): ?TextNode
    {
        return $this->text;
    }
}
