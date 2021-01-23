<?php

namespace Phpactor\Docblock\Ast\Type;

use Phpactor\Docblock\Ast\TypeList;
use Phpactor\Docblock\Ast\TypeNode;

class UnionNode extends TypeNode
{
    /**
     * @var TypeList
     */
    private $types;

    public function __construct(TypeList $types)
    {
        $this->types = $types;
    }

    public function types(): TypeList
    {
        return $this->types;
    }
}
