<?php namespace amekusa\plz;

trait fn {
	use
		fnSystem,
		fnType;
}

function null_safe($xVar, $xAltValue) {
	return isset($xVar) ? $xVar : $xAltValue;
}

function empty_safe($xVar, $xAltValue) {
	return empty($xVar) ? $xAltValue : $xVar;
}

/**
 *
 * @param string $xConstant
 * @param mixed $xAltValue
 * @param bool $xDefines
 * @return mixed
 */
function undef_safe($xConstant, $xAltValue = null, $xDefines = false) {
	if (defined($xConstant)) return constant($xConstant);
	if ($xDefines) define($xConstant, $xAltValue);
	return $xAltValue;
}

/**
 * Alias of {@link undef_safe()}
 */
function undefined_safe($xConstant, $xAltValue = null, $xDefines = false) {
	return undef_safe($xConstant, $xAltValue, $xDefines);
}

function first($xArray) {
	return _first($xArray);
}

function _first(&$xArray) {
	if (empty($xArray)) return null;
	$r = reset($xArray);
	return $r;
}

function last($xArray) {
	return _last($xArray);
}

function _last(&$xArray) {
	if (empty($xArray)) return null;
	$r = end($xArray);
	reset($xArray);
	return $r;
}

/**
 * If the array:$xArray has the key:$xKey, $xArray[$xKey] is returned.
 * Otherwise $xAltValue is returned.
 *
 * @param array $xArray
 * @param integer|string $xKey
 * @param mixed $xAltValue
 * @return see the description
 */
function enter_array(&$xArray, $xKey, $xAltValue = null) {
	if (empty($xArray)) return $xAltValue;
	return array_key_exists($xKey, $xArray) ? $xArray[$xKey] : $xAltValue;
}

function empty_safe_push($xElm, &$xArray) {
	if (empty($xElm)) return;
	$xArray[] = $xElm;
}

function null_safe_push($xElm, &$xArray) {
	if (is_null($xElm)) return;
	$xArray[] = $xElm;
}

function arrays_are_equal(array $xArrayX, array $xArrayY) {
	foreach ($xArrayX as $nKey => $n) {
		foreach ($xArrayY as $mKey => $m) {
			if ($mKey !== $nKey) return false;
			if ((is_array($m) && is_array($n)) && !arrays_are_equal($m, $n)) return false;
			if ($m !== $n) return false;
		}
	}
	return true;
}

/**
 * #UNTESTED
 *
 * @param mixed $xArgs
 * @return array
 */
function flat_array($xArgs) {
	$r = array ();

	$args = (func_num_args() > 1) ? func_get_args() : (is_array($xArgs)) ? $xArgs : array ($xArgs);

	foreach ($args as $iArg) {
		if (is_array($iArg)) $r = array_merge($r, flat_array($iArg));
		else $r[] = $iArg;
	}

	return $r;
}

function array_about($xField, $xArray) {
	$r = array ();
	foreach ($xArray as $iElm) {
		$r[] = get($xField, $iElm);
	}
	return $r;
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

function string_is_nonsense($xString) {
	return !$xString || ctype_space($xString);
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
