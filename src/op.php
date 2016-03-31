<?php namespace amekusa\plz; main::required;

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
		if (is_array($X)) return arr::eq($X, $Y);
		if (is_object($X)) return obj::eq($X, $Y);
		return $X === $Y;
	}

	/**
	 * Returns whether any one of conditions supplied is *truthy*
	 *
	 * If only 1 argument is passed and it is *iterable*,
	 * checks whether any one of its elements is *truthy*.
	 *
	 * @example Demonstration
	 * ```php
	 * $var1 = 0;        // Falsy
	 * $var2 = null;     // Falsy
	 * $var3 = 'string'; // Truthy
	 * var_dump( op::any($var1, $var2)        );
	 * var_dump( op::any($var1, $var2, $var3) );
	 * ```
	 * @example Checking iterable elements
	 * ```php
	 * $var1 = array (
	 *   0,       // Falsy
	 *   null,    // Falsy
	 *   'string' // Truthy
	 * );
	 * $var2 = array (
	 *   0,       // Falsy
	 *   null,    // Falsy
	 *   false    // Falsy
	 * );
	 * var_dump( op::any($var1) );
	 * var_dump( op::any($var2) );
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
	 * checks whether all of its elements is *truthy*.
	 *
	 * @example Demonstration
	 * ```php
	 * $var1 = 1;    // Truthy
	 * $var2 = true; // Truthy
	 * $var3 = null; // Falsy
	 * var_dump( op::all($var1, $var2)        );
	 * var_dump( op::all($var1, $var2, $var3) );
	 * ```
	 * @example Checking iterable elements
	 * ```php
	 * $var1 = array (
	 *   1,       // Truthy
	 *   true,    // Truthy
	 *   null     // Falsy
	 * );
	 * $var2 = array (
	 *   1,       // Truthy
	 *   true,    // Truthy
	 *   'string' // Truthy
	 * );
	 * var_dump( op::all($var1) );
	 * var_dump( op::all($var2) );
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
