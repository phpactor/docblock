<?php

namespace Phpactor\Docblock\Ast\Type;

use Phpactor\Docblock\Ast\TypeNode;
use Phpactor\Docblock\Token;

class ScalarNode extends TypeNode
{
    /**
     * @var Token
     */
    private $name;

    public function __construct(Token $name)
    {
        $this->name = $name;
    }

    public function name(): Token
    {
        return $this->name;
    }
}
