<?php

namespace Phpactor\Docblock\Tests\Unit\Ast;

use Closure;
use Generator;
use PHPUnit\Framework\TestCase;
use Phpactor\Docblock\Ast\Element;
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
        $node = $this->parse($doc);
        $nodes = iterator_to_array($node->getDescendantNodes(), false);
        self::assertIsIterable($nodes);
        self::assertEquals(0, $node->start());
        self::assertEquals(strlen($doc), $node->end());

        $assertion($node);
    }

    /**
     * @dataProvider provideNode
     */
    public function testPartialParse(string $doc): void
    {
        $node = $this->parse($doc);
        $partial = [];
        foreach ($node->getChildElements() as $child) {
            $partial[] = $child->toString();
            $node = $this->parse(implode(' ', $partial));
            self::assertInstanceOf(Element::class, $node);
        }
    }

    /**
     * @dataProvider provideNode
     */
    public function testIsomorphism(string $doc): void
    {
        $one = $this->parse($doc);
        $two = $this->parse($one->toString());
        self::assertEquals($one, $two);
    }
    
    /**
     * @return Generator<mixed>
     */
    abstract public function provideNode(): Generator;

    private function parse(string $doc): Node
    {
        $node = (new Parser())->parse((new Lexer())->lex($doc));
        return $node;
    }
}
