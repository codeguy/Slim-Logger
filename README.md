Slim-Logger
===========

A stand-alone logger class for use with the Slim Framework

## How to Install

#### using [Composer](http://getcomposer.org/)

Create a composer.json file in your project root:
    
```json
{
    "require": {
        "slim/logger": "0.1.*"
    }
}
```

Then run the following composer command:

```bash
$ php composer.phar install
```

### How to use
    
```php
<?php
require 'vendor/autoload.php';

$app = new \Slim\Slim(array(
    'log.writer' => new \Slim\Logger\DateTimeFileWriter()
));
```

## Author

[Josh Lockhart](https://github.com/codeguy)

### Contributor

[Andrew Smith](https://github.com/silentworks)

## License

MIT Public License