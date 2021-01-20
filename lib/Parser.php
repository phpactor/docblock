<?php

namespace Phpactor\Docblock;

use Phpactor\Docblock\Ast\Docblock;
use Phpactor\Docblock\Ast\NameNode;
use Phpactor\Docblock\Ast\Node;
use Phpactor\Docblock\Ast\ParamNode;
use Phpactor\Docblock\Ast\TypeNode;
use Phpactor\Docblock\Ast\VariableNode;

final class Parser
{
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

    private function parseTag(Token $token, Tokens $tokens)
    {
        if ($token->value() === '@param') {
            return $this->parseParam($tokens);
        }
    }

    private function parseParam(Tokens $tokens): ParamNode
    {
        $type = $this->parseType($tokens->skip(Token::T_WHITESPACE));
        $variable = $this->parseVariable($tokens->skip(Token::T_WHITESPACE));

        return new ParamNode($type, $variable);
    }

    private function parseType(Tokens $tokens): ?TypeNode
    {
        if (!$tokens->isType(Token::T_LABEL)) {
            return null;
        }

        $type = $tokens->current();

        return new NameNode($type);
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
