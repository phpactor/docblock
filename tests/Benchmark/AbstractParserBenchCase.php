<?php

namespace Phpactor\Docblock\Tests\Benchmark;

use Generator;
use Phpactor\Docblock\Lexer;
use Phpactor\Docblock\Parser;

/**
 * @Iterations(33)
 * @Revs(50)
 * @BeforeMethods({"setUp"})
 * @OutputTimeUnit("milliseconds")
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

    /**
     * @ParamProviders({"provideCoreDocs"})
     */
    public function benchPhpCore(array $params): void
    {
        $this->parse(trim($params['doc']));
    }

    /**
     * @return Generator<mixed>
     */
    public function provideCoreDocs(): Generator
    {
        $contents = file_get_contents(__DIR__ . '/examples/php_core.example');
        foreach (explode('#!---!#', $contents) as $doc) {
            yield str_replace("\n", '', substr($doc, 0, 10)) => [
                'doc' => $doc
            ];
        }
    }

    public function benchAssert(): void
    {
        $this->parse(file_get_contents(__DIR__ . '/examples/assert.example'));
    }

    abstract public function setUp(): void;
    abstract public function parse(string $doc): void;
}
