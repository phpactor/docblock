<?php

namespace Phpactor\Docblock;

use Phpactor\Docblock\Docblock;
use Phpactor\Docblock\Tag\VarTag;
use Phpactor\Docblock\Tag\ParamTag;
use Phpactor\Docblock\Tag\MethodTag;
use Phpactor\Docblock\Parser;
use Phpactor\Docblock\Tag\ReturnTag;
use Phpactor\Docblock\InheritTag;

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
            throw new DocblockException(
                '@var tag has no type(s)'
            );
        }

        $varName = array_shift($metadata);

        return new VarTag($this->parser->parseTypes($types), $varName);
    }

    private function createParamTag(array $metadata): ParamTag
    {
        if (null === $types = array_shift($metadata)) {
            throw new DocblockException(
                '@param tag has no type(s)'
            );
        }

        $varName = array_shift($metadata);

        return new ParamTag($this->parser->parseTypes($types), $varName);
    }

    private function createMethodTag(array $metadata): MethodTag
    {
        if (null === $types = array_shift($metadata)) {
            throw new DocblockException(
                '@method tag has no type(s)'
            );
        }

        $methodName = array_shift($metadata);

        return new MethodTag($this->parser->parseTypes($types), $this->parser->parseMethodName($methodName));
    }

    private function createReturnTag(array $metadata): ReturnTag
    {
        if (null === $types = array_shift($metadata)) {
            throw new DocblockException(
                '@return tag has no type(s)'
            );
        }

        $methodName = array_shift($metadata);

        return new ReturnTag($this->parser->parseTypes($types), $this->parser->parseMethodName($methodName));
    }
}
