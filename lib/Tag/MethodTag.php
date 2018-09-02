<?php

namespace Phpactor\Docblock\Tag;

use Phpactor\Docblock\Tag;
use Phpactor\Docblock\DocblockException;
use Phpactor\Docblock\DocblockTypes;
use Phpactor\Docblock\Method\Parameter;

class MethodTag implements Tag
{
    /**
     * @var string
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
     * @var Parameter[] $parameters
     */
    public function __construct(DocblockTypes $types, $methodName, array $parameters = [])
    {
        $this->types = $types;
        $this->methodName = $methodName;
        $this->parameters = $parameters;
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
}
