<?php

namespace Phpactor\Docblock\Tag;

use Phpactor\Docblock\Tag;

class MixinTag implements Tag
{
    private string $fqn;

    public function __construct(string $fqn)
    {
        $this->fqn = $fqn;
    }

    public function fqn(): string
    {
        return $this->fqn;
    }

    public function name(): string
    {
        return 'mixin';
    }
}

