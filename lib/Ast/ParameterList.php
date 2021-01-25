<?php

namespace Phpactor\Docblock\Ast;

use ArrayIterator;
use Countable;
use IteratorAggregate;

/**
 * @implements IteratorAggregate<ParameterNode>
 */
class ParameterList extends Node implements IteratorAggregate, Countable
{
    protected const CHILD_NAMES = [
        'list'
    ];
    /**
     * @var ParameterNode[]
     */
    public $list;

    /**
     * @param ParameterNode[] $list
     */
    public function __construct(array $list)
    {
        $this->list = $list;
    }

    /**
     * @return ArrayIterator<int, ParameterNode>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->list);
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return count($this->list);
    }

    public function toString(): string
    {
        return implode(', ', array_map(function (Element $element) {
            return $element->toString();
        }, $this->list));
    }
}
