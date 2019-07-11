<?php

namespace Phpactor\Docblock;

use Phpactor\Docblock\Parser\MethodParser;
use Phpactor\Docblock\Parser\TypesParser;
use Phpactor\Docblock\Tag\MethodTag;
use Phpactor\Docblock\Tag\ParamTag;
use Phpactor\Docblock\Tag\PropertyTag;
use Phpactor\Docblock\Tag\ReturnTag;
use Phpactor\Docblock\Tag\VarTag;

class DocblockFactory
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var TypesParser
     */
    private $typesParser;

    /**
     * @var MethodParser
     */
    private $methodParser;

    public function __construct(Parser $parser = null, TypesParser $typesParser = null, MethodParser $methodParser = null)
    {
        $this->parser = $parser ?: new Parser();
        $this->typesParser = $typesParser ?: new TypesParser();
        $this->methodParser = $methodParser ?: new MethodParser($this->typesParser);
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
                        break;
                    case 'param':
                        $tags[] = $this->createParamTag($metadata);
                        break;
                    case 'method':
                        $tags[] = $this->createMethodTag($metadata);
                        break;
                    case 'property':
                        $tags[] = $this->createPropertyTag($metadata);
                        break;
                    case 'return':
                        $tags[] = $this->createReturnTag($metadata);
                        break;
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

        return new VarTag($this->typesParser->parseTypes($types), $varName);
    }

    private function createParamTag(array $metadata): ParamTag
    {
        if (null === $types = array_shift($metadata)) {
            $types = '';
        }

        $varName = array_shift($metadata);

        return new ParamTag($this->typesParser->parseTypes($types), $varName);
    }

    private function createMethodTag(array $metadata): MethodTag
    {
        return $this->methodParser->parseMethod($metadata);
    }

    private function createReturnTag(array $metadata): ReturnTag
    {
        if (null === $types = array_shift($metadata)) {
            $types = '';
        }

        $methodName = array_shift($metadata);

        return new ReturnTag($this->typesParser->parseTypes($types));
    }

    private function createPropertyTag($metadata)
    {
        if (null === $types = array_shift($metadata)) {
            $types = '';
        }

        $propertyName = array_shift($metadata);

        return new PropertyTag($this->typesParser->parseTypes($types), ltrim($propertyName, '$'));
    }
}
