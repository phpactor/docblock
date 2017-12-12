<?php

namespace Phpactor\Docblock\Tests;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Phpactor\Docblock\Tag;
use Phpactor\Docblock\Docblock;
use Phpactor\Docblock\Tags;

class DocblockTest extends TestCase
{
    /**
     * @var ObjectProphecy|Tag
     */
    private $tag1;

    public function setUp()
    {
        $this->tag1 = $this->prophesize(Tag::class);
    }

    public function testFromTags()
    {
        $docblock = Docblock::fromTags([
            $this->tag1->reveal()
        ]);

        $this->assertEquals(Tags::fromArray([$this->tag1->reveal()]), $docblock->tags());
    }

    public function testFromProseAndTags()
    {
        $docblock = Docblock::fromProseAndTags(
            'Hello this is prose',
            [
                $this->tag1->reveal()
            ]
        );

        $this->assertEquals(Tags::fromArray([$this->tag1->reveal()]), $docblock->tags());
        $this->assertEquals('Hello this is prose', $docblock->prose());
    }
}
