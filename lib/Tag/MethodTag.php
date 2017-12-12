<?php

namespace Phpactor\Docblock\Tag;

use Phpactor\Docblock\Tag;
use Phpactor\Docblock\DocblockException;
use Phpactor\Docblock\Tag\DocblockTypes;

class MethodTag implements Tag
{
    /**
     * @var string
     */
    private $types;

    /**
     * @var string
     */
    private $methodName;

    public function __construct(DocblockTypes $types, $methodName)
    {
        $this->types = $types;
        $this->methodName = $methodName;
    }

    public function name()
    {
        return 'method';
    }

    public function types(): DocblockTypes
    {
        return $this->types;
    }

    public function methodName()
    {
        return $this->methodName;
    }
}
