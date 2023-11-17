
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status](https://travis-ci.org/czim/laravel-json-context-logging.svg?branch=master)](https://travis-ci.org/czim/laravel-json-context-logging)
[![Coverage Status](https://coveralls.io/repos/github/czim/laravel-json-context-logging/badge.svg?branch=master)](https://coveralls.io/github/czim/laravel-json-context-logging?branch=master)


# JSON Context Logging for Laravel

Wrapper package to make use of [czim/monolog-json-context](https://github.com/czim/monolog-json-context) in Laravel applications.

The aim of this package is to easily set up (separate) logging to be easily grokkable by Logstash.


## Version Compatibility

| Laravel       | Package |
|:--------------|:--------|
| 5.8 and below | 0.9     |
| 6.0 and up    | 1.0     |
| 9.0           | 2.0     |
| 10.0          | 3.0     |

## Changelog

[View the changelog](CHANGELOG.md).


## Installation

Via Composer:

``` bash
$ composer require czim/laravel-json-context-logging
```

Publish the configuration file:

``` bash
php artisan vendor:publish --provider="Czim\LaravelJsonContextLogging\Providers\JsonContextLoggingServiceProvider"
```



## Credits

- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/czim/laravel-json-context-logging.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/czim/laravel-json-context-logging.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/czim/laravel-json-context-logging
[link-downloads]: https://packagist.org/packages/czim/laravel-json-context-logging
[link-author]: https://github.com/czim
[link-contributors]: ../../contributors
