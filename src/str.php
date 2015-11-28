<?php namespace amekusa\plz;

/**
 * A collection of utilities for Strings.
 *
 * To get started, place the following line around top of your code.
 * ```php
 * use amekusa\plz\str;
 * ```
 */
abstract class str {

	/**
	 * Returns whether X equals Y
	 * @param string $X A string to compare with `$Y`
	 * @param string $Y A string to compare with `$X`
	 * @param boolean $CaseInsensitive *(optional)* Whether or not to ignore letter case
	 * @return boolean
	 */
	static function eq($X, $Y, $CaseInsensitive = false) {
		if ($CaseInsensitive) return strcasecmp($X, $Y) === 0;
		return $X === $Y;
	}

	/**
	 * Returns whether X contains any visible character
	 * @param string $X
	 * @return boolean
	 */
	static function is_visible($X) {
		return !empty(trim($X));
	}

	/**
	 * Returns whether X contains any multibyte character
	 * @param string $X
	 * @return boolean
	 */
	static function is_mb($X) {
		return mb_strlen($X, mb_internal_encoding()) < strlen($X);
	}

	/**
	 * Returns whether X contains Y
	 * @param string $X A haystack
	 * @param string $Y A needle
	 * @param boolean $CaseInsensitive *(optional)* Whether or not to ignore letter case
	 * @return boolean
	 */
	static function contains($X, $Y, $CaseInsensitive = false) {
		return $CaseInsensitive ? (stripos($X, $Y) !== false) : (strpos($X, $Y) !== false);
	}

	/**
	 * Removes control characters in X
	 * @param string $X
	 * @return string A processed string
	 */
	static function trim($X) {
		static $mask = "[\\x0-\x20\x7f\xc2\xa0\xe3\x80\x80]";
		return preg_replace("/\A{$mask}++|{$mask}++\z/u", '', $X);
	}

	/**
	 * Replaces newline characters in X with the supplied string
	 *
	 * @example Replace every line-breaks with `<br>`
	 * ```php
	 * $var = <<<DOC
	 * If the doors of perception were cleansed
	 * every thing would appear to man as it is,
	 * Infinite
	 * DOC;
	 * echo str::replace_nl('<br>', $var);
	 * ```
	 * @param string $X A haystack
	 * @param string $With *(optional)* A replacement string
	 * @return string A processed string
	 */
	static function replace_nl($X, $With = '') {
		static $needles = array ("\r\n", "\r", "\n");
		return str_replace($needles, $With, $X);
	}

	/**
	 * Repeats X
	 * @example Basic usage
	 * ```php
	 * echo str::repeat('Knock');
	 * ```
	 * ```html
	 * KnockKnock
	 * ```
	 * @example Repeats so many times
	 * ```php
	 * echo 'YEA'.str::repeat('H', 32).'!!';
	 * ```
	 * ```html
	 * YEAHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHH!!
	 * ```
	 * @param string $X A string to repeat
	 * @param integer $Times *(optional)* How many times `$X` to appear
	 * @param string $Insert *(optional)* A string to insert after every repetition
	 */
	static function repeat($X, $Times = 2, $Insert = '') {
		if ($Times == 1) return $X;
		$r = '';
		for ($i = 1; $i < $Times; $i++) $r .= ($X . $Insert);
		return $r . $X;
	}
}
