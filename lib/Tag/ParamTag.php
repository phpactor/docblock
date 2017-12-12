<?php

namespace Phpactor\Docblock\Tag;

use Phpactor\Docblock\Tag;
use Phpactor\Docblock\Tag\VarTag;

class ParamTag extends VarTag
{
    public function name()
    {
        return 'param';
    }
}
