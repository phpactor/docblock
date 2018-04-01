<?php

namespace Phpactor\Docblock\Tests;

use Phpactor\Docblock\DocblockType;
use PHPUnit\Framework\TestCase;

class DocblockTypeTest extends TestCase
{
    public function testCanBeACollection()
    {
        $type = DocblockType::collectionOf('Foobar', 'Item');
        $this->assertTrue($type->isCollection());
        $this->assertFalse($type->isArray());
        $this->assertEquals('Foobar', $type->__toString());
        $this->assertEquals('Item', $type->iteratedType());
    }
}
