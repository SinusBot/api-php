# SinusBot API PHP Class

[![Build Status](https://travis-ci.org/SinusBot/api-php.svg?branch=master)](https://travis-ci.org/SinusBot/api-php)

> PHP Wrapper to communicate with the [SinusBot](https://sinusbot.com) API.

## Usage

### via Composer

```bash
composer require sinusbot/sinusbot-api-php
```

Require the composer's `autoload.php` in the top of your `php` file:

```php
require __DIR__ . '/vendor/autoload.php';
```

Then you can use the SinusBot API via the `SinusBot\API` class. 

### via Source

Download and extract the latest release [here](https://github.com/SinusBot/api-php/releases/latest). Rename the folder to `sinusbot`, after that require the `autoload.php`:

```php
require __DIR__ . '/sinusbot/src/autoload.php';
```

Then you can use the SinusBot API via the `SinusBot\API` class.

## Examples

There are a few examples located in the [examples](examples/) directory.

## Documentation

A complete list of methods can be found in the [documenation](https://sinusbot.github.io/api-php).

## Compatibility

It's compatible with the consumer and hosting version of the SinusBot.

## Copyright

This libary was originally created by [Manuel Hettche](https://github.com/marburger93) from [TS3index](https://ts3index.com).
