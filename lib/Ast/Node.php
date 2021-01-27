<?php

namespace Phpactor\Docblock\Ast;

use Generator;

abstract class Node implements Element
{
    protected const CHILD_NAMES = [
    ];

    public function toString(): string
    {
        $out = str_repeat(' ', $this->length());
        ;
        $start = $this->start();
        foreach ($this->tokens() as $token) {
            $out = substr_replace($out, $token->value, $token->start() - $start, $token->length());
        }

        return $out;
    }

    /**
     * @return Generator<Token>
     */
    public function tokens(): Generator
    {
        yield from $this->findTokens($this->children());
    }

    /**
     * Return the short name of the node class (e.g. ParamTag)
     */
    public function shortName(): string
    {
        return substr(get_class($this), strrpos(get_class($this), '\\') + 1);
    }

    /**
     * @return Generator<Element>
     */
    public function selfAndDescendantElements(): Generator
    {
        yield $this;
        yield from $this->traverseNodes($this->children());
    }

    /**
     * @return Generator<Element>
     */
    public function descendantElements(): Generator
    {
        yield from $this->traverseNodes($this->children());
    }

    /**
     * @return Generator<Element>
     */
    public function children(): Generator
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
        return $this->startOf($this->children());
    }

    public function end(): int
    {
        return $this->endOf(array_reverse(iterator_to_array($this->children(), false)));
    }

    /**
     * @param iterable<Element|array<Element>> $nodes
     *
     * @return Generator<Element>
     */
    private function traverseNodes(iterable $nodes): Generator
    {
        $result = [];
        foreach ($nodes as $child) {
            if (is_array($child)) {
                yield from $this->traverseNodes($child);
                continue;
            }

            if ($child instanceof Node) {
                yield from $child->selfAndDescendantElements();
                continue;
            }

            if ($child instanceof Token) {
                yield $child;
                continue;
            }
        }
    }

    /**
     * @param iterable<null|Element|array<Element>> $elements
     */
    private function endOf(iterable $elements): int
    {
        foreach ($elements as $element) {
            if (null === $element) {
                continue;
            }

            if (is_array($element)) {
                return $this->endOf(array_reverse($element));
            }

            return $element->end();
        }

        return 0;
    }

    private function length(): int
    {
        return $this->end() - $this->start();
    }

    /**
     * @param iterable<Element|array<Element>> $elements
     */
    private function startOf(iterable $elements): int
    {
        foreach ($elements as $element) {
            if ($element instanceof Element) {
                return $element->start();
            }
            if (is_array($element)) {
                return $this->startOf($element);
            }
        }

        return 0;
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
}
