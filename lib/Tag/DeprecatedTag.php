<?php

namespace Phpactor\Docblock\Tag;

use Phpactor\Docblock\Tag;

class DeprecatedTag implements Tag
{
    /**
     * @var string|null
     */
    private $message;

    public function __construct(?string $message = null)
    {
        $this->message = $message;
    }

    public function name()
    {
        return 'deprecated';
    }

    public function message(): ?string
    {
        return $this->message;
    }
}
