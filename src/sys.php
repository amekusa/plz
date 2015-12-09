<?php namespace amekusa\plz;

/**
 * A collection of utilities for System.
 *
 * To get started, place the following line around top of your code.
 * ```php
 * use amekusa\plz\sys;
 * ```
 */
abstract class sys {

	/**
	 * Ensures whether a PHP directive has a specific value
	 * @param string $X The name of a directive
	 * @param mixed $Value The value that the directive should have
	 * @return boolean `true` if the directive has a correct value. Otherwise `false`
	 */
	static function ensure_ini($X, $Value) {
		if ($ini = ini_get($X) === false) throw new \RuntimeException("No such directive: {$X}");
		if ($ini === $Value) return true;
		if (ini_set($X, $Value) === false)
			throw new \RuntimeException("The value of the directive: {$X} must be " . print_r($Value));

		return true;
	}

	/**
	 * @todo Write doc
	 */
	static function ignore_errors() {
		error_reporting(0);
	}

	/**
	 * @ignore
	 * @todo Implement: Set error reporting level
	 */
	static function ignore_warnings() {
	}
}
