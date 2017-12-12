<?php

namespace Phpactor\Docblock\Tests\Tag;

use PHPUnit\Framework\TestCase;
use Phpactor\Docblock\Tag\VarTag;
use Phpactor\Docblock\DocblockException;

class VarTagTest extends TestCase
{
    public function testExceptionNoType()
    {
        $this->expectException(DocblockException::class);
        new VarTag([]);
    }

    public function testGetSet()
    {
        $tag = new VarTag([ 'Foobar' ]);
        $this->assertEquals('Foobar', $tag->type());

        $tag = new VarTag([ 'Foobar', '$foobar' ]);
        $this->assertEquals('$foobar', $tag->varName());
    }
}
