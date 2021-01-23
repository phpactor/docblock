<?php

namespace Phpactor\Docblock\Tests\Benchmark;

use Phpactor\Docblock\Lexer;
use Phpactor\Docblock\Parser;

/**
 * @Iterations(33)
 * @Revs(50)
 */
class ParserBench
{
    public function benchParse(): void
    {
        $doc = <<<'EOT'
/**
 * @param Foobar $foobar
 * @var Foobar $bafoo
 * @param string $baz
 */
EOT;
        $parser = new Parser();
        $parser->parse((new Lexer())->lex($doc));
    }
}
