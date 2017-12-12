<?php

namespace Phpactor\Docblock;

use Phpactor\Docblock\Docblock;
use Phpactor\Docblock\Tag\VarTag;
use Phpactor\Docblock\Tag\ParamTag;
use Phpactor\Docblock\Tag\MethodTag;
use Phpactor\Docblock\Parser;

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

        return Docblock::fromProseAndTags(implode(PHP_EOL, $prose), $tags);
    }
}
