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
            //'type only parts' => [
            //    [ 'Foobar' ],
            //    new MethodTag(DocblockTypes::fromStringTypes(['Foobar']), ''),
            //],
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
                        new Parameter('bar', DocblockTypes::fromDocblockTypes([ ])),
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
                [ 'static', 'Foobar', 'foobar()' ],
                new MethodTag(
                    DocblockTypes::fromStringTypes([ 'Foobar' ]),
                    'foobar',
                    [],
                    true
                ),
            ],
            'phpspec be constructed with' => [
                [ 'void', 'foobar(...$arguments)' ],
                new MethodTag(
                    DocblockTypes::fromStringTypes(['void']),
                    'foobar',
                    [
                        new Parameter(
                            'arguments',
                            DocblockTypes::fromDocblockTypes([])
                        ),
                    ]
                ),
            ],
            'phpspec be constructed through' => [
                [ 'void', 'beConstructedThrough($factoryMethod, array $constructorArguments = array())' ],
                new MethodTag(
                    DocblockTypes::fromStringTypes(['void']),
                    'beConstructedThrough',
                    [
                        new Parameter(
                            'factoryMethod',
                            DocblockTypes::fromDocblockTypes([])
                        ),
                        new Parameter(
                            'constructorArguments',
                            DocblockTypes::fromDocblockTypes([ DocblockType::of('array') ]),
                            DefaultValue::none()
                        ),
                    ]
                ),
            ],
            'phpspec should have count' => [
                [ 'void', 'shouldHaveCount($count)' ],
                new MethodTag(
                    DocblockTypes::fromStringTypes(['void']),
                    'shouldHaveCount',
                    [
                        new Parameter(
                            'count',
                            DocblockTypes::empty()
                        ),
                    ]
                ),

            ],
            'laravel db' => [
                [ 'static', '\\Illuminate\\Database\\Query\\Builder', 'table(string $table)' ],
                new MethodTag(
                    DocblockTypes::fromDocblockTypes([DocblockType::fullyQualifiedNameOf('Illuminate\\Database\\Query\\Builder')]),
                    'table',
                    [
                        new Parameter(
                            'table',
                            DocblockTypes::fromStringTypes(['string'])
                        ),
                    ],
                    true
                ),

            ],
            'laravel route' => [
                [
                    'static',
                    '\\Illuminate\\Routing\\Route',
                    'get(string $uri, \\Closure|array|string|callable|null $action = null)'
                ],
                new MethodTag(
                    DocblockTypes::fromDocblockTypes([DocblockType::fullyQualifiedNameOf('Illuminate\\Routing\\Route')]),
                    'get',
                    [
                        new Parameter(
                            'uri',
                            DocblockTypes::fromStringTypes(['string'])
                        ),
                        new Parameter(
                            'action',
                            DocblockTypes::fromDocblockTypes([
                                DocblockType::fullyQualifiedNameOf('Closure'),
                                DocblockType::of('array'),
                                DocblockType::of('string'),
                                DocblockType::of('callable'),
                                DocblockType::of('null'),
                            ])
                        ),
                    ],
                    true
                )
            ],
        ];
    }
}
