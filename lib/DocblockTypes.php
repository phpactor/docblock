<?php

namespace Phpactor\Docblock;

final class DocblockTypes implements \IteratorAggregate
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

    public function getIterator()
    {
        return new \ArrayIterator($this->docblocktypes);
    }

    private function add(DocblockType $item)
    {
        $this->docblocktypes[] = $item;
    }
}
