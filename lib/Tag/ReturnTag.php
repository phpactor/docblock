<?php

namespace Phpactor\Docblock\Tag;

use Phpactor\Docblock\Tag;
use Phpactor\Docblock\DocblockTypes;

class ReturnTag implements Tag
{
    /**
     * @var DocblockTypes
     */
    private $types;

    public function __construct(DocblockTypes $types)
    {
        $this->types = $types;
    }

    public function name()
    {
        return 'return';
    }

    public function types(): DocblockTypes
    {
        return $this->types;
    }
}
