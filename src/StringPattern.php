<?php

namespace nickola;

class StringPattern {

	protected $string;
	protected $handlers = array();
	protected $otherwise = false;

	public function __construct($string) {

		$this->string = $string;

	}

	public function match($pattern, \Closure $callback) {

		$this->handlers[] = array($pattern, $callback);

		return $this;

	}

	public function otherwise(\Closure $callback) {

		$this->otherwise = $callback;

		return $this->run();

	}

	public function run() {

		foreach ($this->handlers as $handler) {

			if ($this->checkHandler($handler[0], $handler[1])) {
				return;
			}

		}

		if ($this->otherwise) {

			$otherwise = $this->otherwise;
			$otherwise($this->string);
			return;

		}

		throw new \Exception('No match for ' . $this->string);

	}

	protected function checkHandler($pattern, \Closure $callback) {

		$match = $pattern;

		preg_match_all('/\{[^\}]*\}/', $match, $patterns);

		$patterns = $patterns[0];
		$regex = '';
		foreach ($patterns as $variable) {
			//$regex=str_replace($variable[0], '(.+)', $regex);
			$parts = explode($variable, $match);
			$prefix = $parts[0];
			if (!empty($prefix)) {
				$regex .= '(?:' . preg_quote($prefix) . ')';
			}
			$regex .= '(.+?)';

			$match = $parts[1];

		}
		if (!empty($match)) {
			$regex .= '(?:' . preg_quote($match) . ')';
		}

		$regex = '/^' . $regex . '$/';

		preg_match($regex, $this->string, $arguments);

		array_shift($arguments);

		// print_r($patterns);
		// print_r($arguments);

		if (count($patterns) === count($arguments)) {

			call_user_func_array($callback, $arguments);

			return true;

		}

	}

}