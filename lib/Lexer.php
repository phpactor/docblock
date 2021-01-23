<?php

namespace Phpactor\Docblock;

class Lexer
{
    /**
     * @var string[]
     */
    private $patterns = [
        '^/\*+', // start tag
        '\*/', // close tag
        '\*', // leading tag
        '\[\]', //tag
        '@\w+', //tag
        '\s+', // whitespace
        ',', // comma
        '\{', '\}', '\[', '\]', '<', '>', // brackets
        '\$[a-zA-Z0-9_\x80-\xff]+', // variable
        '[^a-zA-Z0-9_\x80-\xff]+', // label
    ];

    /**
     * @var string[]
     */
    private $ignorePatterns = [
        '\s+'
    ];

    public function lex(string $docblock): Tokens
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
        $prevChunk = [null,null];
        foreach ($chunks as $chunk) {
            [ $value, $offset ] = $chunk;
            [ $prevValue, $_ ] = $prevChunk;
            $tokens[] = new Token(
                $offset,
                $this->resolveType($value, $prevValue),
                $value
            );
            $prevChunk = $chunk;
        }

        return new Tokens($tokens);
    }

    private function resolveType(string $value, ?string $prevValue): string
    {
        if (false !== strpos($value, '/*')) {
            return Token::T_PHPDOC_OPEN;
        }

        if (false !== strpos($value, '*/')) {
            return Token::T_PHPDOC_CLOSE;
        }

        if ($prevValue && 0 === strpos($prevValue, "\n") && trim($value) === '*') {
            return Token::T_PHPDOC_LEADING;
        }

        if ($prevValue && 0 === strpos($prevValue, "\n") && trim($value) === '*') {
            return Token::T_PHPDOC_LEADING;
        }

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

        if ($value === '{') {
            return Token::T_BRACKET_CURLY_OPEN;
        }

        if ($value === '}') {
            return Token::T_BRACKET_CURLY_CLOSE;
        }

        if ($value === ',') {
            return Token::T_COMMA;
        }

        if ($value === '[]') {
            return Token::T_LIST;
        }

        return Token::T_UNKNOWN;
    }

}
