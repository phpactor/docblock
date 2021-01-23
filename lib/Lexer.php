<?php

namespace Phpactor\Docblock;

class Lexer
{
    /**
     * @var string[]
     */
    private $patterns = [
        '^/\*+' => Token::T_PHPDOC_OPEN,
        '\*/' => Token::T_PHPDOC_CLOSE,
        '\*' => Token::T_PHPDOC_LEADING,
        '\[\]' => Token::T_LIST,
        '@\w+' => Token::T_TAG,
        '\s+' => Token::T_WHITESPACE,
        ',' => Token::T_COMMA,
        '\{' => Token::T_BRACKET_CURLY_OPEN, 
        '\}' => Token::T_BRACKET_CURLY_CLOSE,
        '\[' => Token::T_BRACKET_SQUARE_OPEN,
        '\]' => Token::T_BRACKET_SQUARE_CLOSE,
        '<' => Token::T_BRACKET_ANGLE_OPEN,
        '>' => Token::T_BRACKET_ANGLE_CLOSE,
        // brackets'\$[a-zA-Z0-9_\x80-\xff]+',
        '\$[a-zA-Z0-9_\x80-\xff]+' => Token::T_VARIABLE,
        '[A-Za-zA-Z0-9_\x80-\xff]+' => Token::T_LABEL,
        // label
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
            implode(')|(', array_map(function (string $pattern, string $name) {
                return sprintf('?P<%s>%s', $name, $pattern);
            }, array_keys($this->patterns), array_values($this->patterns))),
            implode('|', $this->ignorePatterns)
        );

        preg_match_all($pattern, $docblock, $matches, PREG_SET_ORDER|PREG_OFFSET_CAPTURE|PREG_UNMATCHED_AS_NULL);
        $tokens = [];
        foreach ($matches as $groups) {
            foreach ($groups as $name => $group) {
                if (is_string($name) && $group[1] >= 0) {
                    $tokens[] = new Token($group[1], $name, $group[0]);
                }
            }
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
