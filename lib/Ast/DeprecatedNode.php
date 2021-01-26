<?php

namespace Phpactor\Docblock\Ast;

use Phpactor\Docblock\Token;

class DeprecatedNode extends TagNode
{
    public const CHILD_NAMES = [
        'token',
        'text',
    ];

    /**
     * @var TextNode
     */
    public $text;

    /**
     * @var Token
     */
    public $token;

    public function __construct(Token $token, ?TextNode $text)
    {
        $this->text = $text;
        $this->token = $token;
    }

    public function text(): ?TextNode
    {
        return $this->text;
    }
}
