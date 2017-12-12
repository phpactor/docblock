<?php

namespace Phpactor\Docblock;

class Parser
{
    const TAG = '{@([a-zA-Z0-9-_\\\]+)\s*?([,\\\()$\w\s]+)?}';

    public function parseTags($docblock): array
    {
        $lines = explode(PHP_EOL, $docblock);
        $tags = [];

        foreach ($lines as $line) {
            if (0 === preg_match(self::TAG, $line, $matches)) {
                continue;
            }

            $tagName = $matches[1];

            if (!isset($tags[$tagName])) {
                $tags[$tagName] = [];
            }

            $metadata = explode(' ', trim($matches[2]) ?? '');
            $tags[$tagName][] = $metadata;
        }

        return $tags;
    }
}
