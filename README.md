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
Additionally, you can define settings by passing an associative array as constructor argument. Available settings are:

* `path` (string). The relative or absolute filesystem path to a writable directory.
* `name_format` (string). The log file name format; parsed with `date()`.
* `extension` (string). The file extention to append to the filename`.
* `message_format` (string). The log message format; available tokens are:
  * `%label%`: replaced with the log message level (e.g. FATAL, ERROR, WARN).
  * `%date%`: replaced with a ISO8601 date string for current timezone.
  * `%message%`: replaced with the log message, coerced to a string.

The default settings are described in example below:

```php
<?php
require 'vendor/autoload.php';

$app = new \Slim\Slim(array(
    'log.writer' => new \Slim\Logger\DateTimeFileWriter(array(
        'path' => './logs',
        'name_format' => 'Y-m-d',
        'extension' => 'log',
        'message_format' => '%label% - %date% - %message%'
    ))
));
```

## Author

[Josh Lockhart](https://github.com/codeguy)

### Contributor

[Andrew Smith](https://github.com/silentworks)

## License

MIT Public License
