Docblock Parser
===============

Sub-standard docblock parser.

```php
$docblock = (new DocblockFactory())->create('/** @var Foobar */');
$vars = $docblock->tags('var');

foreach ($vars as $var) {
    $var->type();
    $var->varName();
}
```

Why?
----

There is already a [standards-compliant
library](https://github.com/phpDocumentor/ReflectionDocBlock) for
PHP-Documentor, however it is coupled to the PHPDocumentor type reflection
library. This library only cares about parsing docblocks badly.
