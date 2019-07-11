<?php

namespace Phpactor\Docblock\Tests\Tag;

use PHPUnit\Framework\TestCase;
use Phpactor\Docblock\Tag\VarTag;
use Phpactor\Docblock\DocblockTypes;

class VarTagTest extends TestCase
{
    public function testGetSet()
    {
        $tag = new VarTag(DocblockTypes::fromStringTypes([ 'Foobar' ]));
        $this->assertEquals(DocblockTypes::fromStringTypes(['Foobar']), $tag->types());

        $tag = new VarTag(DocblockTypes::fromStringTypes([ 'Foobar' ]), '$foobar');
        $this->assertEquals('$foobar', $tag->varName());
    }
}
