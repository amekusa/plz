<?php namespace amekusa\plz;

/**
 * A collection of utilities for Numbers.
 *
 * To get started, place the following line around top of your code.
 * ```php
 * use amekusa\plz\num;
 * ```
 */
abstract class num {

	/**
	 * Returns an ordinal number
	 * @example Basic usage
	 * ```php
	 * $r1 = num::ordinal(1); // $r1 = '1st'
	 * $r2 = num::ordinal(2); // $r2 = '2nd'
	 * $r3 = num::ordinal(3); // $r3 = '3rd'
	 * $r4 = num::ordinal(4); // $r4 = '4th'
	 * ```
	 * @param integer $X A number
	 * @return string
	 */
	static function ordinal($X) {
		if (abs($X) % 100 < 21 && abs($X) % 100 > 4) $suffix = 'th';
		else {
			switch ($X % 10) {
				case 1:
					$suffix = 'st';
					break;
				case 2:
					$suffix = 'nd';
					break;
				case 3:
					$suffix = 'rd';
					break;
			}
		}
		return $X . $suffix;
	}

	/**
	 * Returns an alphabetic character indexed by `$X`
	 * @example Basic usage
	 * ```php
	 * $r1 = num::abc(0);  // $r1 = 'a'
	 * $r2 = num::abc(25); // $r2 = 'z'
	 * $r3 = num::abc(26); // $r3 = 'a'
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
