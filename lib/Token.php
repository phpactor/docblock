<?php

namespace Phpactor\Docblock;

class Token
{
    public const T_BORDER = 'BORDER';
    public const T_TEXT = 'TEXT';
    public const T_VARIABLE = 'VARIABLE';
    public const T_UNKNOWN = 'UNKNOWN';
    public const T_TAG = 'TAG';
    public const T_LABEL = 'LABEL';
    public const T_WHITESPACE = 'WHITESPACE';
    public const T_BRACKET_SQUARE_OPEN = 'BRACKET_SQUARE_OPEN';
    public const T_BRACKET_SQUARE_CLOSE = 'BRACKET_SQUARE_CLOSE';
    public const T_BRACKET_ANGLE_OPEN = 'BRACKET_ANGLE_OPEN';
    public const T_BRACKET_ANGLE_CLOSE = 'BRACKET_ANGLE_CLOSE';

    /**
     * @var int
     */
    private $byteOffset;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $value;

    public function __construct(int $byteOffset, string $type, string $value)
    {
        $this->byteOffset = $byteOffset;
        $this->type = $type;
        $this->value = $value;
    }

    public function byteOffset(): int
    {
        return $this->byteOffset;
    }

    public function value(): string
    {
        return $this->value;
    }
}
