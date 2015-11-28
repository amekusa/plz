<?php namespace amekusa\plz;

/**
 * A collection of utilities for Types.
 *
 * To get started, place the following line around top of your code.
 * ```php
 * use amekusa\plz\type;
 * ```
 */
abstract class type {

	/**
	 * Returns the type name of X
	 *
	 * If X is an object, returns the class name of the object.
	 *
	 * @param mixed $X A variable you want to know the type
	 * @return string A type name
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
	 * Returns whether the type of X matches a specific type
	 * @param mixed $X A variable to check type
	 * @param integer|string $Type A type expression
	 * @return boolean
	 */
	static function matches($X, $Type) {
		switch (is_int($Type) ? $Type : T::enum($type = str($Type))) {
			case T::BOOL: return is_bool($X);
			case T::INT: return is_int($X);
			case T::FLOAT: return is_float($X);
			case T::STR: return is_string($X);
			case T::ARR: return is_array($X);
			case T::OBJ: return is_object($X);
			case T::RES: return is_resource($X);
		}
		if (!$type) return false;
		switch ($type) {
			case 'any':
			case 'mixed': return true;
			case 'numeric': return is_numeric($X);
			case 'callable': return is_callable($X);
			case 'scalar': return is_scalar($X);
			case 'vector': return !is_scalar($X);
		}
		if (class_exists($type)) return $X instanceof $type;
		return $Type === gettype($X);
	}

	/**
	 * Returns whether X is an array/array-like object
	 *
	 * @example Basic usage
	 * ```php
	 * if (type::is_arr_like($var)) {
	 *   $var[] = 'Element'; // You can treat $var as like an array
	 * }
	 * ```
	 * @param mixed $X A variable to check type
	 * @return boolean
	 */
	static function is_arr_like($X) {
		if (is_array($X)) return true;
		if (is_object($X)) return $X instanceof \ArrayAccess;
		return false;
	}

	/**
	 * Returns whether X is iterable
	 *
	 * @example Basic usage
	 * ```php
	 * if (type::is_iterable($var)) {
	 *   foreach ($var as $item) { // You can iterate over $var
	 *     echo $item;
	 *   }
	 * }
	 * ```
	 * @param mixed $X A variable to check type
	 * @return boolean
	 */
	static function is_iterable($X) {
		if (is_array($X)) return true;
		if (is_object($X)) return $X instanceof \Traversable;
		return false;
	}

	/**
	 * Returns whether X is countable
	 *
	 * @example Basic usage
	 * ```php
	 * if (type::is_countable($var)) {
	 *   echo count($var); // You can count $var
	 * }
	 * ```
	 * @param mixed $X A variable to check type
	 * @return boolean
	 */
	static function is_countable($X) {
		if (is_array($X)) return true;
		if (is_object($X)) return $X instanceof \Countable;
		return false;
	}

	/**
	 * Treats X as a boolean
	 *
	 * + If X is an object, calls `$X->toBool()` / `$X->toBoolean()` if they exist.
	 * + If X is a countable object, returns `count($X) > 0`.
	 *
	 * @param mixed $X A variable to treat as a boolean
	 * @param boolean $Alt *(optional)* An alternative value to return if casting failed
	 * @return boolean
	 */
	static function bool($X, $Alt = false) {
		if (is_bool($X)) return $X;
		if (is_string($X)) {
			if (!$X) return false;
			switch (trim($X)) {
				case 'false':
				case 'False':
				case 'FALSE':
				case 'null':
				case 'Null':
				case 'NULL':
					return false;
			}
		} else if (is_object($X)) {
			if ($X instanceof \Countable) return $X->count() > 0;
			$r = null;
			if (is_callable(array ($X, 'toBool'))) $r = $X->toBool();
			else if (is_callable(array ($X, 'toBoolean'))) $r = $X->toBoolean();
			if (is_bool($r)) return $r;
		}
		try {
			return (bool) $X;
		} catch (RecoverableError $e) {
			return $Alt;
		}
	}

	/**
	 * Treats X as an integer
	 *
	 * If X is an object, returns X->toInt()/toInteger() if they exist.
	 *
	 * @param mixed $X
	 * @param integer $Alt [0] An alternative value to return if casting failed
	 * @return integer
	 */
	static function int($X, $Alt = 0) {
		if (is_int($X)) return $X;
		if (is_object($X)) {
			$r = null;
			if (is_callable(array ($X, 'toInt'))) $r = $X->toInt();
			else if (is_callable(array ($X, 'toInteger'))) $r = $X->toInteger();
			if (is_int($r)) return $r;
		}
		try {
			return (int) $X;
		} catch (RecoverableError $e) {
			return $Alt;
		}
	}

	/**
	 * Treats X as an string
	 *
	 * If X is an object, returns `__toString()`/`toStr()`/`toString()` if they exist.
	 *
	 * @param mixed $X
	 * @param string $Alt [''] An alternative value to return if the casting failed
	 * @return string
	 */
	static function str($X, $Alt = '') {
		if (is_string($X)) return $X;
		if (is_object($X)) {
			if (is_callable(array ($X, '__toString'))) return (string) $X;
			$r = null;
			if (is_callable(array ($X, 'toStr'))) $r = $X->toStr();
			else if (is_callable(array ($X, 'toString'))) $r = $X->toString();
			if (is_string($r)) return $r;
		}
		if (is_iterable($X)) { // Comma-separated strings
			$r = '';
			foreach ($X as $i => $item) {
				if (!$iValue = str($item)) continue;
				if (is_string($i)) $r .= "{$i}: {$iValue}, ";
				else $r .= "{$iValue}, ";
			}
			return $r ? substr($r, 0, -2) : $r;
		}
		try {
			return (string) $X;
		} catch (RecoverableError $e) {
			return $Alt;
		}
	}

	/**
	 * Treats X as an array
	 *
	 * If X is an object, returns X->toArr()/toArray() if they exist.
	 *
	 * @param mixed $X
	 * @param array $Alt [array ($X)] An alternative value to return if casting failed
	 * @return array
	 */
	static function arr($X, $Alt = null) {
		if (is_array($X)) return $X;
		$r = null;
		if (is_object($X)) {
			if (is_callable(array ($X, 'toArr'))) $r = $X->toArr();
			else if (is_callable(array ($X, 'toArray'))) $r = $X->toArray();
			if (is_array($r)) return $r;
			if ($X instanceof \Traversable) {
				$r = array ();
				foreach ($X as $i => $item) $r[$i] = $item;
				return $r;
			}
		}
		try {
			$r = (array) $X;
		} catch (RecoverableError $e) {
			$r = $Alt;
		}
		return $r ?: array ($X);
	}
}
