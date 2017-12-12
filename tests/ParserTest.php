<?php

namespace Phpactor\Docblock\Tests;

use PHPUnit\Framework\TestCase;
use Phpactor\Docblock\Parser;

class ParserTest extends TestCase
{
    /**
     * @dataProvider provideParseTags
     */
    public function testParseTags($docblock, $expected)
    {
        $parser = new Parser();
        list($prose, $tags) = $parser->parse($docblock);
        $this->assertEquals($expected, $tags);
    }

    public function provideParseTags()
    {
        return [
            [
                '/** @var Foobar */',
                [ 'var' => [ [ 'Foobar' ] ] ],
            ],
            [
                '/** @var Foobar $foobar */',
                [ 'var' => [ [ 'Foobar', '$foobar' ] ] ],
            ],
            [
                <<<'EOT'
/** 
 * @var Foobar $foobar 
 * @var Barfoo $barfoo
 **/
EOT
                ,
                ['var' => [
                    [ 'Foobar', '$foobar' ],
                    [ 'Barfoo', '$barfoo' ]
                ]],
            ],
            [
                <<<'EOT'
/** 
 * @var Foobar $foobar Hello this is description
 **/
EOT
                ,
                ['var' => [
                    [ 'Foobar', '$foobar', 'Hello',  'this', 'is', 'description' ],
                ]],
            ],
            [
                <<<'EOT'
/** 
 * @method \Foobar\Barfoo foobar()
 **/
EOT
                ,
                ['method' => [
                    [ '\Foobar\Barfoo', 'foobar()' ],
                ]],
            ],
            [
                '/** @method \Barfoo foobar($foobar, string $foo) */',
                [ 'method' => [ [ '\Barfoo', 'foobar($foobar,', 'string', '$foo)' ] ] ],
            ],
        ];
    }
}
