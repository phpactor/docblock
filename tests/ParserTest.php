<?php

namespace Phpactor\Docblock\Tests;

use PHPUnit\Framework\TestCase;
use Phpactor\Docblock\Parser;
use Phpactor\Docblock\Tag\DocblockTypes;
use Phpactor\Docblock\DocblockType;

class ParserTest extends TestCase
{
    /**
     * @dataProvider provideParseTags
     */
    public function testParseTags($docblock, $expected)
    {
        $parser = new Parser();
        list($prose, $tags) = $parser->parse($docblock);
        $this->assertEquals($expected, $tags);
    }

    public function provideParseTags()
    {
        yield [
            '/** @var Foobar */',
            [ 'var' => [ [ 'Foobar' ] ] ],
        ];

        yield [
            '/** @var Foobar[] */',
            [ 'var' => [ [ 'Foobar[]' ] ] ],
        ];

        yield 'for collection' => [
            '/** @var Foobar<Item> */',
            [ 'var' => [ [ 'Foobar<Item>' ] ] ],
        ];

        yield [
            '/** @var Foobar $foobar */',
            [ 'var' => [ [ 'Foobar', '$foobar' ] ] ],
        ];

        yield 'named var with irregular spacing' => [
            '/** @var   Foobar  $foobar */',
            [ 'var' => [ [ 'Foobar', '$foobar' ] ] ],
        ];

        yield [
            <<<'EOT'
/** 
 * @var Foobar $foobar 
 * @var Barfoo $barfoo
 **/
EOT
        ,
            ['var' => [
                [ 'Foobar', '$foobar' ],
                [ 'Barfoo', '$barfoo' ]
            ]],
        ];

        yield [
            <<<'EOT'
/** 
 * @var Foobar $foobar Hello this is description
 **/
EOT
        ,
            ['var' => [
                [ 'Foobar', '$foobar', 'Hello',  'this', 'is', 'description' ],
            ]],
        ];

        yield [
            <<<'EOT'
/** 
 * @method \Foobar\Barfoo foobar()
 **/
EOT
        ,
            ['method' => [
                [ '\Foobar\Barfoo', 'foobar()' ],
            ]],
        ];

        yield [
            '/** @method \Barfoo foobar($foobar, string $foo) */',
            [ 'method' => [ [ '\Barfoo', 'foobar($foobar,', 'string', '$foo)' ] ] ],
        ];

        yield [
            '/** @method Foobar[] */',
            [ 'method' => [ [ 'Foobar[]' ] ] ],
        ];
    }

    /**
     * @dataProvider provideParseTypes
     */
    public function testParseTypes(string $types, DocblockTypes $expected)
    {
        $parser = new Parser();
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
        ];
    }

    /**
     * @dataProvider provideParseProse
     */
    public function testParseProse($docblock, $expected)
    {
        $parser = new Parser();
        list($prose, $tags) = $parser->parse($docblock);
        $this->assertEquals($expected, $prose);
    }

    public function provideParseProse()
    {
        return [
            [
                <<<'EOT'
/**
  * Hello
  *
  * This is a description
  *
  * @return Foo
 */
EOT
        ,
            [ 'Hello', '', 'This is a description', '' ],
        ],
    ];
    }
}
