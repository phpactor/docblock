<?php

namespace Phpactor\Docblock\Tests\Parser;

use PHPUnit\Framework\TestCase;
use Phpactor\Docblock\DocblockType;
use Phpactor\Docblock\Parser\TypesParser;
use Phpactor\Docblock\DocblockTypes;

class TypesParserTest extends TestCase
{
    /**
     * @dataProvider provideParseTypes
     */
    public function testParseTypes(string $types, DocblockTypes $expected)
    {
        $parser = new TypesParser();
        $this->assertEquals($expected, $parser->parseTypes($types));
    }

    public function provideParseTypes()
    {
        return [
            [
                'Foobar',
                DocblockTypes::fromStringTypes(['Foobar']),
            ],
            [
                'Foobar[]',
                DocblockTypes::fromDocblockTypes([ DocblockType::arrayOf('Foobar') ]),
            ],
            [
                'Foobar<Item>',
                DocblockTypes::fromDocblockTypes([ DocblockType::collectionOf('Foobar', 'Item') ]),
            ],
            [
                '\Foobar\Foobar',
                DocblockTypes::fromDocblockTypes([ DocblockType::fullyQualifiedNameOf('Foobar\Foobar', 'Item') ]),
            ],
        ];
    }
}
