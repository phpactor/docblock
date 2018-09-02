<?php

namespace Phpactor\Docblock\Tests\Parser;

use PHPUnit\Framework\TestCase;
use Phpactor\Docblock\Parser\MethodParser;
use Phpactor\Docblock\Tag\DocblockTypes;
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
            'method no parenthesis' => [
                [ 'Foobar', 'foobar' ],
                new MethodTag(DocblockTypes::fromStringTypes([ 'Foobar' ]), 'foobar'),
            ],
            'method single type' => [
                [ 'Foobar', 'foobar()' ],
                new MethodTag(DocblockTypes::fromStringTypes([ 'Foobar' ]), 'foobar'),
            ],
        ];
    }
}
