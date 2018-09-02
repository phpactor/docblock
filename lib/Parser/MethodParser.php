<?php

namespace Phpactor\Docblock\Parser;

use Phpactor\Docblock\DocblockType;
use Phpactor\Docblock\Method\Parameter;
use Phpactor\Docblock\DocblockTypes;
use Phpactor\Docblock\Tag\MethodTag;

class MethodParser
{
    /**
     * @var TypesParser
     */
    private $typesParser;

    /**
     * @var ParameterParser
     */
    private $parameterParser;


    public function __construct(TypesParser $typesParser = null, ParameterParser $parameterParser = null)
    {
        $this->typesParser = $typesParser ?: new TypesParser();
        $this->parameterParser = $parameterParser ?: new ParameterParser($typesParser);
    }

    public function parseMethod(array $parts): MethodTag
    {
        if (null === $types = array_shift($parts)) {
            $types = '';
        }

        $method = array_shift($parts);

        list($methodName, $parameters) = $this->methodNameAndParameters($method, $parts);

        return new MethodTag(
            $this->typesParser->parseTypes($types),
            $methodName,
            $parameters
        );
    }

    private function methodNameAndParameters($method, array $parts)
    {
        if (preg_match('{(.*?)\((.*)\)}', $method, $parts)) {
            $methodName = $parts[1];
            $paramString = $parts[2];

            return [
                $methodName,
                $this->parseParameters($paramString)
            ];
        }

        return [ $method, [] ];
    }

    private function parseParameters(string $paramString): array
    {
        $parameters = array_map(function (string $param) {
            return trim($param);
        }, explode(', ', $paramString));

        $parameters = array_filter(array_map(function (string $paramString) {
            return $this->parameterParser->parse($paramString);
        }, $parameters));

        return $parameters;
    }
}
