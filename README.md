# php-pattern-matcher
A very simple generic pattern matcher for strings


Simple usage
```php

include_once __DIR__ . '/vendor/autoload.php';

(new nickola\StringPattern('127.0.0.1 - - [23/Nov/2016:10:08:40 -0800] "GET /crossdomain.xml HTTP/1.1" 404 213'))

	->match('{ip} - - [{date}] "{method}" {status} {bytes}', function ($ip, $date, $method, $status, $bytes) {

		//$this->assertEquals('127.0.0.1', $ip);
		//$this->assertEquals('23/Nov/2016:10:08:40 -0800', $date);
		//$this->assertEquals('GET /crossdomain.xml HTTP/1.1', $method);
		//$this->assertEquals('404', $status);
		//$this->assertEquals('213', $bytes);

	})
	->run(); //process template string


```

An exception would be thrown (above) if the input string could not be mapped to the template. 


Multiple templates, and handle unmatched 
```php


include_once __DIR__ . '/vendor/autoload.php';

foreach([
	'/',
	'/home',
	'/item/5'
] as $path){

	(new nickola\StringPattern($path))

		->match('/', function () {

			// if template has no variables then an exact match is required

		})
		->match('/item/{pageNumber}', function ($pageNumber) {

			// the first template that matches, ends the process - notice that the next section 
			// would also work for `/item/5`, so ordering is important...
			// $this->assertEquals('5', $pageNumber);

		})
		->match('/{page}', function ($page) {

			//$this->assertEquals('home', $page);

		})
		->otherwise(function(){

			// if no match is found this method is called 
			// the run() method is automatically called
			//$this->fail('Expected a match');

		});
}

```