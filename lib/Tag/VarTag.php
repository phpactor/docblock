<?php

namespace Phpactor\Docblock\Tag;

use Phpactor\Docblock\Tag;
use Phpactor\Docblock\DocblockTypes;

class VarTag implements Tag
{

    /**
     * @var DocblockTypes
     */
    private $types;

    /**
     * @var string
     */
    private $varName;

    public function __construct(DocblockTypes $types, $varName = null)
    {
        $this->types = $types;
        $this->varName = $varName;
    }
    public function name()
    {
        return 'var';
    }

    public function types(): DocblockTypes
    {
        return $this->types;
    }

    public function varName()
    {
        return $this->varName;
    }
}
