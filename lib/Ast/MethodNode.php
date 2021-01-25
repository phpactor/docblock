<?php

namespace Phpactor\Docblock\Ast;

use Phpactor\Docblock\Token;

class MethodNode extends TagNode
{
    public const CHILD_NAMES = [
        'type',
        'static',
        'type',
        'name',
        'parenOpen',
        'parameters',
        'parenClose',
        'text'
    ];

    /**
     * @var TypeNode|null
     */
    public $type;

    /**
     * @var Token|null
     */
    public $name;

    /**
     * @var Token|null
     */
    public $static;

    /**
     * @var ParameterList|null
     */
    public $parameters;

    /**
     * @var TextNode|null
     */
    public $text;

    /**
     * @var Token|null
     */
    public $parenOpen;

    /**
     * @var Token|null
     */
    public $parenClose;

    public function __construct(
        ?TypeNode $type,
        ?Token $name,
        ?Token $static,
        ?Token $parenOpen,
        ?ParameterList $parameters,
        ?Token $parenClose,
        ?TextNode $text
    ) {
        $this->type = $type;
        $this->name = $name;
        $this->static = $static;
        $this->parameters = $parameters;
        $this->text = $text;
        $this->parenOpen = $parenOpen;
        $this->parenClose = $parenClose;
    }

    public function name(): ?Token
    {
        return $this->name;
    }

    public function type(): ?TypeNode
    {
        return $this->type;
    }

    public function static(): ?Token
    {
        return $this->static;
    }

    public function parameters(): ?ParameterList
    {
        return $this->parameters;
    }

    public function text(): ?TextNode
    {
        return $this->text;
    }

    public function parenOpen(): ?Token
    {
        return $this->parenOpen;
    }

    public function parenClose(): ?Token
    {
        return $this->parenClose;
    }
}
