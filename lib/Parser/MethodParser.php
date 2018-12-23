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

        $method = implode(' ', $parts);

        list($static, $methodName, $parameters) = $this->methodInfo($method, $parts);

        return new MethodTag(
            $this->typesParser->parseTypes($types),
            $methodName,
            $parameters,
            $static
        );
    }

    private function methodInfo($method, array $parts)
    {
        if (preg_match('{(static)?\s*(\w*?)\((.*)\)}', $method, $parts)) {
            $static = $parts[1];
            $methodName = $parts[2];
            $paramString = $parts[3];

            return [
                $static === 'static',
                $methodName,
                $this->parseParameters($paramString),
            ];
        }

        return [ false, $method, [] ];
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
