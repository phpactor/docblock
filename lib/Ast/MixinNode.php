<?php

namespace Phpactor\Docblock\Ast;

use Phpactor\Docblock\Ast\Type\ClassNode;

class MixinNode extends TagNode
{
    /**
     * @var ClassNode|null
     */
    private $class;

    public function __construct(?ClassNode $class)
    {
        $this->class = $class;
    }

    public function class(): ?ClassNode
    {
        return $this->class;
    }
}
