<?php

namespace Phpactor\Docblock;

final class DefaultValue
{
    private $value;

    /**
     * @var bool
     */
    private $none;


    public function __construct($value = null, bool $none = false)
    {
        $this->value = $value;
        $this->none = $none;
    }

    public function isDefined(): bool
    {
        return $this->value !== null;
    }

    public static function none()
    {
        return new self(null, true);
    }

    public static function ofValue($value)
    {
        return new self($value);
    }

    public function value()
    {
        return $this->value;
    }
}
