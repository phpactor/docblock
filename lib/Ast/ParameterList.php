<?php

namespace Phpactor\Docblock\Ast;

use ArrayIterator;
use Countable;
use IteratorAggregate;

/**
 * @implements IteratorAggregate<ParameterNode>
 */
class ParameterList implements IteratorAggregate, Countable
{
    /**
     * @var ParameterNode[]
     */
    private $parameterList;

    /**
     * @param ParameterNode[] $parameterList
     */
    public function __construct(array $parameterList)
    {
        $this->parameterList = $parameterList;
    }

    /**
     * @return ArrayIterator<int, ParameterNode>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->parameterList);
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return count($this->parameterList);
    }
}
