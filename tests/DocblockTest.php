<?php

namespace Phpactor\Docblock\Tests;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Phpactor\Docblock\Tag;
use Phpactor\Docblock\Docblock;
use Phpactor\Docblock\Tags;

class DocblockTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @var ObjectProphecy|Tag
     */
    private $tag1;

    public function setUp(): void
    {
        $this->tag1 = $this->prophesize(Tag::class);
    }

    public function testFromTags(): void
    {
        $docblock = Docblock::fromTags([
            $this->tag1->reveal()
        ]);

        $this->assertEquals(Tags::fromArray([$this->tag1->reveal()]), $docblock->tags());
    }

    public function testFromProseAndTags(): void
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
