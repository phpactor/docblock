<?php

namespace Phpactor\Docblock\Ast;

use Phpactor\Docblock\Token;

class TextNode extends Node
{
    /**
     * @var Token[]
     */
    private $tokens;

    /**
     * @param Token[] $tokens
     */
    public function __construct(array $tokens)
    {
        $this->tokens = $tokens;
    }

    public function toString(): string
    {
        return implode('', array_map(function (Token $token) {
            return $token->value;
        }, $this->tokens));
    }
}
