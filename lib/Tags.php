<?php

namespace Phpactor\Docblock;

use IteratorAggregate;
use Countable;
use ArrayIterator;
use Traversable;

final class Tags implements IteratorAggregate, Countable
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

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->tags);
    }

    public function byName(string $name): Tags
    {
        $name = strtolower($name);
        return new self(array_filter($this->tags, function (Tag $tag) use ($name) {
            return $name == strtolower($tag->name());
        }));
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return count($this->tags);
    }

    private function add(Tag $item): void
    {
        $this->tags[] = $item;
    }
}
