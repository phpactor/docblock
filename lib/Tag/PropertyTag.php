<?php

namespace Phpactor\Docblock\Tag;

use Phpactor\Docblock\Tag;
use Phpactor\Docblock\DocblockTypes;

class PropertyTag implements Tag
{
    /**
     * @var DocblockTypes
     */
    private $types;

    private $propertyName;

    public function __construct(DocblockTypes $types, string $propertyName)
    {
        $this->types = $types;
        $this->propertyName = $propertyName;
    }

    public function name()
    {
        return 'property';
    }

    public function propertyName()
    {
        return $this->propertyName;
    }

    public function types(): DocblockTypes
    {
        return $this->types;
    }
}
