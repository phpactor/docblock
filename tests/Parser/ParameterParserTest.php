<?php

namespace Phpactor\Docblock\Tests\Parser;

use PHPUnit\Framework\TestCase;
use Phpactor\Docblock\DefaultValue;
use Phpactor\Docblock\Method\Parameter;
use Phpactor\Docblock\DocblockTypes;
use Phpactor\Docblock\Parser\ParameterParser;

class ParameterParserTest extends TestCase
{
    /**
     * @dataProvider provideCreate
     */
    public function testCreate(string $paramString, Parameter $expected = null)
    {
        $parser = new ParameterParser();
        $this->assertEquals($expected, $parser->parse($paramString));
    }

    public function provideCreate()
    {
        return [
            'no parts' => [
                '',
                null
            ],
            'lone variable' => [
                '$foobar',
                new Parameter('foobar'),
            ],
            'typed variable' => [
                'Foobar $foobar',
                new Parameter('foobar', DocblockTypes::fromStringTypes(['Foobar'])),
            ],
            'typed variable with string default' => [
                'Foobar $foobar = "foobar"',
                new Parameter('foobar', DocblockTypes::fromStringTypes(['Foobar']), DefaultValue::ofValue('foobar')),
            ],
            'typed variable with single quoted default string' => [
                'Foobar $foobar = \'foobar\'',
                new Parameter('foobar', DocblockTypes::fromStringTypes(['Foobar']), DefaultValue::ofValue('foobar')),
            ],
            'typed variable with float' => [
                'Foobar $foobar = 1.234',
                new Parameter('foobar', DocblockTypes::fromStringTypes(['Foobar']), DefaultValue::ofValue(1.234)),
            ],
            'typed variable with int' => [
                'Foobar $foobar = 1234',
                new Parameter('foobar', DocblockTypes::fromStringTypes(['Foobar']), DefaultValue::ofValue(1234)),
            ],
            'typed variable with static call (not supported)' => [
                'Foobar $foobar = Barfoo::FOOBAR',
                new Parameter('foobar', DocblockTypes::fromStringTypes(['Foobar'])),
            ],
        ];
    }
}
