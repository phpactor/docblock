<?php

namespace Phpactor\Docblock;

use Phpactor\Docblock\Ast\DeprecatedNode;
use Phpactor\Docblock\Ast\Docblock;
use Phpactor\Docblock\Ast\MethodNode;
use Phpactor\Docblock\Ast\TextNode;
use Phpactor\Docblock\Ast\TypeList;
use Phpactor\Docblock\Ast\Type\ClassNode;
use Phpactor\Docblock\Ast\Node;
use Phpactor\Docblock\Ast\ParamNode;
use Phpactor\Docblock\Ast\TagNode;
use Phpactor\Docblock\Ast\TypeNode;
use Phpactor\Docblock\Ast\Type\GenericNode;
use Phpactor\Docblock\Ast\Type\ListNode;
use Phpactor\Docblock\Ast\Type\ScalarNode;
use Phpactor\Docblock\Ast\UnknownTag;
use Phpactor\Docblock\Ast\VarNode;
use Phpactor\Docblock\Ast\VariableNode;

final class Parser
{
    /**
     * @var Tokens
     */
    private $tokens;

    private const SCALAR_TYPES = [
        'int', 'float', 'bool', 'string'
    ];

    public function parse(Tokens $tokens): Node
    {
        $this->tokens = $tokens;
        $children = [];

        while ($tokens->hasCurrent()) {
            if ($tokens->current->type === Token::T_TAG) {
                $children[] = $this->parseTag();
                continue;
            }
            $children[] = $tokens->chomp();
        }

        return new Docblock($children);
    }

    private function parseTag(): TagNode
    {
        $token = $this->tokens->current;

        if ($token->value === '@param') {
            return $this->parseParam();
        }

        if ($token->value === '@var') {
            return $this->parseVar();
        }

        if ($token->value === '@deprecated') {
            return $this->parseDeprecated();
        }

        if ($token->value === '@method') {
            return $this->parseMethod();
        }

        return new UnknownTag($this->tokens->chomp());
    }

    private function parseParam(): ParamNode
    {
        $type = $variable = $textNode = null;
        $this->tokens->chomp(Token::T_TAG);

        if ($this->tokens->ifNextIs(Token::T_LABEL)) {
            $type = $this->parseType();
        }
        if ($this->tokens->ifNextIs(Token::T_VARIABLE)) {
            $variable = $this->parseVariable();
        }

        return new ParamNode($type, $variable, $this->parseText());
    }

    private function parseVar(): VarNode
    {
        $this->tokens->chomp(Token::T_TAG);
        $type = $variable = null;
        if ($this->tokens->if(Token::T_LABEL)) {
            $type = $this->parseType();
        }
        if ($this->tokens->ifNextIs(Token::T_VARIABLE)) {
            $variable = $this->parseVariable();
        }

        return new VarNode($type, $variable);
    }

    private function parseMethod(): MethodNode
    {
        $this->tokens->chomp(Token::T_TAG);
        $type = $name = null;
        if ($this->tokens->if(Token::T_LABEL)) {
            $type = $this->parseType();
        }
        if ($this->tokens->ifNextIs(Token::T_LABEL)) {
            $name = $this->tokens->chomp();
        }

        return new MethodNode($type, $name);
    }

    private function parseType(): ?TypeNode
    {
        $type = $this->tokens->chomp(Token::T_LABEL);
        $isList = false;

        if ($this->tokens->current->type === Token::T_LIST) {
            $list = $this->tokens->chomp();
            return new ListNode($this->createTypeFromToken($type), $list);
        }

        if ($this->tokens->current->type === Token::T_BRACKET_ANGLE_OPEN) {
            $open = $this->tokens->chomp();
            if ($this->tokens->if(Token::T_LABEL)) {
                $typeList = $this->parseTypeList();
            }

            if ($this->tokens->current->type !== Token::T_BRACKET_ANGLE_CLOSE) {
                return null;
            }

            return new GenericNode(
                $open,
                $this->createTypeFromToken($type),
                $typeList,
                $this->tokens->chomp()
            );
        }

        return $this->createTypeFromToken($type);
    }

    private function createTypeFromToken(Token $type): TypeNode
    {
        if (in_array($type->value, self::SCALAR_TYPES)) {
            return new ScalarNode($type);
        }

        return new ClassNode($type);
    }

    private function parseVariable(): ?VariableNode
    {
        if ($this->tokens->current->type !== Token::T_VARIABLE) {
            return null;
        }

        $name = $this->tokens->chomp(Token::T_VARIABLE);

        return new VariableNode($name);
    }

    private function parseTypeList(): TypeList
    {
        $types = [];
        while (true) {
            if ($this->tokens->if(Token::T_LABEL)) {
                $types[] = $this->parseType();
            }
            if ($this->tokens->if(Token::T_COMMA)) {
                $this->tokens->chomp();
                continue;
            }
            break;
        }

        return new TypeList($types);
    }

    private function parseDeprecated(): DeprecatedNode
    {
        $this->tokens->chomp();
        return new DeprecatedNode($this->parseText());
    }

    private function parseText(): ?TextNode
    {
        $text = [];
        if (
            $this->tokens->current->type === Token::T_WHITESPACE && 
            $this->tokens->next()->type === Token::T_LABEL
        ) {
            $this->tokens->chomp();
        }
        while ($this->tokens->current) {
            if ($this->tokens->current->type === Token::T_PHPDOC_CLOSE) {
                break;
            }
            if ($this->tokens->current->type === Token::T_PHPDOC_LEADING) {
                break;
            }
            if (false !== strpos($this->tokens->current->value, "\n")) {
                break;
            }
            $text[] = $this->tokens->chomp();
        }
        
        if ($text) {
            return new TextNode($text);
        }

        return null;
    }
}
