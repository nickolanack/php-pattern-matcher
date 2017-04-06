<?php

class StringMatchTest extends PHPUnit_Framework_TestCase {

	protected function setUp() {
		include_once dirname(__DIR__) . '/vendor/autoload.php';
	}

	public function testSimpleExpression() {

		(new nickola\StringPattern('some.email@some.place'))

			->match('{a}.{b}@{c}.place', function ($prefix, $name, $domain) {

				$this->assertEquals('some', $prefix);
				$this->assertEquals('email', $name);
				$this->assertEquals('some', $domain);

			})->otherwise(function () {

			$this->fail('Expected matcher to succeed');

		});

	}

	public function testMultipleExpression() {

		(new nickola\StringPattern('some.email@some.place'))

			->match('{a}.{b}@{c}.otherplace', function () {

				$this->fail('Should not have matched here becuase the pattern ends with `otherplace` while the input string ends with `some.place`');

			})
			->match('{a}.{b}@{c}.place', function () {

				$this->assertTrue(true);

			})
			->otherwise(function () {

				$this->fail('Expected matcher to succeed on previes match pattern');

			});

	}

	public function testNonMatchingExpression() {

		(new nickola\StringPattern('some.email@some.otherplace'))

			->match('{a}.{b}@{c}.place', function () {

				$this->fail('Should not have matched here becuase pattern ends with `.place` while the input string ends with `[other]place`');

			})
			->otherwise(function () {

				$this->assertTrue(true);

			});

	}

	/**
	 * @expectedException Exception
	 */
	public function testErrorExpression() {

		(new nickola\StringPattern('some.email@some.otherplace'))

			->match('{a}.{b}@{c}.place', function () {

				$this->fail('Should not have matched here becuase pattern ends with `.place` while the input string ends with `[other]place`');

			})->run();

	}

	public function testFormattedString() {

		$someFormattedString = 'One:Two Three--:?!@#<>-';
		$templateFormat = '{name}:{label}-{trailing}';

		(new nickola\StringPattern($someFormattedString))

			->match($templateFormat, function ($name, $label, $trailing) {

				$this->assertEquals('One', $name);
				$this->assertEquals('Two Three', $label);
				$this->assertEquals('-:?!@#<>-', $trailing);

			})->run();

	}

	public function testLogEntryString() {

		(new nickola\StringPattern('127.0.0.1 - - [23/Nov/2016:10:08:40 -0800] "GET /crossdomain.xml HTTP/1.1" 404 213'))

			->match('{ip} - - [{date}] "{method}" {status} {bytes}', function ($ip, $date, $method, $status, $bytes) {

				$this->assertEquals('127.0.0.1', $ip);
				$this->assertEquals('23/Nov/2016:10:08:40 -0800', $date);
				$this->assertEquals('GET /crossdomain.xml HTTP/1.1', $method);
				$this->assertEquals('404', $status);
				$this->assertEquals('213', $bytes);

			})->run();

	}


	public function testMultiEntryString() {

		foreach([
			'/',
			'/home',
			'/item/5'
		] as $path){

			(new nickola\StringPattern($path))

				->match('/', function () {


				})
				->match('/item/{pageNumber}', function ($pageNumber) {

					$this->assertEquals('5', $pageNumber);

				})
				->match('/{page}', function ($page) {

					$this->assertEquals('home', $page);

				})
				->otherwise(function(){

					$this->fail('Expected a match');

				});
		}

	}

}