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
use function SimpleMehanizm\Http\get_http_headers;

$request = new Request(
    get: $_GET,
    post: $_POST,
    request: $_REQUEST, 
    headers: get_http_headers($_SERVER),
    cookies: $_COOKIE,
    files: $_FILES,
    server: $_SERVER 
);

```

### Accessing headers

```php
use SimpleMehanizm\Http\Request;

$request = Request::fromSuperglobals();

// Check if exists
if($request->hasHeader('content-type'))
{
    $value = $request->header('content-type', 'default-value-when-not-present');
}

// get all headers
$headers = $request->headers();

// Change or inject header
$request->setHeader('accept', 'application/json');

```

### Accessing input

The `Request::input` method uses PHP's superglobal `$_REQUEST` to read the data. For POST or GET values, use `post()` or `get()` functions.

```php
use SimpleMehanizm\Http\Request;

$request = Request::fromSuperglobals();

$input_value = $request->input('key value', 'default value when not found');
```

### Accessing GET values

Uses PHP's `$_GET` to access values

```php
use SimpleMehanizm\Http\Request;

$request = Request::fromSuperglobals();

$input_value = $request->get('get_param_name');
```

### Accessing POST values

```php
use SimpleMehanizm\Http\Request;

$request = Request::fromSuperglobals();

$input_value = $request->get('get_param_name');
```

### What if HTTP body was valid JSON?

If your request contains valid JSON, the `Request` class will json_decode the body IF it finds the `Content-Type: application/json` HTTP header

The resulting JSON input will be merged to values found in `$_REQUEST`, allowing you to access them using `$request->input()` method.


### What else is there?

The `src/Protocol` contains several enums that expose defined HTTP status codes, phrase values, headers and similar. Explore them :)