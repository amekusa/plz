<?php namespace amekusa\plz; main::required;

/**
 * Function utilities
 *
 * To get started, place the following line around top of your code.
 * ```php
 * use amekusa\plz\fn;
 * ```
 */
abstract class fn {

	/**
	 * Calls a function:`$X`
	 *
	 * Additionally:
	 *
	 * + If `$X` is not callable, returns `$Alt`
	 * + If `$X` is not callable, And `$Alt` is callable, calls `$Alt`
	 *
	 * @param callable $X A function to call
	 * @param mixed $Args *(optional)* Arguments to pass to `$X`. Pass an array for multiple parameters
	 * @param mixed $Alt *(optional)* A fail-safe value
	 * @return mixed A value `$X` returns or `$Alt`
	 */
	static function call($X, $Args = null, $Alt = null) {
		if (!is_callable($X)) {
			if (is_callable($Alt)) {
				return isset($Args) ?
					call_user_func_array($Alt, type::arr($Args)) :
					call_user_func($Alt);
			}
			return $Alt;
		}
		return isset($Args) ?
			call_user_func_array($X, type::arr($Args)) :
			call_user_func($X);
	}
}
