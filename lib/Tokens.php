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

    /**
     * @var ?Token
     */
    public $current;

    /**
     * @var int
     */
    private $position = 0;

    /**
     * @param Token[] $tokens
     */
    public function __construct(array $tokens)
    {
        $this->tokens = $tokens;
        if (count($tokens)) {
            $this->current = $tokens[$this->position];
        }
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

    public function hasCurrent(): bool
    {
        return isset($this->tokens[$this->position]);
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
        $this->current = @$this->tokens[$this->position];

        if (null !== $type && $token->type !== $type) {
            throw new RuntimeException(sprintf(
                'Expected type "%s" at position "%s": "%s"',
                $type, $this->position,
                implode('', array_map(function (Token $token) {
                    return $token->value;
                }, $this->tokens))
            ));
        }

        return $token;
    }

    public function ifNextIs(string $type): bool
    {
        if ($this->next()->type === $type) {
            $this->current = @$this->tokens[++$this->position];
            return true;
        }

        return false;
    }

    /**
     * If the current or next non-whitespace node matches,
     * advance internal pointer and return true;
     */
    public function if(string $type): bool
    {
        if ($this->current->type === $type) {
            return true;
        }

        if ($this->current->type !== Token::T_WHITESPACE) {
            return false;
        }

        if ($this->next()->type === $type) {
            $this->current = $this->tokens[++$this->position];
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
