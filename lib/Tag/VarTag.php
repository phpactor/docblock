<?php

namespace Phpactor\Docblock\Tag;

use Phpactor\Docblock\Tag;
use Phpactor\Docblock\DocblockException;

class VarTag implements Tag
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $varName;

    public function __construct(array $metadata)
    {
        if (null === $type = array_shift($metadata)) {
            throw new DocblockException(
                'Var tag has no type'
            );
        }

        $varName = array_shift($metadata);

        $this->type = $type;
        $this->varName = $varName;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function varName()
    {
        return $this->varName;
    }
}
