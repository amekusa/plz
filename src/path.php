<?php namespace amekusa\plz; main::required;

/**
 * Path utilities
 *
 * To get started, place the following line around top of your code.
 * ```php
 * use amekusa\plz\path;
 * ```
 */
abstract class path {

	/**
	 * Returns the extension of a file path
	 * @example Demonstration
	 * ```php
	 * $var = 'choosy-developers-choose.gif';
	 * var_dump( path::ext($var) );
	 * ```
	 * @param string $X A file path
	 * @return string The extension of `$X`
	 */
	static function ext($X) {
		return substr($X, strrpos($X, '.'));
	}
}
