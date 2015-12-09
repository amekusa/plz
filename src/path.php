<?php namespace amekusa\plz;

/**
 * A collection of utilities for Path.
 *
 * To get started, place the following line around top of your code.
 * ```php
 * use amekusa\plz\path;
 * ```
 */
abstract class path {

	/**
	 * Returns the extension of a file path
	 * @example
	 * ```php
	 * $var = 'logotype.svg';
	 * $r = path::ext($var); // $r = 'svg'
	 * ```
	 * @param string $X A file path
	 * @return string The extension of `$X`
	 */
	static function ext($X) {
		return substr($X, strrpos($X, '.') + 1);
	}
}
