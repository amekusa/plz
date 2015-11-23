<?php namespace amekusa\plz;

/**
 * A container of global functions prefixed with <code>alt::</code>
 * To use them, a following line is required on top of your code:
 * <pre>
 * use amekusa\plz\alt;
 * </pre>
 */
abstract class alt {

	/**
	 * If X is null, returns the 2nd argument. Otherwise returns X
	 *
	 * Example:<pre>
	 * $var1 = 'Not Null';
	 * $var2 = null;
	 * $r1 = alt::null($var1, 'Null'); // $r1 = 'Not Null'
	 * $r2 = alt::null($var2, 'Null'); // $r2 = 'Null'
	 * </pre>
	 *
	 * @param mixed $X
	 * @param mixed $Alt
	 * @return mixed $X or $Alt
	 */
	static function null($X, $Alt) {
		return is_null($X) ? $Alt : $X;
	}

	/**
	 * If X is falsy, returns the 2nd argument. Otherwise returns X
	 *
	 * Example:<pre>
	 * $var1 = 'Truthy';
	 * $var2 = '';
	 * $r1 = alt::false($var1, 'Falsy'); // $r1 = 'Truthy'
	 * $r2 = alt::false($var2, 'Falsy'); // $r2 = 'Falsy'
	 * </pre>
	 *
	 * @param mixed $X
	 * @param mixed $Alt
	 * @return mixed $X or $Alt
	 */
	static function false($X, $Alt) {
		return $X ? $X : $Alt;
	}
}