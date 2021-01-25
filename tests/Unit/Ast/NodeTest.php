<?php

namespace Phpactor\Docblock\Tests\Unit\Ast;

use Generator;
use PHPUnit\Framework\TestCase;
use Phpactor\Docblock\Ast\MethodNode;
use Phpactor\Docblock\Ast\Node;
use Phpactor\Docblock\Ast\ParamNode;

class NodeTest extends NodeTestCase
{
    /**
     * @return Generator<mixed>
     */
    public function provideNode(): Generator
    {
        yield [
            '@param Baz\Bar $foobar',
            function (ParamNode $methodNode) {
            }
        ];
    }
}
