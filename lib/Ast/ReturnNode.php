<?php

namespace Phpactor\Docblock\Ast;

class ReturnNode extends TagNode
{
    /**
     * @var TypeNode|null
     */
    private $type;

    /**
     * @var TextNode|null
     */
    private $text;

    public function __construct(?TypeNode $type, ?TextNode $text = null)
    {
        $this->type = $type;
        $this->text = $text;
    }

    public function type(): ?TypeNode
    {
        return $this->type;
    }

    public function text(): ?TextNode
    {
        return $this->text;
    }
}
