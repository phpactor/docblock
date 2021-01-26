<?php

namespace Phpactor\Docblock\Printer;

use Phpactor\Docblock\Ast\Tag\DeprecatedTag;
use Phpactor\Docblock\Ast\Docblock;
use Phpactor\Docblock\Ast\Element;
use Phpactor\Docblock\Ast\Tag\MethodTag;
use Phpactor\Docblock\Ast\Tag\MixinTag;
use Phpactor\Docblock\Ast\ParameterList;
use Phpactor\Docblock\Ast\Tag\ParameterTag;
use Phpactor\Docblock\Ast\Tag\PropertyTag;
use Phpactor\Docblock\Ast\Tag\ReturnTag;
use Phpactor\Docblock\Ast\TextNode;
use Phpactor\Docblock\Ast\TypeList;
use Phpactor\Docblock\Ast\TypeNode;
use Phpactor\Docblock\Ast\Node;
use Phpactor\Docblock\Ast\Tag\ParamTag;
use Phpactor\Docblock\Ast\Type\GenericNode;
use Phpactor\Docblock\Ast\Type\ListNode;
use Phpactor\Docblock\Ast\Type\NullNode;
use Phpactor\Docblock\Ast\Type\NullableNode;
use Phpactor\Docblock\Ast\Type\UnionNode;
use Phpactor\Docblock\Ast\UnknownTag;
use Phpactor\Docblock\Ast\ValueNode;
use Phpactor\Docblock\Ast\Tag\VarTag;
use Phpactor\Docblock\Ast\VariableNode;
use Phpactor\Docblock\Printer;
use Phpactor\Docblock\Ast\Token;
use RuntimeException;

final class TestPrinter implements Printer
{
    /**
     * @var string[]
     */
    private $out = [];

    private $indent = 0;

    public function print(Node $node): string
    {
        $this->indent++;
        $out = sprintf('%s: = ', $node->shortName());
        foreach ($node->getChildElements() as $child) {
            $out .= $this->printElement($child);
        }
        $this->indent--;

        return $out;
    }

    /**
     * @param array|Element $element
     */
    public function printElement($element): string
    {
        if ($element instanceof Token) {
            return sprintf('%s', $element->value);
        }

        if ($element instanceof Node) {
            return $this->newLine() . $this->print($element);
        }

        return implode('', array_map(function (Element $element) {
            return $this->printElement($element);
        }, (array)$element));
    }

    private function newLine(): string
    {
        return "\n".str_repeat(' ', $this->indent);
    }

}
