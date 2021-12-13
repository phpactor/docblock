<?php

namespace Phpactor\Docblock\Tag;

use Phpactor\Docblock\Tag;

class LinkTag implements Tag
{
    /**
     * @var string
     */
    private $link;

    /**
     * @var ?string
     */
    private $label;

    public function __construct(string $link, ?string $label)
    {
        $this->link = $link;
        $this->label = $label;
    }

    public function name(): string
    {
        return 'link';
    }

    public function link(): string
    {
        return $this->link;
    }

    public function label(): ?string
    {
        return $this->label;
    }
}
