<?php

namespace Phpactor\Docblock\Ast;

use Phpactor\Docblock\Token;

class ParamNode extends TagNode
{
    protected const CHILD_NAMES = [
        'tag',
        'type',
        'variable',
        'text',
    ];

    /**
     * @var ?TypeNode
     */
    public $type;

    /**
     * @var ?VariableNode
     */
    public $variable;

    /**
     * @var TextNode|null
     */
    public $text;

    /**
     * @var Token
     */
    public $tag;

    public function __construct(Token $tag, ?TypeNode $type, ?VariableNode $variable, ?TextNode $text = null)
    {
        $this->type = $type;
        $this->variable = $variable;
        $this->text = $text;
        $this->tag = $tag;
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
