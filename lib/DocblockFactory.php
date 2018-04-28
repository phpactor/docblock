<?php

namespace Phpactor\Docblock;

use Phpactor\Docblock\Docblock;
use Phpactor\Docblock\Tag\MethodTag;
use Phpactor\Docblock\Tag\ParamTag;
use Phpactor\Docblock\Tag\PropertyTag;
use Phpactor\Docblock\Tag\ReturnTag;
use Phpactor\Docblock\Tag\VarTag;
use Phpactor\Docblock\Parser;
use Phpactor\Docblock\InheritTag;
use Phpactor\Docblock\Tag\DocblockTypes;

class DocblockFactory
{
    /**
     * @var Parser
     */
    private $parser;

    public function __construct(Parser $parser = null)
    {
        $this->parser = $parser ?: new Parser();
    }

    public function create(string $docblock): Docblock
    {
        $tags = [];
        list($prose, $tagData) = $this->parser->parse($docblock);
        foreach ($tagData as $tagName => $metadatas) {
            foreach ($metadatas as $metadata) {
                switch (strtolower(trim($tagName))) {
                    case 'var':
                        $tags[] = $this->createVarTag($metadata);
                        continue;
                    case 'param':
                        $tags[] = $this->createParamTag($metadata);
                        continue;
                    case 'method':
                        $tags[] = $this->createMethodTag($metadata);
                        continue;
                    case 'property':
                        $tags[] = $this->createPropertyTag($metadata);
                        continue;
                    case 'return':
                        $tags[] = $this->createReturnTag($metadata);
                        continue;
                    case 'inheritdoc':
                        $tags[] = new InheritTag();
                }
            }
        }

        return Docblock::fromProseAndTags(implode(PHP_EOL, $prose), $tags);
    }

    private function createVarTag(array $metadata): VarTag
    {
        if (null === $types = array_shift($metadata)) {
            $types = '';
        }

        $varName = array_shift($metadata);

        return new VarTag($this->parser->parseTypes($types), $varName);
    }

    private function createParamTag(array $metadata): ParamTag
    {
        if (null === $types = array_shift($metadata)) {
            $types = '';
        }

        $varName = array_shift($metadata);

        return new ParamTag($this->parser->parseTypes($types), $varName);
    }

    private function createMethodTag(array $metadata): MethodTag
    {
        if (null === $types = array_shift($metadata)) {
            $types = '';
        }

        $methodName = array_shift($metadata);

        return new MethodTag($this->parser->parseTypes($types), $this->parser->parseMethodName($methodName));
    }

    private function createReturnTag(array $metadata): ReturnTag
    {
        if (null === $types = array_shift($metadata)) {
            $types = '';
        }

        $methodName = array_shift($metadata);

        return new ReturnTag($this->parser->parseTypes($types), $this->parser->parseMethodName($methodName));
    }

    private function createPropertyTag($metadata)
    {
        if (null === $types = array_shift($metadata)) {
            $types = '';
        }

        $propertyName = array_shift($metadata);

        return new PropertyTag($this->parser->parseTypes($types), ltrim($propertyName, '$'));
    }
}
