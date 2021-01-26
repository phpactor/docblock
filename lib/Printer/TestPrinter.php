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

    public function print(Node $node): string
    {
        $this->out = [];

        $this->render($node);

        return implode('', $this->out);
    }

    private function render(?Element $node): void
    {
        if (null === $node) {
            $this->out[] = '#missing#';
            return;
        }

        if ($node instanceof Docblock) {
            $this->renderDocblock($node);
            return;
        }

        if ($node instanceof Token) {
            $this->out[] = $node->value;
            return;
        }

        if ($node instanceof ParamTag) {
            $this->renderParam($node);
            return;
        }

        if ($node instanceof VarTag) {
            $this->renderVar($node);
            return;
        }

        if ($node instanceof ListNode) {
            $this->renderListNode($node);
            return;
        }

        if ($node instanceof GenericNode) {
            $this->renderGenericNode($node);
            return;
        }

        if ($node instanceof NullableNode) {
            $this->renderNullable($node);
            return;
        }

        if ($node instanceof UnionNode) {
            $this->renderUnion($node);
            return;
        }

        if ($node instanceof NullNode) {
            $this->out[] = $node->shortName() . '()';
            return;
        }

        if ($node instanceof TypeNode) {
            $this->renderTypeNode($node);
            return;
        }

        if ($node instanceof TextNode) {
            $this->renderTextNode($node);
            return;
        }

        if ($node instanceof VariableNode) {
            $this->renderVariableNode($node);
            return;
        }

        if ($node instanceof UnknownTag) {
            $this->out[] = $node->shortName();
            return;
        }

        if ($node instanceof DeprecatedTag) {
            $this->renderDeprecated($node);
            return;
        }

        if ($node instanceof MethodTag) {
            $this->renderMethod($node);
            return;
        }

        if ($node instanceof PropertyTag) {
            $this->renderProperty($node);
            return;
        }

        if ($node instanceof MixinTag) {
            $this->renderMixin($node);
            return;
        }

        if ($node instanceof ReturnTag) {
            $this->renderReturn($node);
            return;
        }

        if ($node instanceof ParameterTag) {
            $this->renderParameter($node);
            return;
        }

        if ($node instanceof ValueNode) {
            $this->renderValue($node);
            return;
        }

        throw new RuntimeException(sprintf(
            'Do not know how to render "%s"',
            get_class($node)
        ));
    }

    private function renderDocblock(Docblock $node): void
    {
        foreach ($node->children() as $child) {
            $this->render($child);
        }
    }

    private function renderParam(ParamTag $node): void
    {
        $this->out[] = $node->shortName() . '(';
        $this->render($node->type());
        $this->out[] = ',';
        $this->render($node->variable());
        if ($node->text()) {
            $this->out[] = ',';
            $this->render($node->text());
        }
        $this->out[] = ')';
    }

    private function renderVar(VarTag $node): void
    {
        $this->out[] = $node->shortName() . '(';
        $this->render($node->type());
        if ($node->variable()) {
            $this->out[] = ',';
            $this->render($node->variable());
        }
        $this->out[] = ')';
    }

    private function renderTypeNode(TypeNode $node): void
    {
        $this->out[] = $node->shortName() . '(';
        $this->render($node->name());
        $this->out[] = ')';
    }

    private function renderVariableNode(VariableNode $node): void
    {
        $this->out[] = $node->shortName() . '(';
        $this->render($node->name());
        $this->out[] = ')';
    }

    private function renderListNode(ListNode $node): void
    {
        $this->out[] = $node->shortName() . '(';
        $this->render($node->type());
        $this->out[] = ')';
    }

    private function renderGenericNode(GenericNode $node): void
    {
        $this->out[] = $node->shortName() . '(';
        $this->render($node->type());
        $this->out[] = ',';
        $this->renderTypeList($node->parameters());
        $this->out[] = ')';
    }

    private function renderTypeList(TypeList $typeList, string $delimiter = ','): void
    {
        foreach ($typeList as $i => $param) {
            $this->render($param);
            if ($i + 1 !== $typeList->count()) {
                $this->out[] = $delimiter;
            }
        }
    }

    private function renderTextNode(TextNode $node): void
    {
        $this->out[] = $node->shortName() . '(';
        $this->out[] = $node->toString();
        $this->out[] = ')';
    }

    private function renderDeprecated(DeprecatedTag $node): void
    {
        $this->out[] = $node->shortName() . '(';
        if ($node->text()) {
            $this->render($node->text());
        }
        $this->out[] = ')';
    }

    private function renderMethod(MethodTag $node): void
    {
        $this->out[] = $node->shortName() . '(';
        $this->render($node->type());
        if ($node->name()) {
            $this->out[] = ',';
            $this->render($node->name());
        }
        if ($node->static()) {
            $this->out[] = ',static';
        }
        if ($node->parameters()) {
            $this->out[] = ',';
            $this->renderParameterList($node->parameters());
        }
        if ($node->text()) {
            $this->out[] = ',';
            $this->render($node->text());
        }
        $this->out[] = ')';
    }

    private function renderMixin(MixinTag $node): void
    {
        $this->out[] = $node->shortName() . '(';
        $this->render($node->class());
        $this->out[] = ')';
    }

    private function renderNullable(NullableNode $node): void
    {
        $this->out[] = $node->shortName() . '(';
        $this->render($node->type());
        $this->out[] = ')';
    }

    private function renderProperty(PropertyTag $node): void
    {
        $this->out[] = $node->shortName() . '(';
        $this->render($node->type());
        if ($node->name()) {
            $this->out[] = ',';
            $this->render($node->name());
        }
        $this->out[] = ')';
    }

    private function renderReturn(ReturnTag $node): void
    {
        $this->out[] = $node->shortName() . '(';
        $this->render($node->type());
        if ($node->text()) {
            $this->out[] = ',';
            $this->render($node->text());
        }
        $this->out[] = ')';
    }

    private function renderUnion(UnionNode $node): void
    {
        $this->out[] = $node->shortName() . '(';
        $this->renderTypeList($node->types(), '|');
        $this->out[] = ')';
    }

    private function renderParameterList(ParameterList $list): void
    {
        $this->out[] = 'ParameterList(';
        foreach ($list as $i => $parameter) {
            $this->render($parameter);
        }
        $this->out[] = ')';
    }

    private function renderParameter(ParameterTag $node): void
    {
        $this->out[] = $node->shortName() . '(';
        if ($node->name()) {
            $this->render($node->name());
        }
        if ($node->type()) {
            $this->out[] = ',';
            $this->render($node->type());
        }
        if ($node->default()) {
            $this->out[] = ',';
            $this->render($node->default());
        }
        $this->out[] = ')';
    }

    private function renderValue(ValueNode $node): void
    {
        $this->out[] = json_encode($node->value());
    }
}
