<?php

namespace Phpactor\Docblock\Tests\Parser;

use PHPUnit\Framework\TestCase;
use Phpactor\Docblock\DefaultValue;
use Phpactor\Docblock\DocblockType;
use Phpactor\Docblock\Method\Parameter;
use Phpactor\Docblock\Parser\MethodParser;
use Phpactor\Docblock\DocblockTypes;
use Phpactor\Docblock\Tag\MethodTag;

class MethodParserTest extends TestCase
{
    /**
     * @dataProvider provideCreate
     */
    public function testCreate(array $parts, MethodTag $expected)
    {
        $parser = new MethodParser();
        $this->assertEquals($expected, $parser->parseMethod($parts));
    }

    public function provideCreate()
    {
        return [
            'no parts' => [
                [ ],
                new MethodTag(DocblockTypes::empty(), ''),
            ],
            'type only parts' => [
                [ 'Foobar' ],
                new MethodTag(DocblockTypes::fromStringTypes(['Foobar']), ''),
            ],
            'no parenthesis' => [
                [ 'Foobar', 'foobar' ],
                new MethodTag(DocblockTypes::fromStringTypes([ 'Foobar' ]), 'foobar'),
            ],
            'single type' => [
                [ 'Foobar', 'foobar()' ],
                new MethodTag(DocblockTypes::fromStringTypes([ 'Foobar' ]), 'foobar'),
            ],
            'with parameters' => [
                [ 'Foobar', 'foobar(Foobar $foobar, string $foo, $bar)' ],
                new MethodTag(
                    DocblockTypes::fromStringTypes([ 'Foobar' ]), 
                    'foobar',
                    [
                        new Parameter('foobar', DocblockTypes::fromDocblockTypes([ DocblockType::of('Foobar') ])),
                        new Parameter('foo', DocblockTypes::fromDocblockTypes([ DocblockType::of('string') ])),
                        new Parameter('bar', DocblockTypes::fromDocblockTypes([ ] )),
                    ]
                ),
            ],
            'with parameters and default value' => [
                [ 'Foobar', 'foobar(string $foo = "hello", $bar)' ],
                new MethodTag(
                    DocblockTypes::fromStringTypes([ 'Foobar' ]), 
                    'foobar',
                    [
                        new Parameter(
                            'foo',
                            DocblockTypes::fromDocblockTypes([ DocblockType::of('string') ]),
                            DefaultValue::ofValue('hello')
                        ),
                        new Parameter('bar', DocblockTypes::fromDocblockTypes([ ])),
                    ]
                ),
            ],
            'static method' => [
                [ 'Foobar', 'static', 'foobar()' ],
                new MethodTag(
                    DocblockTypes::fromStringTypes([ 'Foobar' ]), 
                    'foobar',
                    [],
                    true
                ),
            ]
        ];
    }
}
