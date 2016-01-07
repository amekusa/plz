<?php namespace amekusa\plz; main::required;

/**
 * Number utilities
 *
 * To get started, place the following line around top of your code.
 * ```php
 * use amekusa\plz\num;
 * ```
 */
abstract class num {

	/**
	 * Returns an ordinal number
	 * @example Demonstration
	 * ```php
	 * var_dump( num::ordinal(1) );  // First
	 * var_dump( num::ordinal(2) );  // Second
	 * var_dump( num::ordinal(3) );  // Third
	 * var_dump( num::ordinal(4) );  // Fourth
	 * var_dump( num::ordinal(11) ); // Eleventh
	 * var_dump( num::ordinal(20) ); // Twentieth
	 * var_dump( num::ordinal(21) ); // Twenty-first
	 * var_dump( num::ordinal(-1) ); // Negative first
	 * ```
	 * @param integer $X A number
	 * @return string
	 */
	static function ordinal($X) {
		$n = abs($X) % 100;
		if ($n < 21 && $n > 3) $suffix = 'th';
		else {
			switch ($n % 10) {
				case 1:
					$suffix = 'st';
					break;
				case 2:
					$suffix = 'nd';
					break;
				case 3:
					$suffix = 'rd';
					break;
				default:
					$suffix = 'th';
			}
		}
		return $X.$suffix;
	}

	/**
	 * Returns an alphabetic character indexed by `$X`
	 * @example Demonstration
	 * ```php
	 * var_dump( num::abc(0) );  // 'a'
	 * var_dump( num::abc(1) );  // 'b'
	 * var_dump( num::abc(2) );  // 'c'
	 * var_dump( num::abc(25) ); // 'z'
	 * var_dump( num::abc(26) ); // 'a'
	 * ```
	 * @param integer $X A number
	 * @return string
	 */
	static function abc($X) {
		static $map = null;
		if (!$map) $map = range('a', 'z');
		return $map[$X % 26];
	}
}
