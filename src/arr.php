<?php namespace amekusa\plz;

/**
 * A collection of utilities for Arrays.
 *
 * To get started, place the following line around top of your code.
 * ```php
 * use amekusa\plz\arr;
 * ```
 */
abstract class arr {

	/**
	 * Returns a number of elements in X
	 *
	 * If X is uncountable, 1 is returned.
	 * If X is null, 0 is returned.
	 *
	 * @param array|object $X An array, a countable object, or an iterable object
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
	 * Returns the first element of X
	 *
	 * CAUTION: Calling this in a `foreach` loop over X can cause unpredictable results
	 *
	 * @param array|object $X An array or an iterable object
	 * @return mixed
	 */
	static function first($X) {
		if (is_array($X)) return reset($X);
		foreach ($X as $iX) return $iX;
	}

	/**
	 * Returns the last element of X
	 *
	 * CAUTION: Calling this in a `foreach` loop over X can cause unpredictable results
	 *
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
	 * Returns whether or not X has the supplied key
	 * @param array|object $X An array, array-like object, or traversable object
	 * @param mixed $Key
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
	 * Returns X’s element indexed by the supplied key
	 *
	 * If the element doesn’t exist, returns the 3rd argument.
	 *
	 * @param array|object $X An array, array-like object, or traversable object
	 * @param mixed $Key The key of an element to be returned
	 * @param mixed $Alt *(optional)* An alternative value to return if `X` doesn’t have the key
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
	 * @param mixed $X Any number of parameters are accepted
	 * @return array
	 */
	static function flat($X) {
		$r = array ();
		$args = (func_num_args() > 1) ? func_get_args() : (is_array($X)) ? $X : array ($X);
		foreach ($args as $iArg) {
			if (is_array($iArg)) $r = array_merge($r, arr::flat($iArg));
			else $r[] = $iArg;
		}
		return $r;
	}
}
