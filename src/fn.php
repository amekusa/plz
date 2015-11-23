<?php namespace amekusa\plz;

trait fn {
	use
		fnCollection,
		fnString,
		fnType,
		fnSystem;
}

/**
 * If a constant specified by the 1st argument exists, returns its value.
 * Otherwise returns the 2nd. Additionally if the 3rd is true, defines the constant.
 *
 * @param string $xName
 * @param mixed $xAlt
 * @param bool $xDefines
 * @return mixed
 */
function cst($xName, $xAlt = null, $xDefines = false) {
	if (defined($xName)) return \constant($xName);
	if ($xDefines) define($xName, $xAlt);
	return $xAlt;
}

/**
 * An alias of cst()
 */
function constant($xName, $xAlt = null, $xDefines = false) {
	return cst($xName, $xAlt, $xDefines);
}

function get($xName, $xFrom, $xAltValue = null) {
	if (is_object($xFrom)) {
		$x = array ($xFrom, 'get' . ucfirst($xName));
		if (is_callable($x)) return call_user_func($x);
		else $vars = get_object_vars($xFrom);
	} else if (is_arr_like($xFrom)) $vars = $xFrom;
	else return $xAltValue;

	return enter_array($vars, $xName, $xAltValue);
}

function string_is_mb($xString) {
	return mb_strlen($xString, mb_internal_encoding()) < strlen($xString);
}

function strings_are_equal($xStringX, $xStringY, $xCaseInsensitive = false) {
	if ($xCaseInsensitive) return strcasecmp($xStringX, $xStringY) === 0;
	return $xStringX === $xStringY;
}

/**
 * Checks whether $xSbjStr contains $xObjStr or not.
 *
 * @param string $xSbjStr
 * @param string $xObjStr
 * @param boolean $xCaseInsensitive
 * @return boolean
 */
function string_contains($xSbjStr, $xObjStr, $xCaseInsensitive = false) {
	return $xCaseInsensitive ? (stripos($xSbjStr, $xObjStr) !== false) : (strpos($xSbjStr, $xObjStr) !== false);
}

function repeat($xString, $xRepetition = 1) {
	$r = str($xString);
	for ($n = 0; $n < $xRepetition; $n++) $r .= $r;
	return $r;
}

function rpt($xString, $xRepetition = 1) {
	return rpt($xString, $xRepetition);
}

/**
 * Gets the extention from a file path.
 * @param string $xPath
 * @return string
 */
function extension($xPath) {
	return substr($xPath, strrpos($xPath, '.') + 1);
}

function ext($xPath) {
	return extension($xPath);
}

/**
 * @param integer $xNumber
 * @return string
 */
function ordinal($xNumber) {
	if (abs($xNumber) % 100 < 21 && abs($xNumber) % 100 > 4) $suffix = 'th';
	else {
		switch ($xNumber % 10) {
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
	return $xNumber . $suffix;
}

function buffer($xCallback = null, $xForcesPush = false) {
	static $buffers = array ();

	if (is_null($xCallback)) return array_pop($buffers);

	ob_start();
	invoke($xCallback);
	$r = ob_get_clean();
	if ($xForcesPush || !empty($r)) $buffers[] = $r;

	return $r;
}

function bf($xCallback = null, $xForcesPush = false) {
	return buffer($xCallback, $xForcesPush);
}

function call($xCallback, $xArgs = null, $xAlt = false) {
	if (!is_callable($xCallback)) return $xAlt;

}

function invoke($xCallback) {
	if (is_callable($xCallback)) return call_user_func($xCallback);
	if (is_string($xCallback)) {
		if (substr($xCallback, -1) != ';') $xCallback .= ';';
		return eval(substr($xCallback, -1) == ';' ? $xCallback : ($xCallback . ';'));
	}
}
