<?php

namespace Phpactor\Docblock\Parser;

use Phpactor\Docblock\DocblockType;
use Phpactor\Docblock\DocblockTypes;

class TypesParser
{
    /**
     * Mask to remove white space and nullable prefixes(?)
     *
     * The current Phpactor's type system doesn't handle "nullable",
     * it just erases at the same time as white space.
     */
    private const MASK_WHITESPACE_AND_IGNORED_PREFIX = "? \t\n\r\0\x0B";

    public function parseTypes(string $types): DocblockTypes
    {
        if (empty($types)) {
            return DocblockTypes::empty();
        }

        $types = str_replace('&', '|', $types);
        $docblockTypes = [];

        foreach (explode('|', $types) as $type) {
            $type = trim($type, self::MASK_WHITESPACE_AND_IGNORED_PREFIX);

            if (preg_match('{^(.*)<(.*)>$}', $type, $matches)) {
                $type = $matches[1];
                $collectionType = trim($matches[2], self::MASK_WHITESPACE_AND_IGNORED_PREFIX);
                $docblockTypes[] = DocblockType::collectionOf($type, $collectionType);
                continue;
            }

            if (substr($type, -2) == '[]') {
                $type = trim(substr($type, 0, -2), self::MASK_WHITESPACE_AND_IGNORED_PREFIX);
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
