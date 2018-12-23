<?php

namespace Phpactor\Docblock\Parser;

use Phpactor\Docblock\DefaultValue;
use Phpactor\Docblock\DocblockTypes;
use Phpactor\Docblock\Method\Parameter;

class ParameterParser
{
    /**
     * @var TypesParser
     */
    private $typesParser;

    public function __construct(TypesParser $typesParser = null)
    {
        $this->typesParser = $typesParser ?: new TypesParser();
    }

    /**
     * @return Parameter|null
     */
    public function parse(string $parameterString)
    {
        list($parameterName, $types, $defaultValue) = $this->extractParts($parameterString);
        
        if (!$parameterName) {
            return null;
        }

        return new Parameter($parameterName, $types, $defaultValue);
    }

    private function extractParts(string $parameterString)
    {
        $parts = array_map('trim', explode(' ', $parameterString));

        $types = DocblockTypes::empty();
        $parameterName = null;
        $defaultValue = null;

        foreach ($parts as $index => $part) {
            if (substr($part, 0, 1) === '$') {
                $parameterName = substr($part, 1);
                continue;
            }

            if (substr($part, 0, 3) === '...') {
                $parameterName = substr($part, 4);
                continue;
            }

            if ($index === 0) {
                $types = $this->typesParser->parseTypes($part);
                continue;
            }

            if ($part === '=' && isset($parts[$index + 1])) {
                $defaultValue = $this->parseDefaultValue($parts[$index + 1]);
                break;
            }
        }

        return [$parameterName, $types, $defaultValue];
    }

    private function parseDefaultValue(string $defaultValueString): DefaultValue
    {
        if (is_numeric($defaultValueString)) {
            // hack to cast to either a float or an int
            return DefaultValue::ofValue($defaultValueString + 0);
        }

        if (in_array(substr($defaultValueString, 0, 1), ['"', '\''])) {
            return DefaultValue::ofValue(trim($defaultValueString, '"\''));
        }

        return DefaultValue::none();
    }
}
