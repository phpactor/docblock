<?php

namespace Phpactor\Docblock\Ast;

class DeprecatedNode extends TagNode
{
    /**
     * @var TextNode
     */
    private $text;

    public function __construct(?TextNode $text)
    {
        $this->text = $text;
    }

    public function text(): ?TextNode
    {
        return $this->text;
    }
}
