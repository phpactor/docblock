<?php

namespace Phpactor\Docblock;

use IteratorAggregate;
use ArrayIterator;
use Traversable;

final class DocblockTypes implements IteratorAggregate
{
    private $docblocktypes = [];

    private function __construct($docblocktypes)
    {
        foreach ($docblocktypes as $item) {
            $this->add($item);
        }
    }

    public static function empty(): DocblockTypes
    {
        return new self([]);
    }

    public static function fromStringTypes($types)
    {
        return new self(array_map(function (string $type) {
            return DocblockType::of($type);
        }, $types));
    }

    public static function fromDocblockTypes(array $docblocktypes): DocblockTypes
    {
        return new self($docblocktypes);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->docblocktypes);
    }

    private function add(DocblockType $item): void
    {
        $this->docblocktypes[] = $item;
    }
}
