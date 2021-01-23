<?php

namespace Phpactor\Docblock\Ast\Type;

use Phpactor\Docblock\Ast\TypeNode;
use Phpactor\Docblock\Token;

class NullableNode extends TypeNode
{
    /**
     * @var Token
     */
    private $nullable;

    /**
     * @var TypeNode
     */
    private $type;

    public function __construct(Token $nullable, TypeNode $type)
    {
        $this->nullable = $nullable;
        $this->type = $type;
    }

    public function nullable(): Token
    {
        return $this->nullable;
    }

    public function type(): TypeNode
    {
        return $this->type;
    }
}
