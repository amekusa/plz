<?php namespace amekusa\plz;

abstract class type {

	/**
	 * Returns the type name of X
	 *
	 * If X is an object, returns the class name of the object.
	 *
	 * @param mixed $X
	 * @return string The type name
	 */
	static function name($X) {
		if (is_object($X)) return get_class($X);
		if (is_bool($X)) return 'boolean';
		if (is_int($X)) return 'integer';
		if (is_float($X)) return 'float';
		if (is_string($X)) return 'string';
		if (is_array($X)) return 'array';
		if (is_resource($X)) return 'resource';
		return gettype($X);
	}

	/**
	 * Returns whether or not the type of X matches a specific type
	 *
	 * @param mixed $x
	 * @param int|string $xType A type expression
	 * @return boolean
	 */
	static function matches($x, $xType) {
		switch (is_int($xType) ? $xType : T::enum(str($xType))) {
			case T::BOOL: return is_bool($x);
			case T::INT: return is_int($x);
			case T::FLOAT: return is_float($x);
			case T::STR: return is_string($x);
			case T::ARR: return is_array($x);
			case T::OBJ: return is_object($x);
			case T::RES: return is_resource($x);
		}

		switch ($xType) {
			case 'mixed':
			case 'any': return true;
		}

		if ($xType === 'mixed') return true;
		if ($xType === 'numeric') return is_numeric($x);
		if ($xType === 'callable') return is_callable($x);
		if ($xType === 'scalar') return is_scalar($x);
		if ($xType === 'vector') return !is_scalar($x);

		if ($xType === 'long') return is_long($x);
		if ($xType === 'double') return is_double($x);
		if ($xType === 'real') return is_real($x);

		if (class_exists($xType)) return $x instanceof $xType;

		return $xType === gettype($x);
	}

	/**
	 * Returns whether or not X is an array or array-like object
	 *
	 * @param mixed $X
	 * @return boolean
	 */
	static function is_arr_like($X) {
		if (is_array($X)) return true;
		if (is_object($X)) return $X instanceof \ArrayAccess;
		return false;
	}

	/**
	 * Returns whether or not X is iterable
	 *
	 * @param mixed $X
	 * @return boolean
	 */
	static function is_iterable($X) {
		if (is_array($X)) return true;
		if (is_object($X)) return $X instanceof \Traversable;
		return false;
	}

	/**
	 * Returns whether or not X is countable
	 *
	 * @param mixed $x
	 * @return boolean
	 */
	static function is_countable($x) {
		if (is_array($x)) return true;
		if (is_object($x)) return $x instanceof \Countable;
		return false;
	}

	/**
	 * Treats a value as a boolean
	 *
	 * If $xValue is an object, returns $xValue->toBool()|toBoolean() if they exist.
	 * If $xValue is a countable object, returns (bool) $xValue->count().
	 *
	 * @param mixed $xValue
	 * @param boolean $xAlt [Default:false] The alternative value to return if the casting failed
	 * @return boolean
	 */
	static function bool($xValue, $xAlt = false) {
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
			if ($xValue instanceof \Countable) return $xValue->count() > 0;
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
	static function boolean($xValue, $xAlt = false) {
		return bool($xValue, $xAlt);
	}

	/**
	 * Treats a value as an integer
	 *
	 * If $xValue is an object, returns $xValue->toInt()|toInteger() if they exist.
	 *
	 * @param mixed $xValue
	 * @param integer $xAlt [Default:0] The alternative value to return if the casting failed
	 * @return integer
	 */
	static function int($xValue, $xAlt = 0) {
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
	static function integer($xValue, $xAlt = 0) {
		return int($xValue, $xAlt);
	}

	/**
	 * Treats a value as an string
	 *
	 * If $xValue is an object, returns $xValue->__toString()|toStr()|toString() if they exist.
	 *
	 * @param mixed $xValue
	 * @param string $xAlt [Default:''] The alternative value to return if the casting failed
	 * @return string
	 */
	static function str($xValue, $xAlt = '') {
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
	static function string($xValue, $xAlt = '') {
		return str($xValue, $xAlt);
	}

	/**
	 * Treats a value as an array
	 *
	 * If $xValue is an object, returns $xValue->toArr()|toArray() if they exist.
	 *
	 * @param mixed $xValue
	 * @param string $xAlt [Default:array ($xValue)] The alternative value to return if the casting failed
	 * @return array
	 */
	static function arr($xValue, $xAlt = null) {
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
}