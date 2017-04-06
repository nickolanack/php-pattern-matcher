# php-pattern-matcher
A really simple pattern matcher for strings


```php


(new nickola\StringPattern('127.0.0.1 - - [23/Nov/2016:10:08:40 -0800] "GET /crossdomain.xml HTTP/1.1" 404 213'))

	->match('{ip} - - [{date}] "{method}" {status} {bytes}', function ($ip, $date, $method, $status, $bytes) {

		//$this->assertEquals('127.0.0.1', $ip);
		//$this->assertEquals('23/Nov/2016:10:08:40 -0800', $date);
		//$this->assertEquals('GET /crossdomain.xml HTTP/1.1', $method);
		//$this->assertEquals('404', $status);
		//$this->assertEquals('213', $bytes);

	})->run();


```