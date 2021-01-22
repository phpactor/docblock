<?php

namespace Phpactor\Docblock;

use ArrayIterator;
use IteratorAggregate;
use RuntimeException;

final class Tokens implements IteratorAggregate
{
    /**
     * @var Token[]
     */
    private $tokens;

    private $position = 0;

    /**
     * @param Token[] $tokens
     */
    public function __construct(array $tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * @return Token[]
     */
    public function toArray(): array
    {
        return $this->tokens;
    }

    /**
     * @return ArrayIterator<int,Token>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->tokens);
    }

    public function peek(): ?Token
    {
        if (!isset($this->tokens[$this->position + 1])) {
            return null;
        }

        return $this->tokens[$this->position + 1];
    }

    public function next(): ?Token
    {
        if (!isset($this->tokens[$this->position + 1])) {
            return null;
        }

        return $this->tokens[++$this->position];
    }

    public function current(): Token
    {
        if (!isset($this->tokens[$this->position])) {
            throw new RuntimeException(sprintf(
                'No token at current position "%s"',
                $this->position
            ));
        }

        return $this->tokens[$this->position];
    }

    /**
     * Skip until all tokens of the given type
     */
    public function skip(string $type): self
    {
        if ($this->current()->type() !== $type) {
            return $this;
        }

        while (null !== $current = $this->next()) {
            if ($current->type() === $type) {
                continue;
            }

            return $this;
        }

        return $this;
    }

    public function isType(string $type): bool
    {
        return $this->current()->type() === $type;
    }
}
