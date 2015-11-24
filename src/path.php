<?php namespace amekusa\plz;

abstract class path {

	/**
	 * Returns the extention from a file path
	 *
	 * Example:<pre>
	 * $var = 'logotype.svg';
	 * $r = path::ext($var); // $r = 'svg'
	 * </pre>
	 *
	 * @param string $X A file path
	 * @return string
	 */
	static function ext($X) {
		return substr($X, strrpos($X, '.') + 1);
	}
}
