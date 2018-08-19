<?php

namespace Phpactor\Docblock\Parser;

use Phpactor\Docblock\Tag\MethodTag;

class MethodParser
{
    /**
     * @var TypesParser
     */
    private $typesParser;

    public function __construct(TypesParser $typesParser)
    {
        $this->typesParser = $typesParser;
    }

    public function parseMethod(array $metadata): MethodTag
    {
        if (null === $types = array_shift($metadata)) {
            $types = '';
        }

        $methodName = array_shift($metadata);

        return new MethodTag($this->typesParser->parseTypes($types), $this->parseMethodName($methodName));
    }

    private function parseMethodName($methodName)
    {
        if (false !== $pos = strpos($methodName, '(')) {
            return substr($methodName, 0, $pos);
        }

        return $methodName;
    }
}
