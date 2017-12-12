<?php

namespace Phpactor\Docblock;

class DocblockType
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $isArray;

    private function __construct(string $type, int $isArray)
    {
        $this->type = $type;
        $this->isArray = $isArray;
    }

    public static function arrayOf(string $type)
    {
        return new self($type, true);
    }

    public static function of(string $type)
    {
        return new self($type, false);
    }

    public function isArray(): bool
    {
        return $this->isArray;
    }

    public function __toString()
    {
        return $this->type;
    }
}
