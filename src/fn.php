<?php namespace amekusa\plz;

/**
 * A collection of utilities for Functions.
 *
 * To get started, place the following line around top of your code.
 * ```php
 * use amekusa\plz\fn;
 * ```
 */
abstract class fn {

	/**
	 * Calls a function
	 * @todo More specific documentation
	 * @param callable $X A function to call
	 * @param array $Args Arguments to pass to `$X`
	 * @param mixed $Alt *(optional)* A fail-safe value
	 * @return mixed A value `$X` returns or `$Alt`
	 */
	static function call($X, $Args, $Alt = null) {
		if (!is_callable($X)) {
			if (is_callable($Alt)) return call_user_func($Alt, $Args);
			return $Alt;
		}
		return call_user_func_array($X, $Args);
	}
}
