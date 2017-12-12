<?php

namespace Phpactor\Docblock\Tests;

use PHPUnit\Framework\TestCase;
use Phpactor\Docblock\Tag;
use Phpactor\Docblock\Tags;

class TagsTest extends TestCase
{
    public function testByName()
    {
        $foobar = new class implements Tag {
            public function name()
            {
                return 'foobar';
            }
        };
        $barfoo = new class implements Tag {
            public function name()
            {
                return 'barfoo';
            }
        };

        $original = Tags::fromArray([ $foobar, $barfoo ]);
        $expected = Tags::fromArray([ $foobar ]);

        $this->assertEquals($expected, $original->byName('foobar'));
    }
}
