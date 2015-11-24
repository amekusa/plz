<?php namespace amekusa\plz;

abstract class constant {

	/**
	 * Returns the value of the constant:X
	 *
	 * If the constant is undefined, returns the 2nd argument.
	 * Additionally if the 3rd is true, defines the constant:X with the 2nd as its value.
	 *
	 * @param string $X The name of a constant
	 * @param mixed $Alt [null] The alternative value to return if the constant is undefined
	 * @param boolean $Defines [false] Whether or not to define the constant if it is undefined
	 * @return mixed
	 */
	static function get($X, $Alt = null, $Defines = false) {
		if (defined($X)) return constant($X);
		if ($Defines) define($X, $Alt);
		return $Alt;
	}
}
