<?php

namespace Phpactor\Docblock\Tag;

use Phpactor\Docblock\Tag;

class PropertyTag implements Tag
{
    /**
     * @var DocblockTypes
     */
    private $types;

    private $propertyName;

    public function __construct(DocblockTypes $types, $methodName)
    {
        $this->types = $types;
        $this->methodName = $methodName;
    }

    public function name()
    {
        return 'property';
    }

    public function propertyName()
    {
        return $this->propertyName;
    }
}
