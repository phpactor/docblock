<?php

namespace Phpactor\Docblock;

use Phpactor\Docblock\Tag;

class InheritTag implements Tag
{
    public function name()
    {
        return 'inheritDoc';
    }
}
