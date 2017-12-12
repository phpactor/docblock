<?php

namespace Phpactor\Docblock;

final class Tags implements \IteratorAggregate
{
    private $tags = [];

    private function __construct(array $tags)
    {
        foreach ($tags as $item) {
            $this->add($item);
        }
    }

    public static function fromArray(array $tags): Tags
    {
         return new self($tags);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->tags);
    }

    public function byName(string $name): Tags
    {
        return new self(array_filter($this->tags, function (Tag $tag) use ($name) {
            return $name == $tag->name();
        }));
    }

    private function add(Tag $item)
    {
        $this->tags[] = $item;
    }
}
