<?php

namespace Phpactor\Docblock\Tests;

use PHPUnit\Framework\TestCase;
use Phpactor\Docblock\Parser;
use Phpactor\Docblock\Docblock;
use Phpactor\Docblock\Tag\VarTag;
use Phpactor\Docblock\DocblockFactory;
use Phpactor\Docblock\Tag\ParamTag;
use Phpactor\Docblock\Tag\MethodTag;

class DocblockFactoryTest extends TestCase
{
    /**
     * @var Parser
     */
    private $parser;

    public function setUp()
    {
        $this->parser = $this->prophesize(Parser::class);
    }

    /**
     * @dataProvider provideCreate
     */
    public function testCreate($tagData, Docblock $expected)
    {
        $factory = new DocblockFactory($this->parser->reveal());
        $docblock = 'ABCD';
        $this->parser->parseTags($docblock)->willReturn($tagData);
        $docblock = $factory->create($docblock);
        $this->assertEquals($expected, $docblock);
    }

    public function provideCreate()
    {
        return [
            [
                [ ],
                Docblock::fromTags([]),
            ],
            [
                [ 'var' => [ [ 'Foobar' ] ] ],
                Docblock::fromTags([ new VarTag([ 'Foobar' ]), ]),
            ],
            [
                [ 'param' => [ [ 'Foobar', '$foobar' ] ] ],
                Docblock::fromTags([ new ParamTag([ 'Foobar', '$foobar' ]), ]),
            ],
            [
                [ 'method' => [ [ 'Foobar', 'foobar()' ] ] ],
                Docblock::fromTags([ new MethodTag([ 'Foobar', 'foobar()' ]), ]),
            ],
        ];
    }
}
