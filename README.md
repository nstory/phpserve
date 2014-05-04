[![Build Status](https://travis-ci.org/nstory/phpserve.svg?branch=master)](https://travis-ci.org/nstory/phpserve)
## Summary
This is a PHP library for working with the [web server built-in to the PHP
interpreter](http://www.php.net/manual/en/features.commandline.webserver.php).

## Features
* Forks the web-server as a separate process
* Automatically handles clean-up

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

## Question
Q: Why would I want to spin-up an instance of the not-suitable-for-production
built-in web-server?

A: For automated testing purposes (specifically for integration testing).

## To be done
* Make this work under Windows
* Find a better way to tell when the server has started up

## License
MIT
