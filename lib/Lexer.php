<?php

namespace Phpactor\Docblock;

class Lexer
{
    private $position = 0;

    /**
     * @var string[]
     */
    private $patterns = [
        // param tag
        '@[\w]+',

        // param tag
        '\s',

        // brackets
        '\[', '\]', '<', '>',

        // variable name
        '\$[a-zA-Z0-9_\x80-\xff]+',

        // name
        '[^a-zA-Z0-9_\x80-\xff]+',
    ];

    /**
     * @var string[]
     */
    private $ignorePatterns = [
        '\s+'
    ];

    /**
     * @return Token[]
     */
    public function lex(string $docblock): array
    {
        $pattern = sprintf(
            '{(%s)|%s}',
            implode(')|(', $this->patterns),
            implode('|', $this->ignorePatterns)
        );
        $chunks = (array)preg_split(
            $pattern,
            $docblock,
            null,
            PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_OFFSET_CAPTURE
        );

        $tokens = [];
        foreach ($chunks as $chunk) {
            [ $value, $offset ] = $chunk;
            $tokens[] = new Token($offset, $this->resolveType($value), $value);
        }

        return $tokens;
    }

    private function resolveType($value): string
    {
        if (0 === strpos($value, '$')) {
            return Token::T_VARIABLE;
        }

        if (0 === strpos($value, '@')) {
            return Token::T_TAG;
        }

        if (trim($value) === '') {
            return Token::T_WHITESPACE;
        }

        if (trim($value) === '') {
            return Token::T_WHITESPACE;
        }

        if (ctype_alpha($value) || $value === '_') {
            return Token::T_LABEL;
        }

        if ($value === ']') {
            return Token::T_BRACKET_SQUARE_CLOSE;
        }

        if ($value === '[') {
            return Token::T_BRACKET_SQUARE_OPEN;
        }

        if ($value === '<') {
            return Token::T_BRACKET_ANGLE_OPEN;
        }

        if ($value === '>') {
            return Token::T_BRACKET_ANGLE_CLOSE;
        }

        return Token::T_UNKNOWN;
    }

}
