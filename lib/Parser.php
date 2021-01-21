<?php

namespace Phpactor\Docblock;

use Phpactor\Docblock\Ast\Docblock;
use Phpactor\Docblock\Ast\Type\ClassNode;
use Phpactor\Docblock\Ast\Node;
use Phpactor\Docblock\Ast\ParamNode;
use Phpactor\Docblock\Ast\TagNode;
use Phpactor\Docblock\Ast\TypeNode;
use Phpactor\Docblock\Ast\Type\ListNode;
use Phpactor\Docblock\Ast\Type\ScalarNode;
use Phpactor\Docblock\Ast\UnknownTag;
use Phpactor\Docblock\Ast\VariableNode;

final class Parser
{
    private const SCALAR_TYPES = [
        'int', 'float', 'bool', 'string'
    ];

    public function parse(Tokens $tokens): Node
    {
        $children = [$tokens->current()];

        while ($token = $tokens->next()) {
            assert($token instanceof Token);
            if ($token->type() === Token::T_TAG) {
                $children[] = $this->parseTag($token, $tokens);
                continue;
            }
            $children[] = $token;
        }

        return new Docblock($children);
    }

    private function parseTag(Token $token, Tokens $tokens): TagNode
    {
        if ($token->value() === '@param') {
            return $this->parseParam($tokens);
        }

        return new UnknownTag($token);
    }

    private function parseParam(Tokens $tokens): ParamNode
    {
        $type = $this->parseType($tokens->skip(Token::T_WHITESPACE));
        $variable = $this->parseVariable($tokens->skip(Token::T_WHITESPACE));

        return new ParamNode($type, $variable);
    }

    private function parseType(Tokens $tokens): ?TypeNode
    {
        $isList = false;

        if (!$tokens->isType(Token::T_LABEL)) {
            return null;
        }


        $type = $tokens->current();

        if ($tokens->peek()->type() === Token::T_LIST) {
            $tokens->next();
            return new ListNode($this->createTypeFromToken($type), $tokens->current());
        }

        return $this->createTypeFromToken($type);
    }

    private function createTypeFromToken(Token $type): TypeNode
    {
        if (in_array($type->value(), self::SCALAR_TYPES)) {
            return new ScalarNode($type);
        }

        return new ClassNode($type);
    }

    private function parseVariable(Tokens $tokens): ?VariableNode
    {
        if (!$tokens->isType(Token::T_VARIABLE)) {
            return null;
        }

        $name = $tokens->current();

        return new VariableNode($name);
    }
}
