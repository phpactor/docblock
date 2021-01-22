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

    public function hasAnother(): bool
    {
        return isset($this->tokens[$this->position + 1]);
    }

    /**
     * Return the current token and move the position ahead.
     */
    public function chomp(?string $type = null): ?Token
    {
        if (!isset($this->tokens[$this->position])) {
            return null;
        }

        $token = $this->tokens[$this->position++];

        if (null !== $type && $token->type() !== $type) {
            throw new RuntimeException(sprintf(
                'Expected type "%s" at position "%s": "%s"',
                $type, $this->position,
                implode('', array_map(function (Token $token) {
                    return $token->value();
                }, $this->tokens))
            ));
        }

        return $token;
    }

    public function current(): Token
    {
        if (!isset($this->tokens[$this->position])) {
            throw new RuntimeException(sprintf(
                'No token at position "%s"', $this->position
            ));
        }

        return $this->tokens[$this->position];
    }

    public function ifNextIs(string $type): bool
    {
        if ($this->next()->type() === $type) {
            $this->position++;
            return true;
        }

        return false;
    }

    public function if(string $type): bool
    {
        if ($this->current()->type() === $type) {
            return true;
        }

        if ($this->current()->type() !== Token::T_WHITESPACE) {
            return false;
        }

        if ($this->next()->type() === $type) {
            $this->position++;
            return true;
        }

        return false;
    }

    public function next(): ?Token
    {
        if (!isset($this->tokens[$this->position + 1])) {
            return null;
        }

        return $this->tokens[$this->position + 1];
    }
}
