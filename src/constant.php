<?php namespace amekusa\plz; main::required;

/**
 * Constant utilities
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
	 * @example Demonstration
	 * ```php
	 * define('CONST_X', 'I am CONST_X.');
	 * define('CONST_Y', 'I am CONST_Y.');
	 * var_dump( constant::get('CONST_X')                      );
	 * var_dump( constant::get('CONST_Y')                      );
	 * var_dump( constant::get('CONST_Z')                      ); // Alternates with NULL
	 * var_dump( constant::get('CONST_Z', 'No such constant!') ); // Alternates with a string
	 * ```
	 * @example Just-in-Time `define()`
	 * ```php
	 * define('CONST_X', 'I am CONST_X.');
	 * define('CONST_Y', 'I am CONST_Y.');
	 * var_dump( constant::get('CONST_X')                        );
	 * var_dump( constant::get('CONST_Y')                        );
	 * var_dump( constant::get('CONST_Z', 'I am CONST_Z.', true) );
	 * echo 'Hi, ' . CONST_X . "\n";
	 * echo 'Hi, ' . CONST_Y . "\n";
	 * echo 'Hi, ' . CONST_Z . "\n";
	 * ```
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
