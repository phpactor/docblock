<?php

namespace Phpactor\Docblock\Parser;

use Phpactor\Docblock\DocblockType;
use Phpactor\Docblock\DocblockTypes;

class TypesParser
{
    public function parseTypes(string $types): DocblockTypes
    {
        if (empty($types)) {
            return DocblockTypes::empty();
        }

        $types = str_replace('&', '|', $types);
        $docblockTypes = [];

        foreach (explode('|', $types) as $type) {
            $type = trim($type, "? \t\n\r\0\x0B");

            if (preg_match('{^(.*)<(.*)>$}', $type, $matches)) {
                $type = $matches[1];
                $collectionType = trim($matches[2], "? \t\n\r\0\x0B");
                $docblockTypes[] = DocblockType::collectionOf($type, $collectionType);
                continue;
            }

            if (substr($type, -2) == '[]') {
                $type = trim(substr($type, 0, -2), "? \t\n\r\0\x0B");;
                $docblockTypes[] = DocblockType::arrayOf($type);
                continue;
            }

            if (substr($type, 0, 1) === '\\') {
                $docblockTypes[] = DocblockType::fullyQualifiedNameOf(substr($type, 1));
                continue;
            }

            $docblockTypes[] = DocblockType::of($type);
        }

        return DocblockTypes::fromDocblockTypes($docblockTypes);
    }
}
