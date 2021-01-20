<?php

namespace Phpactor\Docblock\Tests\Unit;

use Generator;
use PHPUnit\Framework\TestCase;
use Phpactor\Docblock\Ast\Docblock;
use Phpactor\Docblock\Ast\NameNode;
use Phpactor\Docblock\Ast\Node;
use Phpactor\Docblock\Ast\ParamNode;
use Phpactor\Docblock\Ast\VariableNode;
use Phpactor\Docblock\Lexer;
use Phpactor\Docblock\Parser;
use Phpactor\Docblock\Token;

class ParserTest extends TestCase
{
    /**
     * @dataProvider provideParse
     */
    public function testParse(string $text, Node $expected): void
    {
        $node = (new Parser())->parse((new Lexer())->lex($text));
        dump($node);
        self::assertEquals($expected, $node);
    }

    /**
     * @return Generator<mixed>
     */
    public function provideParse(): Generator
    {
        yield [
            '/** Hello */',
            new Docblock([
                new Token(0, Token::T_PHPDOC_OPEN, '/** '),
                new Token(4, Token::T_LABEL, 'Hello'),
                new Token(9, Token::T_WHITESPACE, ' '),
                new Token(10, Token::T_PHPDOC_CLOSE, '*/'),
            ])
        ];

        yield [
            '/** @param Foobar $foobar */',
            new Docblock([
                new Token(0, Token::T_PHPDOC_OPEN, '/** '),
                new ParamNode(
                    new NameNode(new Token(11, Token::T_LABEL, 'Foobar')),
                    new VariableNode(new Token(18, Token::T_VARIABLE, '$foobar'))
                ),
                new Token(25, Token::T_WHITESPACE, ' '),
                new Token(26, Token::T_PHPDOC_CLOSE, '*/'),
            ])
        ];
    }
}
