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
    private $iteratedType;

    private function __construct(string $type, string $iteratedType = null)
    {
        $this->type = $type;
        $this->iteratedType = $iteratedType;
    }

    public static function collectionOf(string $type, string $iteratedType): DocblockType
    {
        return new self($type, $iteratedType);
    }

    public static function of(string $type): DocblockType
    {
        return new self($type, false);
    }

    public static function arrayOf(string $type): DocblockType
    {
        return new self('array', $type);
    }

    /**
     * @return string|null
     */
    public function iteratedType()
    {
        return $this->iteratedType;
    }

    public function isArray(): bool
    {
        return $this->type === 'array';
    }

    public function isCollection(): bool
    {
        return $this->iteratedType && $this->type !== 'array';
    }

    public function __toString()
    {
        return $this->type;
    }
}
