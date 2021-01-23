<?php

namespace Phpactor\Docblock\Ast\Type;

use Phpactor\Docblock\Ast\TypeList;
use Phpactor\Docblock\Ast\TypeNode;
use Phpactor\Docblock\Token;

class GenericNode extends TypeNode
{
    /**
     * @var Token
     */
    private $open;

    /**
     * @var Token
     */
    private $close;

    /**
     * @var TypeList
     */
    private $parameters;

    /**
     * @var TypeNode
     */
    private $type;

    public function __construct(Token $open, TypeNode $type, TypeList $parameters, Token $close)
    {
        $this->open = $open;
        $this->close = $close;
        $this->parameters = $parameters;
        $this->type = $type;
    }

    public function close(): Token
    {
        return $this->close;
    }

    public function open(): Token
    {
        return $this->open;
    }

    public function parameters(): TypeList
    {
        return $this->parameters;
    }

    public function type(): TypeNode
    {
        return $this->type;
    }
}
