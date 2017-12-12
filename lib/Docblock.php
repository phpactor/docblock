<?php

namespace Phpactor\Docblock;

class Docblock
{
    /**
     * @var Tags
     */
    private $tags;

    /**
     * @var string
     */
    private $prose;

    public function __construct(Tags $tags, string $prose = '')
    {
        $this->tags = $tags;
        $this->prose = $prose;
    }

    public static function fromTags(array $tags)
    {
        return new self(Tags::fromArray($tags));
    }

    public static function fromProseAndTags(string $prose, array $tags)
    {
        return new self(Tags::fromArray($tags), $prose);
    }

    public function tags(): Tags
    {
        return $this->tags;
    }

    public function prose(): string
    {
        return $this->prose;
    }
}
