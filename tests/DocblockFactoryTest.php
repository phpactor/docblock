<?php

namespace Phpactor\Docblock\Tests;

use PHPUnit\Framework\TestCase;
use Phpactor\Docblock\Docblock;
use Phpactor\Docblock\DocblockFactory;
use Phpactor\Docblock\DocblockTypes;
use Phpactor\Docblock\InheritTag;
use Phpactor\Docblock\Tag\DeprecatedTag;
use Phpactor\Docblock\Tag\LinkTag;
use Phpactor\Docblock\Tag\MethodTag;
use Phpactor\Docblock\Tag\MixinTag;
use Phpactor\Docblock\Tag\ParamTag;
use Phpactor\Docblock\Tag\PropertyTag;
use Phpactor\Docblock\Tag\ReturnTag;
use Phpactor\Docblock\Tag\VarTag;

class DocblockFactoryTest extends TestCase
{
    /**
     * @dataProvider provideCreate
     */
    public function testCreate($docblock, Docblock $expected): void
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
            'property' => [
                '/** @property string $foo */',
                Docblock::fromTags([ new PropertyTag(DocblockTypes::fromStringTypes(['string']), 'foo') ]),
            ],

            'property no type' => [
                '/** @property Foo */',
                Docblock::fromTags([ new PropertyTag(DocblockTypes::fromStringTypes(['Foo']), '') ]),
            ],

            'property with nothing' => [
                '/** @property */',
                Docblock::fromTags([ new PropertyTag(DocblockTypes::empty(), '') ]),
            ],

            'return single type' => [
                '/** @return Foobar foobar() */',
                Docblock::fromTags([ new ReturnTag(DocblockTypes::fromStringTypes([ 'Foobar' ])) ]),
            ],
            'return single nullable type' => [
                '/** @return ?Foobar foobar() */',
                Docblock::fromTags([ new ReturnTag(DocblockTypes::fromStringTypes([ 'Foobar' ])) ]),
            ],
            'inheritdoc' => [
                '/** {@inheritDoc} */',
                Docblock::fromTags([ new InheritTag() ]),
            ],

            'var no type' => [
                '/** @var  */',
                Docblock::fromTags([ new VarTag(DocblockTypes::empty()) ]),
            ],
            'param no type' => [
                '/** @param */',
                Docblock::fromTags([ new ParamTag(DocblockTypes::empty(), '') ]),
            ],
            'method no type' => [
                '/** @method  */',
                Docblock::fromTags([ new MethodTag(DocblockTypes::empty(), '') ]),
            ],
            'return no type' => [
                '/** @return  */',
                Docblock::fromTags([ new ReturnTag(DocblockTypes::empty()) ]),
            ],
            'deprecated' => [
                '/** @deprecated This is deprecated */',
                Docblock::fromTags([ new DeprecatedTag('This is deprecated') ]),
            ],
            'mixin' => [
                '/** @mixin Foobar\\Barfoo */',
                Docblock::fromTags([ new MixinTag('Foobar\\Barfoo') ]),
            ],
            'link without label' => [
                '/** @link https://example.org */',
                Docblock::fromTags([ new LinkTag('https://example.org', null) ])
            ],
            'link with label' => [
                '/** @link https://example.org Example */',
                Docblock::fromTags([ new LinkTag('https://example.org', 'Example') ])
            ]
        ];
    }
}
