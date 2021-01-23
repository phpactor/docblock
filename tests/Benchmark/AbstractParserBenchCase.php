<?php

namespace Phpactor\Docblock\Tests\Benchmark;

use Phpactor\Docblock\Lexer;
use Phpactor\Docblock\Parser;

/**
 * @Iterations(33)
 * @Revs(50)
 * @BeforeMethods({"setUp"})
 */
abstract class AbstractParserBenchCase
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
        $this->parse($doc);
    }

    abstract public function setUp(): void;
    abstract public function parse(string $doc): void;
}
