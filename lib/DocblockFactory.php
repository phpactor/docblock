<?php

namespace Phpactor\Docblock;

use Phpactor\Docblock\Parser\MethodParser;
use Phpactor\Docblock\Parser\TypesParser;
use Phpactor\Docblock\Tag\DeprecatedTag;
use Phpactor\Docblock\Tag\LinkTag;
use Phpactor\Docblock\Tag\MethodTag;
use Phpactor\Docblock\Tag\MixinTag;
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
                    case 'mixin':
                        $tags[] = $this->createMixinTag($metadata);
                        break;
                    case 'property':
                        $tags[] = $this->createPropertyTag($metadata);
                        break;
                    case 'deprecated':
                        $tags[] = $this->createDeprecatedTag($metadata);
                        break;
                    case 'return':
                        $tags[] = $this->createReturnTag($metadata);
                        break;
                    case 'link':
                        $tags[] = $this->createLinkTag($metadata);
                        break;
                    case 'inheritdoc':
                        $tags[] = new InheritTag();
                }
            }
        }

        return Docblock::fromProseAndTags(implode(PHP_EOL, $prose), array_filter(
            $tags,
            function (Tag $tag) {
                return $tag !== null;
            }
        ));
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
        $propertyName = $propertyName ?? '';

        return new PropertyTag($this->typesParser->parseTypes($types), ltrim($propertyName, '$'));
    }

    private function createDeprecatedTag(array $metadata): DeprecatedTag
    {
        return new DeprecatedTag(implode(' ', $metadata));
    }

    /**
     * @param string[] $metadata
     */
    private function createLinkTag(array $metadata): LinkTag
    {
        $link = array_shift($metadata);
        $label = $metadata[0] ?? null;

        return new LinkTag($link, $label);
    }

    private function createMixinTag(array $metadata): ?MixinTag
    {
        $fqn = array_shift($metadata);
        if (null === $fqn) {
            return null;
        }
        return new MixinTag($fqn);
    }
}
