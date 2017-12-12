<?php

namespace Phpactor\Docblock\Tag;

use Phpactor\Docblock\Tag;
use Phpactor\Docblock\DocblockException;

class MethodTag implements Tag
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $methodName;

    public function __construct(array $metadata)
    {
        if (null === $type = array_shift($metadata)) {
            throw new DocblockException(
                'Method tag has no type'
            );
        }

        $this->type = $type;

        if (null === $methodName = array_shift($metadata)) {
            throw new DocblockException(
                'Method tag has no name'
            );
        }

        $this->methodName = $this->extractMethodName($methodName);
    }

    public function type(): string
    {
        return $this->type;
    }

    public function methodName()
    {
        return $this->methodName;
    }

    private function extractMethodName(string $methodName)
    {
        if (false !== $pos = strpos($methodName, '(')) {
            return substr($methodName, 0, $pos);
        }

        return $methodName;
    }
}
