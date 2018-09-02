<?php

namespace Phpactor\Docblock\Parser;

use Phpactor\Docblock\Tag\MethodTag;

class MethodParser
{
    /**
     * @var TypesParser
     */
    private $typesParser;

    public function __construct(TypesParser $typesParser = null)
    {
        $this->typesParser = $typesParser ?: new TypesParser();
    }

    public function parseMethod(array $parts): MethodTag
    {
        if (null === $types = array_shift($parts)) {
            $types = '';
        }

        $methodName = array_shift($parts);

        return new MethodTag(
            $this->typesParser->parseTypes($types),
            $this->parseMethodName($methodName)
        );
    }

    private function parseMethodName($methodName)
    {
        if (false !== $pos = strpos($methodName, '(')) {
            return substr($methodName, 0, $pos);
        }

        return $methodName;
    }
}
