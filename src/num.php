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
	 * @param integer $X
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
	 * Returns an alphabet
	 * @param integer $X
	 * @return string
	 */
	static function abc($X) {
		static $map = null;
		if (!$map) $map = range('a', 'z');
		return $map[$X % 26];
	}
}
