<?php namespace amekusa\plz;

/**
 * A collection of utilities for Constants.
 *
 * To get started, place the following line around top of your code.
 * ```php
 * use amekusa\plz\constant;
 * ```
 */
abstract class constant {

	/**
	 * Returns the value of constant:`$X`
	 *
	 * If the constant is undefined, returns the 2nd argument.
	 * Additionally, if the 3rd is `true`, defines constant:`$X` of which value is the 2nd.
	 *
	 * @param string $X The name of a constant
	 * @param mixed $Alt *(optional)* The alternative value to return if constant:`$X` is undefined
	 * @param boolean $Defines *(optional)* Whether or not to define constant:`$X` if it is undefined
	 * @return mixed The value of constant:`$X` or `$Alt`
	 */
	static function get($X, $Alt = null, $Defines = false) {
		if (defined($X)) return constant($X);
		if ($Defines) define($X, $Alt);
		return $Alt;
	}
}
