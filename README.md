# Introduction

`simplemehanizm/http` is object-oriented wrapper around handling an HTTP request in PHP.

Purpose of the library is to be small in footprint, to enumerate status codes and reason phrases in order to help developers avoid using magic strings.

## Where and when to use this

Use case is for projects, experiments and otherwise simple coding exercises in which author(s) want to avoid using heavy libraries like Symfony's HttpFoundation in order to gain access to object-oriented interface for an HTTP request.

The intent is not to replace or to provide an alternative to existing libraries that are doing the job well.

## Installation

`composer require simplemehanizm/http`

## Usage 

### Create Request instance using PHP's superglobal variables

```php
use SimpleMehanizm\Http\Request;

$request = Request::fromSuperglobals();
```

### Create request instance by specifying input values

```php
use SimpleMehanizm\Http\Request;

$request = new Request(
    get: $_GET,
    post: $_POST,
    request: $_REQUEST, 
    headers: 
);

```

### Accessing headers

### Accessing input

