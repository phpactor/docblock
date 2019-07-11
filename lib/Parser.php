<?php

namespace Phpactor\Docblock;

class Parser
{
    const TAG = '{@([a-zA-Z0-9-_\\\]+)\s*?([\\<\\>\\[\\]&|,\\\(\\\)$\w\s=]+)?}';

    public function parse($docblock): array
    {
        $lines = explode(PHP_EOL, $docblock);
        $tags = [];
        $prose = [];

        foreach ($lines as $line) {
            if (0 === preg_match(self::TAG, $line, $matches)) {
                if (null !== $line = $this->extractProse($line)) {
                    $prose[] = $line;
                }
                continue;
            }

            $tagName = $matches[1];

            if (!isset($tags[$tagName])) {
                $tags[$tagName] = [];
            }

            $metadata = array_values(array_filter(explode(' ', trim($matches[2] ?? ''))));
            $tags[$tagName][] = $metadata;
        }

        return [$prose, $tags ];
    }

    private function extractProse(string $line)
    {
        $line = trim($line);

        if (empty($line)) {
            return;
        }

        if ($line == '/**') {
            return;
        }

        if ($line == '*') {
            return '';
        }

        if (substr($line, 0, 2) == '* ') {
            $line = substr($line, 2);
        }

        if (substr($line, 0, 2) == '*/') {
            return;
        }

        return $line;
    }
}
