<?php

namespace Phpactor\Docblock\Ast;

use Phpactor\Docblock\Token;

class PropertyNode extends TagNode
{
    /**
     * @var TypeNode|null
     */
    private $type;

    /**
     * @var Token|null
     */
    private $name;

    public function __construct(?TypeNode $type, ?Token $name)
    {
        $this->type = $type;
        $this->name = $name;
    }

    public function name(): ?Token
    {
        return $this->name;
    }

    public function type(): ?TypeNode
    {
        return $this->type;
    }
}
