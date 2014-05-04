[![Build Status](https://travis-ci.org/nstory/phpserve.svg?branch=master)](https://travis-ci.org/nstory/phpserve)
## Summary
This is a PHP library for working with the [web server built-in to the PHP
interpreter](http://www.php.net/manual/en/features.commandline.webserver.php).

## Example
```php
$serve = new Nstory\Serve\Serve;

// options below are all defaults; setting them explicitly for the example
$serve->setHost('127.0.0.1');
$serve->setPort('8000');
$serve->setRootDirectory('.');

// creates a separate PHP process running the built-in web server
$serve->start();

// will print this file (assuming we are serving from this directory!)
echo file_get_contents('http://localhost:8000/README.md');

// not strictly necessary (a shutdown hook is registered)
$serve->shutdown();
```

## Installation
_to be written_

## Features
* Forks the web-server as a separate process
* Automatically handles clean-up

## Why?
Ideas for how this could be used:
* integration/functional testing -- combine this with SQLite as a database, and BOOM! your app in a test environment -- no external dependencies
* for serving a site locally during development (similar to `jekyll serve`)

## To be done
* Make this work under Windows
* Find a better way to tell when the server has started up

## License
MIT
