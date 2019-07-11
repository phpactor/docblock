<?php

namespace Phpactor\Docblock\Parser;

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
        $method = implode(' ', $parts);

        list($static, $types, $methodName, $parameters) = $this->methodInfo($method, $parts);

        return new MethodTag(
            $this->typesParser->parseTypes($types),
            $methodName,
            $parameters,
            $static
        );
    }

    private function methodInfo(string $method, array $parts): array
    {
        if (empty($method)) {
            return [ false , '', $method, [] ];
        }

        if (substr($method, -1) !== ')') {
            $method .= '()';
        }

        if (preg_match('{(static)?\s*([\w\\\]+)?\s+(\w*?)\s*\((.*)\)}', $method, $parts)) {
            $static = $parts[1];
            $types = $parts[2];
            $methodName = $parts[3];
            $paramString = $parts[4];

            return [
                $static === 'static',
                $types,
                $methodName,
                $this->parseParameters($paramString),
            ];
        }

        return [ false, '', $method, [] ];
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
