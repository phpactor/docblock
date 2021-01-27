<?php

namespace Phpactor\Docblock\Ast;

class Docblock extends Node
{
    protected const CHILD_NAMES = [
        'children'
    ];

    /**
     * @var ElementList
     */
    public $children = [];

    /**
     * @param Element[] $children
     */
    public function __construct(array $children)
    {
        $this->children = new ElementList($children);
    }

    public function prose(): string
    {
        return trim(implode('', array_map(function (Element $token): string {
            if ($token instanceof Token) {
                if (in_array($token->type, [
                    Token::T_PHPDOC_OPEN,
                    Token::T_PHPDOC_CLOSE,
                    Token::T_PHPDOC_LEADING
                ])) {
                    return '';
                }
                return $token->value;
            }
            return '';
        }, iterator_to_array($this->children, false))));
    }
}
