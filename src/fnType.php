<?php namespace amekusa\plz;

trait fnType {
	use fnString;
}

/**
 * Returns the type of a value
 *
 * If the value is an object, returns the class name of the object.
 *
 * @param mixed $xValue
 * @return string The type expression
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
function is_arr_like($xValue) {
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
function is_iterable($xValue) {
	if (is_array($xValue)) return true;
	if (is_object($xValue)) return $xValue instanceof \Traversable;
	return false;
}

/**
 * Treats a value as a boolean
 *
 * If $xValue is an object, returns $xValue->toBool()|toBoolean() if they exist
 *
 * @param mixed $xValue
 * @param boolean $xAlt [Default:false] The alternative value to return if the casting failed
 * @return boolean
 */
function bool($xValue, $xAlt = false) {
	if (is_bool($xValue)) return $xValue;
	if (is_string($xValue)) {
		if (!$xValue) return false;
		switch (trim($xValue)) {
			case '':
			case 'false':
			case 'False':
			case 'FALSE':
			case 'null':
			case 'Null':
			case 'NULL':
				return false;
		}
	} else if (is_object($xValue)) {
		$r = null;
		if (is_callable(array ($xValue, 'toBool'))) $r = $xValue->toBool();
		else if (is_callable(array ($xValue, 'toBoolean'))) $r = $xValue->toBoolean();
		if (is_bool($r)) return $r;
	}
	try {
		return (bool) $xValue;
	} catch (RecoverableError $e) {
		return $xAlt;
	}
}

/**
 * An alias of bool()
 */
function boolean($xValue, $xAlt = false) {
	return bool($xValue, $xAlt);
}

/**
 * Treats a value as an integer
 *
 * If $xValue is an object, returns $xValue->toInt()|toInteger() if they exist
 *
 * @param mixed $xValue
 * @param integer $xAlt [Default:0] The alternative value to return if the casting failed
 * @return integer
 */
function int($xValue, $xAlt = 0) {
	if (is_int($xValue)) return $xValue;
	if (is_object($xValue)) {
		$r = null;
		if (is_callable(array ($xValue, 'toInt'))) $r = $xValue->toInt();
		else if (is_callable(array ($xValue, 'toInteger'))) $r = $xValue->toInteger();
		if (is_int($r)) return $r;
	}
	try {
		return (int) $xValue;
	} catch (RecoverableError $e) {
		return $xAlt;
	}
}

/**
 * An alias of int()
 */
function integer($xValue, $xAlt = 0) {
	return int($xValue, $xAlt);
}

/**
 * Treats a value as an string
 *
 * If $xValue is an object, returns $xValue->__toString()|toStr()|toString() if they exist
 *
 * @param mixed $xValue
 * @param string $xAlt [Default:''] The alternative value to return if the casting failed
 * @return string
 */
function str($xValue, $xAlt = '') {
	if (is_string($xValue)) return $xValue;
	if (is_object($xValue)) {
		if (is_callable(array ($xValue, '__toString'))) return (string) $xValue;
		$r = null;
		if (is_callable(array ($xValue, 'toStr'))) $r = $xValue->toStr();
		else if (is_callable(array ($xValue, 'toString'))) $r = $xValue->toString();
		if (is_string($r)) return $r;
	}
	if (is_iterable($xValue)) { // Comma-separated strings
		$r = '';
		foreach ($xValue as $i => $item) {
			if (!$iValue = str($item)) continue;
			if (is_string($i)) $r .= "{$i}: {$iValue}, ";
			else $r .= "{$iValue}, ";
		}
		return $r ? substr($r, 0, -2) : $r;
	}
	try {
		return (string) $xValue;
	} catch (RecoverableError $e) {
		return $xAlt;
	}
}

/**
 * An alias of str()
 */
function string($xValue, $xAlt = '') {
	return str($xValue, $xAlt);
}

/**
 * Treats a value as an array
 *
 * If $xValue is an object, returns $xValue->toArr()|toArray() if they exist
 *
 * @param mixed $xValue
 * @param string $xAlt [Default:array ($xValue)] The alternative value to return if the casting failed
 * @return array
 */
function arr($xValue, $xAlt = null) {
	if (is_array($xValue)) return $xValue;
	$r = null;
	if (is_object($xValue)) {
		if (is_callable(array ($xValue, 'toArr'))) $r = $xValue->toArr();
		else if (is_callable(array ($xValue, 'toArray'))) $r = $xValue->toArray();
		if (is_array($r)) return $r;

		if ($xValue instanceof \Traversable) {
			$r = array ();
			foreach ($xValue as $i => $item) $r[$i] = $item;
			return $r;
		}
	}
	try {
		$r = (array) $xValue;
	} catch (RecoverableError $e) {
		$r = $xAlt;
	}
	return $r ?: array ($xValue);
}
