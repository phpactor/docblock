<?php

namespace Phpactor\Docblock\Tests\Tag;

use PHPUnit\Framework\TestCase;
use Phpactor\Docblock\Tag\MethodTag;
use Phpactor\Docblock\DocblockException;

class MethodTagTest extends TestCase
{
    public function testExceptionNoType()
    {
        $this->expectException(DocblockException::class);
        $this->expectExceptionMessage('no type');
        new MethodTag([]);
    }

    public function testExceptionNoName()
    {
        $this->expectException(DocblockException::class);
        $this->expectExceptionMessage('no name');
        new MethodTag([ 'Type' ]);
    }

    public function testGetSet()
    {
        $tag = new MethodTag([ 'Foobar', 'foobar' ]);
        $this->assertEquals('foobar', $tag->methodName());
        $this->assertEquals('Foobar', $tag->type());
    }

    public function testStripParenthesis()
    {
        $tag = new MethodTag([ 'Foobar', 'foobar()' ]);
        $this->assertEquals('foobar', $tag->methodName());
    }
}
