<?php

namespace Phpactor\Docblock\Tests\Unit;

use Generator;
use PHPUnit\Framework\TestCase;
use Phpactor\Docblock\Lexer;
use Phpactor\Docblock\Token;

class LexerTest extends TestCase
{
    /**
     * @dataProvider provideLex
     * @param list<array{string,string}> $expectedTokens
     */
    public function testLex(string $lex, array $expectedTokens): void
    {
        $tokens = (new Lexer())->lex($lex);

        self::assertCount(count($expectedTokens), $tokens, 'Expected number of tokens');

        foreach ($tokens as $index => $token) {
            [$type, $value] = $expectedTokens[$index];
            $expectedToken = new Token($token->byteOffset(), $type, $value);
            self::assertEquals($expectedToken, $token);
        }
    }

    /**
     * @return Generator<mixed>
     */
    public function provideLex(): Generator
    {
        yield [ '', [] ];
        yield [
            'Foobar',
            [
                [Token::T_LABEL, "Foobar"],
            ]
        ];
        yield [
            'Foobar[]',
            [
                [Token::T_LABEL, "Foobar"],
                [Token::T_BRACKET_SQUARE_OPEN, "["],
                [Token::T_BRACKET_SQUARE_CLOSE, "]"],
            ]
        ];
        yield [
            'Foobar<Barfoo>',
            [
                [Token::T_LABEL, "Foobar"],
                [Token::T_BRACKET_ANGLE_OPEN, "<"],
                [Token::T_LABEL, "Barfoo"],
                [Token::T_BRACKET_ANGLE_CLOSE, ">"],
            ]
        ];
        yield [
            'Foobar<Barfoo>',
            [
                [Token::T_LABEL, "Foobar"],
                [Token::T_BRACKET_ANGLE_OPEN, "<"],
                [Token::T_LABEL, "Barfoo"],
                [Token::T_BRACKET_ANGLE_CLOSE, ">"],
            ]
        ];
    }
}
