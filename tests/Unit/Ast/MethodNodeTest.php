<?php

namespace Phpactor\Docblock\Tests\Unit\Ast;

use Generator;
use PHPUnit\Framework\TestCase;
use Phpactor\Docblock\Ast\MethodNode;

class MethodNodeTest extends NodeTestCase
{
    /**
     * @return Generator<mixed>
     */
    public function provideNode(): Generator
    {
        yield [
            '@method static Baz\Bar bar(string $boo, string $baz)',
            function (MethodNode $methodNode) {
                self::assertEquals('static', $methodNode->static->value);
                self::assertEquals('Baz\Bar', $methodNode->type->toString());
                self::assertEquals('bar', $methodNode->name->toString());
                self::assertEquals('(', $methodNode->parenOpen->toString());
                self::assertEquals('string $boo, string $baz', $methodNode->parameters->toString());
                self::assertEquals(')', $methodNode->parenClose->toString());
                self::assertEquals('@method static Baz\Bar bar ( string $boo, string $baz )', $methodNode->toString());
            }
        ];
    }
}
