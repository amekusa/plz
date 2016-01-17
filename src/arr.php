<?php namespace amekusa\plz; main::required;

/**
 * Array utilities
 *
 * To get started, place the following line around top of your code.
 * ```php
 * use amekusa\plz\arr;
 * ```
 */
abstract class arr {

	/**
	 * Returns the number of elements in `$X`
	 *
	 * Additionally:
	 *
	 * + If `$X` is uncountable, `1` is returned.
	 * + If `$X` is `null`, `0` is returned.
	 *
	 * @example Demonstration
	 * ```php
	 * $var1 = array ('A', 'B');                       // Array
	 * $var2 = new ArrayObject(array ('A', 'B', 'C')); // Countable object
	 * $var3 = 'ABCD';                                 // String
	 * $var4 = null;                                   // Null
	 * var_dump( arr::count($var1) );
	 * var_dump( arr::count($var2) );
	 * var_dump( arr::count($var3) );
	 * var_dump( arr::count($var4) );
	 * ```
	 * @example Recursively counting
	 * ```php
	 * $var = array (
	 *   'A', 'B',
	 *   array ('C', 'D'),
	 *   'E', 'F'
	 * );
	 * var_dump( arr::count($var)       ); // Normal
	 * var_dump( arr::count($var, true) ); // Recursive
	 * ```
	 * @param array|object $X An array, countable object, or iterable object
	 * @param boolean $Recursive *(optional)* Whether or not to count recursively
	 * @return integer
	 */
	static function count($X, $Recursive = false) {
		if (is_object($X)) {
			if (!$X instanceof \Countable) {
				if ($X instanceof \Traversable) { // Computes
					$r = 0;
					if ($Recursive) foreach ($X as $iX) $r += (1 + arr::count($iX, true));
					else foreach ($X as $iX) $r++;
					return $r;
				}
			}
		}
		return \count($X, $Recursive ? COUNT_RECURSIVE : COUNT_NORMAL);
	}

	/**
	 * Returns the first element of `$X`
	 *
	 * **CAUTION:** Calling in a `foreach` loop over `$X` can cause unpredictable results.
	 *
	 * @example Demonstration
	 * ```php
	 * $var1 = array ('A', 'B', 'C');                  // Array
	 * $var2 = new ArrayObject(array ('A', 'B', 'C')); // Iterable object
	 * var_dump( arr::first($var1) );
	 * var_dump( arr::first($var2) );
	 * ```
	 * @param array|object $X An array or an iterable object
	 * @return mixed
	 */
	static function first($X) {
		if (is_array($X)) return reset($X);
		foreach ($X as $iX) return $iX;
	}

	/**
	 * Returns the last element of `$X`
	 *
	 * **CAUTION:** Calling this in a `foreach` loop over `$X` can cause unpredictable results.
	 *
	 * @example Demonstration
	 * ```php
	 * $var1 = array ('A', 'B', 'C');                  // Array
	 * $var2 = new ArrayObject(array ('A', 'B', 'C')); // Iterable object
	 * var_dump( arr::last($var1) );
	 * var_dump( arr::last($var2) );
	 * ```
	 * @param array|object $X An array or an iterable object
	 * @return mixed
	 */
	static function last($X) {
		if (is_array($X)) {
			$r = end($X);
			reset($X);
			return $r;
		}
		foreach ($X as $iX);
		return $iX;
	}

	/**
	 * Returns whether `$X` has the supplied key
	 * @example Demonstration
	 * ```php
	 * $var = array (
	 *   'X' => 'A',
	 *   'Y' => 'B',
	 *   'Z' => 'C'
	 * );
	 * var_dump( arr::has_key($var, 'X') );
	 * var_dump( arr::has_key($var, 'W') );
	 * ```
	 * @param array|object $X An array, array-like object, or traversable object
	 * @param mixed $Key A key to find
	 * @return boolean
	 */
	static function has_key($X, $Key) {
		if (is_array($X)) return array_key_exists($Key, $X);
		if (is_object($X)) {
			if ($X instanceof \ArrayAccess) return $X->offsetExists($Key);
			if ($X instanceof \Traversable) {
				foreach ($X as $i => $iX) {
					if ($i === $Key) return true;
				}
				return false;
			}
		}
		return false;
	}

	/**
	 * Returns `$X`’s element indexed by `$Key`
	 *
	 * If the element doesn’t exist, returns `$Alt`.
	 *
	 * @example Demonstration
	 * ```php
	 * $var = array (
	 *   'X' => 'A',
	 *   'Y' => 'B',
	 *   'Z' => 'C'
	 * );
	 * var_dump( arr::get($var, 'X')                 ); // Same as $var['X']
	 * var_dump( arr::get($var, 'W')                 ); // Alternates with NULL
	 * var_dump( arr::get($var, 'W', 'No such key!') ); // Alternates with a string
	 * ```
	 * @param array|object $X An array, array-like object, or traversable object
	 * @param mixed $Key The key of an element to be returned
	 * @param mixed $Alt *(optional)* An alternative value to return if `$X` doesn’t have the key
	 * @return mixed
	 */
	static function get($X, $Key, $Alt = null) {
		if (is_array($X)) return array_key_exists($Key, $X) ? $X[$Key] : $Alt;
		if (is_object($X)) {
			if ($X instanceof \ArrayAccess) return $X->offsetExists($Key) ? $X[$Key] : $Alt;
			if ($X instanceof \Traversable) {
				foreach ($X as $i => $iX) {
					if ($i === $Key) return $iX;
				}
				return $Alt;
			}
		}
		return $Alt;
	}

	/**
	 * Treats arguments as an one-dimensional array
	 * @example Converting a multi-dimensional array into one-dimensional
	 * ```php
	 * $var = array (
	 *   'A',
	 *   array (
	 *     'B',
	 *     array (
	 *       'C'
	 *     ),
	 *     'D'
	 *   ),
	 *   'E'
	 * );
	 * var_export( arr::flat($var) );
	 * ```
	 * @example Converting multiple arguments into an one-dimentional array
	 * ```php
	 * $r = arr::flat('A', 'B', array ('C', 'D'), 'E', 'F');
	 * var_export( $r );
	 * ```
	 * @param mixed $X Any number of parameters are accepted
	 * @return array
	 */
	static function flat($X) {
		$r = array ();
		$args = (func_num_args() > 1) ? func_get_args() : (type::is_iterable($X) ? $X : array ($X));
		foreach ($args as $iArg) {
			if (is_array($iArg)) $r = array_merge($r, arr::flat($iArg));
			else $r[] = $iArg;
		}
		return $r;
	}
}
