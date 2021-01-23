<?php

namespace Phpactor\Docblock\Ast;

class Node implements Element
{
    public function shortName(): string
    {
        return substr(get_class($this), strrpos(get_class($this), '\\') + 1);
    }
}
