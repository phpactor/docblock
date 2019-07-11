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

            if (substr($type, 0, 1) === '\\') {
                $docblockTypes[] = DocblockType::fullyQualifiedNameOf(substr($type, 1));
                continue;
            }

            $docblockTypes[] = DocblockType::of($type);
        }

        return DocblockTypes::fromDocblockTypes($docblockTypes);
    }
}
