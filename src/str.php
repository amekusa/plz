<?php namespace amekusa\plz; main::required;

/**
 * String utilities
 *
 * To get started, place the following line around top of your code.
 * ```php
 * use amekusa\plz\str;
 * ```
 */
abstract class str {

	/**
	 * Returns whether `$X` equals `$Y`
	 * @example Demonstration
	 * ```php
	 * $var1 = 'ABC';
	 * $var2 = 'ABC';
	 * $var3 = 'Abc';
	 * var_dump( str::eq($var1, $var2)       );
	 * var_dump( str::eq($var1, $var3)       );
	 * var_dump( str::eq($var1, $var3, true) ); // Case-insensitive
	 * ```
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
	 * Returns whether `$X` contains any visible character
	 * @example Demonstration
	 * ```php
	 * $var1 = " \t \n ";   // Spaces, Tab, Linebreak
	 * $var2 = " \t \n _ "; // Spaces, Tab, Linebreak, and Underscore
	 * var_dump( str::is_visible($var1) );
	 * var_dump( str::is_visible($var2) );
	 * ```
	 * @param string $X
	 * @return boolean
	 */
	static function is_visible($X) {
		return !empty(str::trim($X));
	}

	/**
	 * Returns whether `$X` contains any multibyte character
	 * @example Demonstration
	 * ```php
	 * $var1 = "ABC 123";
	 * $var2 = "ABC 一二三";
	 * var_dump( str::is_mb($var1) );
	 * var_dump( str::is_mb($var2) );
	 * ```
	 * @param string $X
	 * @return boolean
	 */
	static function is_mb($X) {
		return mb_strlen($X, mb_internal_encoding()) < strlen($X);
	}

	/**
	 * Returns whether `$X` contains `$Y`
	 * @example Demonstration
	 * ```php
	 * $haystack = "ABCDEFGHI";
	 * $needle1  = "DEF";
	 * $needle2  = "Def";
	 * var_dump( str::contains($haystack, $needle1)       );
	 * var_dump( str::contains($haystack, $needle2)       );
	 * var_dump( str::contains($haystack, $needle2, true) ); // Case-insensitive
	 * ```
	 * @param string $X A haystack
	 * @param string $Y A needle
	 * @param boolean $CaseInsensitive *(optional)* Whether or not to ignore letter case
	 * @return boolean
	 */
	static function contains($X, $Y, $CaseInsensitive = false) {
		return $CaseInsensitive ? (stripos($X, $Y) !== false) : (strpos($X, $Y) !== false);
	}

	/**
	 * Removes control characters in `$X`
	 * @param string $X
	 * @return string A processed string
	 */
	static function trim($X) {
		static $mask = "[\\x0-\x20\x7f\xc2\xa0\xe3\x80\x80]";
		return preg_replace("/\A{$mask}++|{$mask}++\z/u", '', $X);
	}

	/**
	 * Replaces newline characters in `$X` with the supplied string
	 * @example Replacing every line-breaks with `<br>\n`
	 * ```php >> html
	 * $var = <<<TEXT
	 * If the doors of perception were cleansed
	 * every thing would appear to man as it is,
	 * Infinite.
	 * TEXT;
	 * echo str::replace_nl($var, "<br>\n");
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
	 * Repeats `$X`
	 * @example Demonstration
	 * ```php
	 * echo str::repeat('Knock');
	 * ```
	 * @example Repeating so many times
	 * ```php
	 * echo 'YEA'.str::repeat('H', 32).'!!';
	 * ```
	 * @param string $X A string to repeat
	 * @param integer $Times *(optional)* How many times `$X` to appear
	 * @param string $Insert *(optional)* A string to insert after every repetition
	 */
	static function repeat($X, $Times = 2, $Insert = '') {
		if ($Times == 1) return $X;
		$r = '';
		for ($i = 1; $i < $Times; $i++) $r .= ($X . $Insert);
		return $r.$X;
	}
}
