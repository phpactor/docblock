<?php

namespace Phpactor\Docblock\Ast;

use ArrayIterator;
use Iterator;
use IteratorAggregate;

/**
 * @template T of Element
 * @implements IteratorAggregate<int,T>
 */
class ElementList extends Node implements IteratorAggregate
{
    protected const CHILD_NAMES = [
        'elements',
    ];

    /**
     * @var T[]
     */
    public $elements;

    /**
     * @param T[] $elements
     */
    public function __construct(array $elements)
    {
        $this->elements = $elements;
    }

    /**
     * @return ArrayIterator<int,T>
     */
    public function getIterator(): Iterator
    {
        return new ArrayIterator($this->elements);
    }

    /**
     * @template T
     * @param class-string<T> $classFqn
     * @return ElementList<T>
     */
    public function byClass(string $classFqn): ElementList
    {
        return new self(array_filter($this->elements, function (Element $element) use ($classFqn): bool {
            return get_class($element) === $classFqn;
        }));
    }

    public function byName(string $name): ElementList
    {
        return new self(array_filter($this->elements, function (Element $element) use ($classFqn): bool {
            return get_class($element) === $classFqn;
        }));
    }

    /**
     * @return Element[]
     */
    public function toArray(): array
    {
        return $this->elements;
    }
}
