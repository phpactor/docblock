<?php

namespace Phpactor\Docblock\Ast;

use ArrayIterator;
use Countable;
use IteratorAggregate;

/**
 * @implements IteratorAggregate<TypeNode>
 */
class TypeList implements IteratorAggregate, Countable
{
    /**
     * @var TypeNode[]
     */
    private $typeList;

    /**
     * @param TypeNode[] $typeList
     */
    public function __construct(array $typeList)
    {
        $this->typeList = $typeList;
    }

    /**
     * @return ArrayIterator<int, TypeNode>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->typeList);
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return count($this->typeList);
    }
}
