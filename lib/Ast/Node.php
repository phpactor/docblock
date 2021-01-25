<?php

namespace Phpactor\Docblock\Ast;

use Generator;
use Phpactor\Docblock\Token;

abstract class Node implements Element
{
    protected const CHILD_NAMES = [
    ];

    public function shortName(): string
    {
        return substr(get_class($this), strrpos(get_class($this), '\\') + 1);
    }

    /**
     * @return Generator<Element>
     */
    public function getDescendantNodes(): Generator
    {
        yield $this;
        yield from $this->walkNodes($this->getChildNodes());
    }

    /**
     * @param iterable<Element|array<Element>> $nodes
     *
     * @return Generator<Element>
     */
    private function walkNodes(iterable $nodes): Generator
    {
        $result = [];
        foreach ($nodes as $child) {
            if (is_array($child)) {
                yield from $this->walkNodes($child);
                continue;
            }

            if ($child instanceof Node) {
                yield from $child->getDescendantNodes();
                continue;
            }

            if ($child instanceof Token) {
                yield $child;
                continue;
            }
        }
    }

    /**
     * @return Generator<Element>
     */
    private function getChildNodes(): Generator
    {
        foreach (static::CHILD_NAMES as $name) {
            $child = $this->$name;
            if (null !== $child) {
                yield $child;
            }
        }
    }
}
