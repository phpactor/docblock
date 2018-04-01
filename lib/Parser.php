<?php

namespace Phpactor\Docblock;

use Phpactor\Docblock\Tag\DocblockTypes;
use Phpactor\Docblock\DocblockType;

class Parser
{
    const TAG = '{@([a-zA-Z0-9-_\\\]+)\s*?([\\<\\>\\[\\]&|,\\\()$\w\s]+)?}';

    public function parse($docblock): array
    {
        $lines = explode(PHP_EOL, $docblock);
        $tags = [];
        $prose = [];

        foreach ($lines as $line) {
            if (0 === preg_match(self::TAG, $line, $matches)) {
                if (null !== $line = $this->extractProse($line)) {
                    $prose[] = $line;
                }
                continue;
            }

            $tagName = $matches[1];

            if (!isset($tags[$tagName])) {
                $tags[$tagName] = [];
            }

            $metadata = explode(' ', trim($matches[2] ?? ''));
            $tags[$tagName][] = $metadata;
        }

        return [$prose, $tags ];
    }

    public function parseTypes(string $types): DocblockTypes
    {
        $types = str_replace('&', '|', $types);
        $types = explode('|', $types);
        $docblockTypes = [];

        foreach ($types as $type) {
            $type = trim($type);

            if (preg_match('{^(.*)<(.*)>$}', $type, $matches)) {
                $type = $matches[1];
                $collectionType = $matches[2];
                $docblockTypes[] = DocblockType::collectionOf($type, $collectionType);
                continue;
            }

            if (substr($type, -2) == '[]') {
                $type = substr($type, 0, -2);
                $docblockTypes[] = DocblockType::arrayOf($type);
                continue;
            }

            $docblockTypes[] = DocblockType::of($type);
        }

        return DocblockTypes::fromDocblockTypes($docblockTypes);
    }

    public function parseMethodName($methodName)
    {
        if (false !== $pos = strpos($methodName, '(')) {
            return substr($methodName, 0, $pos);
        }

        return $methodName;
    }

    private function extractProse(string $line)
    {
        $line = trim($line);

        if (empty($line)) {
            return;
        }

        if ($line == '/**') {
            return;
        }

        if ($line == '*') {
            return '';
        }

        if (substr($line, 0, 2) == '* ') {
            $line = substr($line, 2);
        }

        if (substr($line, 0, 2) == '*/') {
            return;
        }

        return $line;
    }
}
