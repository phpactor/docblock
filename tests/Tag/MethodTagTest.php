<?php

namespace Phpactor\Docblock\Tests\Tag;

use PHPUnit\Framework\TestCase;
use Phpactor\Docblock\Tag\MethodTag;
use Phpactor\Docblock\DocblockTypes;

class MethodTagTest extends TestCase
{
    public function testGetSet(): void
    {
        $tag = new MethodTag(DocblockTypes::fromStringTypes([ 'Foobar']), 'foobar');
        $this->assertEquals('foobar', $tag->methodName());
        $this->assertEquals(DocblockTypes::fromStringTypes([ 'Foobar' ]), $tag->types());
    }
}
