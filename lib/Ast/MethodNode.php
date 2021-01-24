<?php

namespace Phpactor\Docblock\Ast;

use Phpactor\Docblock\Token;

class MethodNode extends TagNode
{
    /**
     * @var TypeNode|null
     */
    private $type;

    /**
     * @var Token|null
     */
    private $name;

    /**
     * @var Token|null
     */
    private $static;

    /**
     * @var ParameterList|null
     */
    private $parameters;

    /**
     * @var TextNode|null
     */
    private $text;

    /**
     * @var Token|null
     */
    private $parenOpen;

    /**
     * @var Token|null
     */
    private $parenClose;

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
