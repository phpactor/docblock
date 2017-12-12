<?php

namespace Phpactor\Docblock\Tests;

use PHPUnit\Framework\TestCase;
use Phpactor\Docblock\Parser;
use Phpactor\Docblock\Docblock;
use Phpactor\Docblock\Tag\VarTag;
use Phpactor\Docblock\DocblockFactory;
use Phpactor\Docblock\Tag\ParamTag;
use Phpactor\Docblock\Tag\MethodTag;
use Phpactor\Docblock\Tag\DocblockTypes;
use Phpactor\Docblock\Tag\ReturnTag;
use Phpactor\Docblock\InheritTag;

class DocblockFactoryTest extends TestCase
{
    /**
     * @dataProvider provideCreate
     */
    public function testCreate($docblock, Docblock $expected)
    {
        $factory = new DocblockFactory();
        $docblock = $factory->create($docblock);
        $this->assertEquals($expected->tags(), $docblock->tags());
    }

    public function provideCreate()
    {
        return [
            'no tags' => [
                '/** */',
                Docblock::fromTags([]),
            ],
            'var single type' => [
                '** @var Foobar */',
                Docblock::fromTags([ new VarTag(DocblockTypes::fromStringTypes([ 'Foobar' ])), ]),
            ],
            'var multiple types' => [
                '/** @var Foobar|string|null */',
                Docblock::fromTags([ new VarTag(DocblockTypes::fromStringTypes([ 'Foobar', 'string', 'null' ])) ]),
            ],
            'var union types' => [
                '/** @var Foobar&string */',
                Docblock::fromTags([ new VarTag(DocblockTypes::fromStringTypes([ 'Foobar', 'string' ])) ]),
            ],
            'param single type' => [
                '/** @param Foobar $foobar */',
                Docblock::fromTags([ new ParamTag(DocblockTypes::fromStringTypes([ 'Foobar' ]), '$foobar') ]),
            ],
            'method single type' => [
                '/** @method Foobar foobar() */',
                Docblock::fromTags([ new MethodTag(DocblockTypes::fromStringTypes([ 'Foobar' ]), 'foobar') ]),
            ],
            'return single type' => [
                '/** @return Foobar foobar() */',
                Docblock::fromTags([ new ReturnTag(DocblockTypes::fromStringTypes([ 'Foobar' ])) ]),
            ],
            'inheritdoc' => [
                '/** {@inheritDoc} */',
                Docblock::fromTags([ new InheritTag() ]),
            ],
        ];
    }
}
