<?php

namespace Phpactor\Docblock\Ast\Type;

use Phpactor\Docblock\Ast\TypeNode;
use Phpactor\Docblock\Token;

class ListNode extends TypeNode
{
    /**
     * @var TypeNode
     */
    private $type;

    /**
     * @var Token
     */
    private $listChars;

    public function __construct(TypeNode $type, Token $listChars)
    {
        $this->type = $type;
        $this->listChars = $listChars;
    }

    public function type(): TypeNode
    {
        return $this->type;
    }

    public function listChars(): Token
    {
        return $this->listChars;
    }
}
