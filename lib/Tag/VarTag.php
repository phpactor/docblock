<?php

namespace Phpactor\Docblock\Tag;

use Phpactor\Docblock\Tag;
use Phpactor\Docblock\DocblockException;
use Phpactor\Docblock\Tag\DocblockTypes;

class VarTag implements Tag
{
    public function name()
    {
        return 'var';
    }

    /**
     * @var array
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

    public function types(): DocblockTypes
    {
        return $this->types;
    }

    public function varName()
    {
        return $this->varName;
    }
}
