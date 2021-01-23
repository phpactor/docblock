<?php

namespace Phpactor\Docblock\Ast;

class ReturnNode extends TagNode
{
    /**
     * @var TypeNode|null
     */
    private $type;

    public function __construct(?TypeNode $type)
    {
        $this->type = $type;
    }

    public function type(): ?TypeNode
    {
        return $this->type;
    }
}
