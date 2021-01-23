<?php

namespace Phpactor\Docblock\Printer;

use Phpactor\Docblock\Ast\DeprecatedNode;
use Phpactor\Docblock\Ast\Docblock;
use Phpactor\Docblock\Ast\Element;
use Phpactor\Docblock\Ast\MethodNode;
use Phpactor\Docblock\Ast\TextNode;
use Phpactor\Docblock\Ast\TypeList;
use Phpactor\Docblock\Ast\TypeNode;
use Phpactor\Docblock\Ast\Type\ClassNode;
use Phpactor\Docblock\Ast\Node;
use Phpactor\Docblock\Ast\ParamNode;
use Phpactor\Docblock\Ast\Type\GenericNode;
use Phpactor\Docblock\Ast\Type\ListNode;
use Phpactor\Docblock\Ast\UnknownTag;
use Phpactor\Docblock\Ast\VarNode;
use Phpactor\Docblock\Ast\VariableNode;
use Phpactor\Docblock\Printer;
use Phpactor\Docblock\Token;
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

        return implode("", $this->out);
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

        if ($node instanceof ParamNode) {
            $this->renderParam($node);
            return;
        }

        if ($node instanceof VarNode) {
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

        if ($node instanceof DeprecatedNode) {
            $this->renderDeprecated($node);
            return;
        }

        if ($node instanceof MethodNode) {
            $this->renderMethod($node);
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

    private function renderParam(ParamNode $node): void
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

    private function renderVar(VarNode $node): void
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

    private function renderTypeList(TypeList $typeList): void
    {
        foreach ($typeList as $i => $param) {
            $this->render($param);
            if ($i + 1 !== $typeList->count()) {
                $this->out[] = ',';
            }
        }
    }

    private function renderTextNode(TextNode $node): void
    {
        $this->out[] = $node->shortName() . '(';
        $this->out[] = $node->toString();
        $this->out[] = ')';
    }

    private function renderDeprecated(DeprecatedNode $node): void
    {
        $this->out[] = $node->shortName() . '(';
        if ($node->text()) {
            $this->render($node->text());
        }
        $this->out[] = ')';
    }

    private function renderMethod(MethodNode $node): void
    {
        $this->out[] = $node->shortName() . '(';
        $this->render($node->type());
        if ($node->name()) {
            $this->out[] = ',';
            $this->render($node->name());
        }
        $this->out[] = ')';
    }
}
