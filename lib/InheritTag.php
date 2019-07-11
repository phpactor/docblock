<?php

namespace Phpactor\Docblock;

class InheritTag implements Tag
{
    public function name()
    {
        return 'inheritDoc';
    }
}
