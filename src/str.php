<?php namespace amekusa\plz;

abstract class str {

	static function is_visible($X) {
		return !empty(trim($X));
	}

	static function trim($X) {
		static $mask = "[\\x0-\x20\x7f\xc2\xa0\xe3\x80\x80]";
		return preg_replace("/\A{$mask}++|{$mask}++\z/u", '', $X);
	}
}