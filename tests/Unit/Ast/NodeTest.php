<?php

namespace Phpactor\Docblock\Tests\Unit\Ast;

use Generator;
use PHPUnit\Framework\TestCase;
use Phpactor\Docblock\Ast\MethodNode;
use Phpactor\Docblock\Ast\Node;
use Phpactor\Docblock\Ast\ParamNode;

class NodeTest extends NodeTestCase
{
    /**
     * @return Generator<mixed>
     */
    public function provideNode(): Generator
    {
        yield from $this->methodTag();
        yield from $this->paramTag();
        yield from $this->varTag();
        yield from $this->returnTag();
    }

    /**
     * @return Generator<mixed>
     */
    private function methodTag(): Generator
    {
        yield [
            '@method static Baz\Bar bar(string $boo, string $baz)',
            function (MethodNode $methodNode) {
                self::assertEquals('@method static Baz\Bar bar(string $boo, string $baz)', $methodNode->toString());
                self::assertEquals('string $boo, string $baz', $methodNode->parameters->toString());
                self::assertEquals('static', $methodNode->static->value);
                self::assertEquals('Baz\Bar', $methodNode->type->toString());
                self::assertEquals('bar', $methodNode->name->toString());
                self::assertEquals('(', $methodNode->parenOpen->toString());
                self::assertEquals(')', $methodNode->parenClose->toString());
            }
        ];
    }

    /**
     * @return Generator<mixed>
     */
    private function paramTag(): Generator
    {
        yield ['@param Baz\Bar $foobar'];
    }

    /**
     * @return Generator<mixed>
     */
    private function varTag(): Generator
    {
        yield ['@var Baz\Bar $foobar'];
    }

    /**
     * @return Generator<mixed>
     */
    private function returnTag(): Generator
    {
        yield ['@return Baz\Bar'];
    }
}
