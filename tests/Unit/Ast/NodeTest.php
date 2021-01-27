<?php

namespace Phpactor\Docblock\Tests\Unit\Ast;

use Generator;
use Phpactor\Docblock\Ast\Tag\MethodTag;
use Phpactor\Docblock\Ast\Tag\ReturnTag;
use Phpactor\Docblock\Ast\Type\GenericNode;
use Phpactor\Docblock\Ast\Type\ListNode;
use Phpactor\Docblock\Ast\Type\UnionNode;

class NodeTest extends NodeTestCase
{
    /**
     * @return Generator<mixed>
     */
    public function provideNode(): Generator
    {
        yield from $this->provideTags();
        yield from $this->provideTypes();
    }

    /**
     * @return Generator<mixed>
     */
    private function provideTags()
    {
        yield [ '@deprecated This is deprecated'];
        yield [ '/** This is docblock @deprecated Foo */'];
        yield [ '@mixin Foo\Bar'];
        yield [ '@param string $foo This is a parameter'];
        yield [
            '@method static Baz\Bar bar(string $boo, string $baz)',
            function (MethodTag $methodNode): void {
                self::assertEquals('@method static Baz\Bar bar(string $boo, string $baz)', $methodNode->toString());
                self::assertEquals('string $boo, string $baz', $methodNode->parameters->toString());
                self::assertEquals('static', $methodNode->static->value);
                self::assertEquals('Baz\Bar', $methodNode->type->toString());
                self::assertEquals('bar', $methodNode->name->toString());
                self::assertEquals('(', $methodNode->parenOpen->toString());
                self::assertEquals(')', $methodNode->parenClose->toString());
            }
        ];
        yield ['@param Baz\Bar $foobar This is a parameter'];
        yield ['@var Baz\Bar $foobar'];
        yield ['@return Baz\Bar'];
        yield ['@property Baz\Bar $foobar'];
    }

    /**
     * @return Generator<mixed>
     */
    private function provideTypes(): Generator
    {
        yield 'scalar' => ['string'];
        yield 'union' => [
            '@return string|int|bool|float|mixed',
            function (ReturnTag $return): void {
                $type = $return->type;
                assert($type instanceof UnionNode);
                self::assertInstanceOf(UnionNode::class, $type);
                self::assertEquals('string', $type->types->types()->first()->toString());
                self::assertCount(5, $type->types->types());
            }
        ];
        yield 'list' => [
            '@return Foo[]',
            function (ReturnTag $return): void {
                self::assertInstanceOf(ListNode::class, $return->type);
            }
        ];
        yield 'generic' => [
            '@return Foo<Bar<string, int>, Baz|Bar>',
            function (ReturnTag $return): void {
                self::assertInstanceOf(GenericNode::class, $return->type);
            }
        ];
    }
}
