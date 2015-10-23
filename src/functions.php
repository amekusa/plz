<?php namespace amekusa\plz;

interface functions {
	const required = true;
}

/**
 * Verifies a PHP directive has a specific value
 *
 * @param string $xName The name of the directive
 * @param mixed $xValue The value of the directive
 */
function ensure_ini($xName, $xValue) {
	$x = ini_get($xName);
	if ($x === false) new \Exception("No such directive:$xName");
	if ($x !== '' && $x === $xValue) return;
	if (ini_set($xName, $xValue) === false) new \Exception("The value of the directive:$xName must be $xValue");
}

/**
 * Returns the type of a value
 *
 * If the value is an object, returns the class name of the object.
 *
 * @param mixed $xValue
 * @return string The type representation
 */
function type($xValue) {
	if (is_object($xValue)) return get_class($xValue);
	if (is_bool($xValue)) return 'boolean';
	if (is_int($xValue)) return 'integer';
	if (is_float($xValue)) return 'float';
	if (is_string($xValue)) return 'string';
	if (is_array($xValue)) return 'array';
	if (is_resource($xValue)) return 'resource';
	return gettype($xValue);
}

/**
 * Returns whether the type of a value matches a specific type
 *
 * @param mixed $xValue
 * @param string $xType The matching type representation
 * @return boolean
 */
function type_matches($xValue, $xType) {
	if ($xType === 'bool' || $xType === 'boolean') return is_bool($xValue);
	if ($xType === 'int' || $xType === 'integer') return is_int($xValue);
	if ($xType === 'float') return is_float($xValue);
	if ($xType === 'string') return is_string($xValue);
	if ($xType === 'array') return is_array($xValue);
	if ($xType === 'object') return is_object($xValue);
	if ($xType === 'resource') return is_resource($xValue);

	if ($xType === 'mixed') return true;
	if ($xType === 'numeric') return is_numeric($xValue);
	if ($xType === 'callable') return is_callable($xValue);
	if ($xType === 'scalar') return is_scalar($xValue);
	if ($xType === 'vector') return !is_scalar($xValue);

	if ($xType === 'long') return is_long($xValue);
	if ($xType === 'double') return is_double($xValue);
	if ($xType === 'real') return is_real($xValue);

	if (class_exists($xType)) return $xValue instanceof $xType;

	return $xType === gettype($xValue);
}

/**
 * Returns whether a value is an array or an array-like object
 *
 * @param mixed $xValue
 * @return boolean
 */
function array_like($xValue) {
	if (is_array($xValue)) return true;
	if (is_object($xValue)) return $xValue instanceof \ArrayAccess;
	return false;
}

/**
 * Returns whether a value is iterable
 *
 * @param mixed $xValue
 * @return boolean
 */
function iterable($xValue) {
	if (is_array($xValue)) return true;
	if (is_object($xValue)) return $xValue instanceof \Traversable;
	return false;
}

/**
 * Interprets a value as boolean
 *
 * If $xValue is an object, returns $xValue->toBool()|toBoolean() if they exist
 *
 * @param mixed $xValue
 * @return boolean
 */
function bool($xValue) {
	if (is_bool($xValue)) return $xValue;
	if (is_string($xValue)) {
		if (strings_are_equal($xValue, 'false', true)) return false;

	} else if (is_object($xValue)) {
		if (method_exists($xValue, 'toBool')) return bool($xValue->toBool());
		if (method_exists($xValue, 'toBoolean')) return bool($xValue->toBoolean());
	}
	return (bool) $xValue;
}

/**
 * @see bool() Alias
 */
function boolean($xValue) {
	return bool($xValue);
}

function int($xValue) {
	if (is_int($xValue)) return $xValue;
	if (is_object($xValue)) {
		if (method_exists($xValue, 'toBool')) return bool($xValue->toBool());
		if (method_exists($xValue, 'toBoolean')) return bool($xValue->toBoolean());
	}
	return (int) $xValue;
}

function integer($xValue) {
	return int($xValue);
}

function str($xValue) {
	if (is_string($xValue)) return $xValue;
	if (is_array($xValue)) return '';

	if (is_object($xValue)) {
		if (!is_callable(array ($xValue, '__toString'))) return '';
	}

	return (string) $xValue;
}

function string($xValue) {
	return str($xValue);
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
	} else if (array_like($xFrom)) $vars = $xFrom;
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

function invoke($xCallback) {
	if (is_callable($xCallback)) return call_user_func($xCallback);
	if (is_string($xCallback)) {
		if (substr($xCallback, -1) != ';') $xCallback .= ';';
		return eval(substr($xCallback, -1) == ';' ? $xCallback : ($xCallback . ';'));
	}
}
