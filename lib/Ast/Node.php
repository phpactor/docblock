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
        $out = str_repeat(' ', $this->length());;
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
        return $this->startOf($this->getChildElements());
    }

    /**
     * @param iterable<Element|array<Element>> $elements
     */
    public function startOf(iterable $elements): int
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

    public function end(): int
    {
        return $this->endOf(array_reverse(iterator_to_array($this->getChildElements(), false)));
    }

    /**
     * @param iterable<Element|array<Element>> $elements
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
}
