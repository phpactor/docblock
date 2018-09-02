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
    private $type;

    /**
     * @var DefaultValue
     */
    private $defaultValue;

    public function __construct(string $name, DocblockTypes $type = null, DefaultValue $defaultValue = null)
    {
        $this->name = $name;
        $this->type = $type ?: DocblockTypes::empty();
        $this->defaultValue = $defaultValue ?: DefaultValue::none();
    }

    public function name(): string
    {
        return $this->name;
    }

    public function type(): DocblockTypes
    {
        return $this->type;
    }

    public function defaultValue(): DefaultValue
    {
        return $this->defaultValue;
    }
}

