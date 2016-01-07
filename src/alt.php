<?php namespace amekusa\plz; main::required;

/**
 * Fail-safe utilities
 *
 * To get started, place the following line around top of your code.
 * ```php
 * use amekusa\plz\alt;
 * ```
 */
abstract class alt {

	/**
	 * If `$X` is `null`, returns `$Alt`. Otherwise returns `$X`
	 * @example Demonstration
	 * ```php
	 * $var1 = 'Not Null';
	 * $var2 = null;
	 * var_dump( alt::null($var1, 'Null') );
	 * var_dump( alt::null($var2, 'Null') );
	 * ```
	 * @param mixed $X A variable to check
	 * @param mixed $Alt A fail-safe value
	 * @return mixed `$X` or `$Alt`
	 */
	static function null($X, $Alt) {
		return is_null($X) ? $Alt : $X;
	}

	/**
	 * If `$X` is *falsy*, returns `$Alt`. Otherwise returns `$X`
	 * @example Demonstration
	 * ```php
	 * $var1 = 'Truthy';
	 * $var2 = '';
	 * var_dump( alt::false($var1, 'Falsy') );
	 * var_dump( alt::false($var2, 'Falsy') );
	 * ```
	 * @param mixed $X A variable to check
	 * @param mixed $Alt A fail-safe value
	 * @return mixed `$X` or `$Alt`
	 */
	static function false($X, $Alt) {
		return $X ? $X : $Alt;
	}
}
