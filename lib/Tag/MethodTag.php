<?php

namespace Phpactor\Docblock\Tag;

use Phpactor\Docblock\Tag;
use Phpactor\Docblock\DocblockTypes;
use Phpactor\Docblock\Method\Parameter;

class MethodTag implements Tag
{
    /**
     * @var DocblockTypes
     */
    private $types;

    /**
     * @var string
     */
    private $methodName;

    /**
     * @var Parameter[]
     */
    private $parameters;

    /**
     * @var bool
     */
    private $isStatic;

    /**
     * @var Parameter[] $parameters
     */
    public function __construct(DocblockTypes $types, $methodName, array $parameters = [], bool $isStatic = false)
    {
        $this->types = $types;
        $this->methodName = $methodName;
        $this->parameters = $parameters;
        $this->isStatic = $isStatic;
    }

    public function name()
    {
        return 'method';
    }

    public function types(): DocblockTypes
    {
        return $this->types;
    }

    public function methodName()
    {
        return $this->methodName;
    }

    public function parameters(): array
    {
        return $this->parameters;
    }

    public function isStatic(): bool
    {
        return $this->isStatic;
    }
}
