<?php

namespace Phpactor\Docblock\Tests\Unit\Ast;

use Generator;
use PHPUnit\Framework\TestCase;

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
            }
        ];
    }
}
