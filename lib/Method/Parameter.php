<?php

namespace Phpactor\Docblock\Method;

use Phpactor\Docblock\DefaultValue;
use Phpactor\Docblock\DocblockTypes;

class Parameter
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var DocblockTypes
     */
    private $types;

    /**
     * @var DefaultValue
     */
    private $defaultValue;

    public function __construct(string $name, DocblockTypes $types = null, DefaultValue $defaultValue = null)
    {
        $this->name = $name;
        $this->types = $types ?: DocblockTypes::empty();
        $this->defaultValue = $defaultValue ?: DefaultValue::none();
    }

    public function name(): string
    {
        return $this->name;
    }

    public function types(): DocblockTypes
    {
        return $this->types;
    }

    public function defaultValue(): DefaultValue
    {
        return $this->defaultValue;
    }
}
