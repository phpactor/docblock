<?php

namespace Phpactor\Docblock\Ast;

class DeprecatedNode extends TagNode
{
    public const CHILD_NAMES = [
        'text',
    ];

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
