Docblock Parser
===============

**DEPRECATED**: See https://github.com/phpactor/docblock-parser for a better solution.

Sub-standard docblock parser.

```php
$docblock = (new DocblockFactory())->create('/** @var Foobar */');
$vars = $docblock->tags()->byName('var');

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
library. This library only cares about parsing docblocks badly for
[Phpactor](https://github.com/phpactor/phpactor).

Contributing
------------

This package is open source and welcomes contributions! Feel free to open a
pull request on this repository.

Support
-------

- Create an issue on the main [Phpactor](https://github.com/phpactor/phpactor) repository.
- Join the `#phpactor` channel on the Slack [Symfony Devs](https://symfony.com/slack-invite) channel.

