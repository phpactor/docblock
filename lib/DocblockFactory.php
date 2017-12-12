<?php

namespace Phpactor\Docblock;

use Phpactor\Docblock\Docblock;
use Phpactor\Docblock\Tag\VarTag;
use Phpactor\Docblock\Tag\ParamTag;
use Phpactor\Docblock\Tag\MethodTag;

class DocblockFactory
{
    /**
     * @var Parser
     */
    private $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    public function create(string $docblock): Docblock
    {
        $tags = [];
        foreach ($this->parser->parseTags($docblock) as $tagName => $metadatas) {
            foreach ($metadatas as $metadata) {
                switch ($tagName) {
                    case 'var':
                        $tags[] = new VarTag($metadata);
                        continue;
                    case 'param':
                        $tags[] = new ParamTag($metadata);
                        continue;
                    case 'method':
                        $tags[] = new MethodTag($metadata);
                        continue;
                }
            }
        }

        return Docblock::fromTags($tags);
    }
}
