<?php

namespace Phpactor\Docblock\Ast;

use Generator;
use Phpactor\Docblock\Token;

abstract class Node implements Element
{
    protected const CHILD_NAMES = [
    ];

    public function toString(): string
    {
        return implode(' ', array_map(function (Token $token) {
            return $token->value;
        }, iterator_to_array($this->tokens(), false)));
    }

    /**
     * @return Generator<Token>
     */
    public function tokens(): Generator
    {
        yield from $this->findTokens($this->getChildElements());
    }

    /**
     * @return Generator<Token>
     * @param iterable<Element|array<Element>> $nodes
     */
    private function findTokens(iterable $nodes): Generator
    {
        foreach ($nodes as $node) {
            if ($node instanceof Token) {
                yield $node;
                continue;
            }

            if ($node instanceof Node) {
                yield from $node->tokens();
            }

            if (is_array($node)) {
                yield from $this->findTokens($node);
            }
        }
    }

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
        yield from $this->walkNodes($this->getChildElements());
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
    public function getChildElements(): Generator
    {
        foreach (static::CHILD_NAMES as $name) {
            $child = $this->$name;
            if (null !== $child) {
                yield $child;
            }
        }
    }

    public function start(): int
    {
        $first = $this->getChildElements()->current();
        if (null === $first) {
            return 0;
        }

        return $first->start();
    }

    public function end(): int
    {
        foreach (array_reverse(static::CHILD_NAMES) as $childName) {
            $element = $this->$childName;
            if (null === $element) {
                continue;
            }

            return $element->end();
        }
        return 0;
    }
}
