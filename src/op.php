<?php namespace amekusa\plz;

/**
 * Operator utilities
 *
 * To get started, place the following line around top of your code.
 * ```php
 * use amekusa\plz\op;
 * ```
 */
abstract class op {

	/**
	 * Returns whether or not `$X` equals `$Y`
	 * @param mixed $X
	 * @param mixed $Y
	 * @return boolean
	 */
	static function eq($X, $Y) {
		if (is_array($X)) {
			if ($X != $Y) return false;
			foreach ($X as $i => $iX) {
				if (!op::eq($X, $Y)) return false;
			}
			return true;
		}
		if (is_object($X)) {
			if ($X === $Y) return true; // Same instance
			if (get_class($X) != get_class($Y)) return false;

			// TODO Implement additional comparing methods

			if ($X instanceof \Traversable) {
				if ($X instanceof \ArrayAccess) {
					foreach ($X as $i => $iX) {
						if (!$Y->offsetExists($i)) return false;
						if (!op::eq($iX, $Y[$i])) return false;
					}
					return true;
				}
			}
		}
		return $X === $Y;
	}

	/**
	 * Returns whether any one of conditions supplied is *truthy*
	 *
	 * If only 1 argument is passed and it is *iterable*,
	 * checks whether any one of its elements is *truthy*
	 *
	 * @example Basic usage
	 * ```php
	 * $var1 = null;     // Falsy
	 * $var2 = 0;        // Falsy
	 * $var3 = 'string'; // Truthy
	 * if (op::any($var1, $var2, $var3)) {
	 *   echo 'true';
	 * } else {
	 *   echo 'false';
	 * }
	 * ```
	 * ```html
	 * true
	 * ```
	 * @example Checking iterable elements
	 * ```php
	 * $var = array (
	 *   null,    // Falsy
	 *   0,       // Falsy
	 *   'string' // Truthy
	 * );
	 * if (op::any($var)) {
	 *   echo 'true';
	 * } else {
	 *   echo 'false';
	 * }
	 * ```
	 * ```html
	 * true
	 * ```
	 * @param mixed[*] $Conditions
	 * @return boolean
	 */
	static function any($Conditions) {
		if (func_num_args() == 1 && type::is_iterable($Conditions)) {
			foreach ($Conditions as $iCond) {
				if (type::bool($iCond)) return true;
			}
			return false;
		}
		foreach (func_get_args() as $iArg) {
			if (type::bool($iArg)) return true;
		}
		return false;
	}

	/**
	 * Returns whether all of conditions supplied is *truthy*
	 *
	 * If only 1 argument is passed and it is *iterable*,
	 * checks whether all of its elements is *truthy*
	 *
	 * @example Basic usage
	 * ```php
	 * $var1 = null;     // Falsy
	 * $var2 = 0;        // Falsy
	 * $var3 = 'string'; // Truthy
	 * if (op::all($var1, $var2, $var3)) {
	 *   echo 'true';
	 * } else {
	 *   echo 'false';
	 * }
	 * ```
	 * ```html
	 * false
	 * ```
	 * @example Checking iterable elements
	 * ```php
	 * $var = array (
	 *   null,    // Falsy
	 *   0,       // Falsy
	 *   'string' // Truthy
	 * );
	 * if (op::all($var)) {
	 *   echo 'true';
	 * } else {
	 *   echo 'false';
	 * }
	 * ```
	 * ```html
	 * false
	 * ```
	 * @param mixed[*] $Conditions
	 * @return boolean
	 */
	static function all($Conditions) {
		if (func_num_args() == 1 && type::is_iterable($Conditions)) {
			foreach ($Conditions as $iCond) {
				if (!type::bool($iCond)) return false;
			}
			return true;
		}
		foreach (func_get_args() as $iArg) {
			if (!type::bool($iArg)) return false;
		}
		return true;
	}
}
