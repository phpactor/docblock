<?php

namespace Phpactor\Docblock\Tests\Unit\Ast;

use Closure;
use Generator;
use PHPUnit\Framework\TestCase;
use Phpactor\Docblock\Ast\ElementList;
use Phpactor\Docblock\Ast\Node;
use Phpactor\Docblock\Lexer;
use Phpactor\Docblock\Parser;

abstract class NodeTestCase extends TestCase
{
    /**
     * @dataProvider provideNode
     */
    public function testNode(string $doc, ?Closure $assertion = null): void
    {
        $node = (new Parser())->parse((new Lexer())->lex($doc));
        $nodes = iterator_to_array($node->getDescendantNodes(), false);
        self::assertIsIterable($nodes);
    }

    /**
     * @return Generator<mixed>
     */
    abstract public function provideNode(): Generator;
}
