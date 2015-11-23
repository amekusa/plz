<?php namespace amekusa\plz;

abstract class sys {

	/**
	 * Verifies a PHP directive has a specific value
	 *
	 * @param string $X The name of the directive
	 * @param mixed $Value The value of the directive
	 * @return boolean Returns true if the directive has a correct value. Otherwise false
	 */
	static function ensure_ini($X, $Value) {
		if ($x = ini_get($X) === false)
			throw new \RuntimeException("No such directive: {$X}");

			if ($x === $Value) return true;

			if (ini_set($X, $Value) === false)
				throw new \RuntimeException("The value of the directive: {$X} must be " . print_r($Value));

			return true;
	}

	static function ignore_errors() {
		error_reporting(0);
	}

	/**
	 * TODO Implement: Set error reporting level
	 */
	static function ignore_warnings() {
	}
}