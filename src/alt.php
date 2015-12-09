<?php namespace amekusa\plz;

/**
 * A collection of utilities for fail-safe.
 *
 * To get started, place the following line around top of your code.
 * ```php
 * use amekusa\plz\alt;
 * ```
 */
abstract class alt {

	/**
	 * If `$X` is `null`, returns `$Alt`. Otherwise returns `$X`
	 * @example Basic usage
	 * ```php
	 * $var1 = 'Not Null';
	 * $var2 = null;
	 * $r1 = alt::null($var1, 'Null'); // $r1 = 'Not Null'
	 * $r2 = alt::null($var2, 'Null'); // $r2 = 'Null'
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
	 * @example Basic usage
	 * ```php
	 * $var1 = 'Truthy';
	 * $var2 = '';
	 * $r1 = alt::false($var1, 'Falsy'); // $r1 = 'Truthy'
	 * $r2 = alt::false($var2, 'Falsy'); // $r2 = 'Falsy'
	 * ```
	 * @param mixed $X A variable to check
	 * @param mixed $Alt A fail-safe value
	 * @return mixed `$X` or `$Alt`
	 */
	static function false($X, $Alt) {
		return $X ? $X : $Alt;
	}
}
