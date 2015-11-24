<?php namespace amekusa\plz;

abstract class str {

	static function eq($X, $Y, $CaseInsensitive = false) {
		if ($CaseInsensitive) return strcasecmp($X, $Y) === 0;
		return $X === $Y;
	}

	static function is_visible($X) {
		return !empty(trim($X));
	}

	static function is_mb($X) {
		return mb_strlen($X, mb_internal_encoding()) < strlen($X);
	}

	/**
	 * Returns whether X contains Y
	 * @param string $Y A needle
	 * @param string $X A haystack
	 * @param boolean $CaseInsensitive
	 * @return boolean
	 */
	static function contains($Y, $X, $CaseInsensitive = false) {
		return $CaseInsensitive ? (stripos($X, $Y) !== false) : (strpos($X, $Y) !== false);
	}

	static function trim($X) {
		static $mask = "[\\x0-\x20\x7f\xc2\xa0\xe3\x80\x80]";
		return preg_replace("/\A{$mask}++|{$mask}++\z/u", '', $X);
	}

	static function repeat($X, $Times = 2, $Insert = '') {
		if ($Times == 1) return $X;
		$r = '';
		for ($i = 1; $i < $Times; $i++) $r .= ($X . $Insert);
		return $r . $X;
	}
}
