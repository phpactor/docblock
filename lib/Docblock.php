<?php

namespace Phpactor\Docblock;

class Docblock
{
    /**
     * @var Tags
     */
    private $tags;

    public function __construct(Tags $tags)
    {
        $this->tags = $tags;
    }

    public static function fromTags(array $tags)
    {
        return new self(Tags::fromArray($tags));
    }

    public function tags(): Tags
    {
        return $this->tags;
    }
}
