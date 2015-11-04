<?php namespace amekusa\plz;

//const " \t\n\r\0\x0B";

trait fnString {
}

function str_is_visible($xStr) {
	//return !empty($xStr) && !ctype_space($xStr);
	return !empty(trim($xStr));
}

function trim($xStr) {
	static $mask = "[\\x0-\x20\x7f\xc2\xa0\xe3\x80\x80]";
	return preg_replace("/\A{$mask}++|{$mask}++\z/u", '', $xStr);
}
