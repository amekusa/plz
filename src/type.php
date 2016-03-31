<?php namespace amekusa\plz; main::required;

/**
 * Type utilities
 *
 * To get started, place the following line around top of your code.
 * ```php
 * use amekusa\plz\type;
 * ```
 */
abstract class type {

	/**
	 * Returns the type name of `$X`
	 *
	 * If `$X` is an object, returns the class name of the object.
	 *
	 * @example Demonstration
	 * ```php
	 * var_dump( type::name(true)           );
	 * var_dump( type::name(1)              );
	 * var_dump( type::name(1.0)            );
	 * echo "\n";
	 * var_dump( type::name('string')       );
	 * var_dump( type::name(array ())       );
	 * var_dump( type::name(tmpfile())      );
	 * echo "\n";
	 * var_dump( type::name(new stdClass()) );
	 * ```
	 * @param mixed $X A variable you want to know the type
	 * @return string A type name or class name
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
	 * Returns whether the type of `$X` matches a specific type
	 * @example Demonstration
	 * ```php
	 * $var = '123';
	 * var_dump( type::matches($var, 'boolean') );
	 * var_dump( type::matches($var, 'bool')    ); // Shorten name
	 * echo "\n";
	 * var_dump( type::matches($var, 'integer') );
	 * var_dump( type::matches($var, 'int')     ); // Shorten name
	 * echo "\n";
	 * var_dump( type::matches($var, 'string')  );
	 * var_dump( type::matches($var, 'str')     ); // Shorten name
	 * echo "\n";
	 * var_dump( type::matches($var, 'array')   );
	 * var_dump( type::matches($var, 'arr')     ); // Shorten name
	 * ```
	 * @example Pseudo type matching
	 * ```php
	 * $var1 = '123';
	 * var_dump( type::matches($var1, 'numeric') );
	 * echo "\n";
	 * $var2 = array (1, 2, 3);
	 * var_dump( type::matches($var2, 'scalar') );
	 * var_dump( type::matches($var2, 'vector') );
	 * echo "\n";
	 * $var3 = function () { };
	 * var_dump( type::matches($var3, 'callable') );
	 * ```
	 * @example Class matching
	 * ```php
	 * class Cat { }
	 * class Dog { }
	 * class Collie extends Dog { }
	 *
	 * $dog = new Collie();
	 * var_dump( type::matches($dog, 'Collie') ); // Matches
	 * var_dump( type::matches($dog, 'Dog')    ); // Also matches with super-class
	 * var_dump( type::matches($dog, 'Cat')    ); // Never matches
	 * ```
	 * @param mixed $X A variable to check type
	 * @param integer|string $Type A type expression
	 * @return boolean
	 */
	static function matches($X, $Type) {
		switch (is_int($Type) ? $Type : T::enum($Type)) {
			case T::BOOL: return is_bool($X);
			case T::INT: return is_int($X);
			case T::FLOAT: return is_float($X);
			case T::STR: return is_string($X);
			case T::ARR: return is_array($X);
			case T::OBJ: return is_object($X);
			case T::RES: return is_resource($X);
			case T::UNKNOWN:
				if (!is_string($Type)) return false;
				switch ($Type) {
					case 'mixed': return true;
					case 'numeric': return is_numeric($X);
					case 'callable': return is_callable($X);
					case 'scalar': return is_scalar($X);
					case 'vector': return !is_scalar($X);
					default:
						if (is_object($X)) { // Assumes class name
							if (is_a($X, $Type)) return true;
						}
						if (gettype($X) == $Type) return true;
				}
		}
		return false;
	}

	/**
	 * Returns whether `$X` is an array or array-like object
	 * @example Demonstration
	 * ```php
	 * $var1 = array ();
	 * $var2 = new ArrayObject();
	 * $var3 = new stdClass();
	 * var_dump( type::is_arr_like($var1) );
	 * var_dump( type::is_arr_like($var2) );
	 * var_dump( type::is_arr_like($var3) );
	 * ```
	 * @example Using in `if`
	 * ```php
	 * $var = new ArrayObject();
	 * if (type::is_arr_like($var)) {
	 *   // In this block, you can safely treat $var as an array
	 *   $var[0]   = 'A'; // Inserts into a specific index
	 *   $var['X'] = 'B'; // Inserts into a specific key
	 *   $var[]    = 'C'; // Adds(Pushes) onto the last
	 * }
	 * print_r( $var );
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
	 * Returns whether `$X` is iterable
	 * @example Demonstration
	 * ```php
	 * $var1 = array ();
	 * $var2 = new ArrayObject();
	 * $var3 = new stdClass();
	 * var_dump( type::is_iterable($var1) );
	 * var_dump( type::is_iterable($var2) );
	 * var_dump( type::is_iterable($var3) );
	 * ```
	 * @example Using in `if`
	 * ```php
	 * $var = new ArrayObject(array ('A', 'B', 'C'));
	 * if (type::is_iterable($var)) {
	 *   // In this block,
	 *   // you can safely iterate over $var with foreach
	 *   foreach ($var as $i => $item) {
	 *     echo "$i: $item" . "\n";
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
	 * Returns whether `$X` is countable
	 * @example Demonstration
	 * ```php
	 * $var1 = array ();
	 * $var2 = new ArrayObject();
	 * $var3 = 'string';
	 * var_dump( type::is_countable($var1) );
	 * var_dump( type::is_countable($var2) );
	 * var_dump( type::is_countable($var3) );
	 * ```
	 * @example Using in `if`
	 * ```php
	 * $var = array ('A', 'B', 'C');
	 * if (type::is_countable($var)) {
	 *   // In this block, you can safely count $var
	 *   echo 'Number of elements: ' . count($var);
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
	 * Evaluates `$X` as a boolean
	 *
	 * In detail:
	 *
	 * + If `$X` is an object, calls `$X->toBoolean()` or `$X->toBool()` if they exist.
	 * + If `$X` is a countable object, returns whether `count($X) > 0`.
	 *
	 * @example Number to boolean
	 * ```php
	 * var_dump( type::bool(1)  ); // Integer
	 * var_dump( type::bool(0)  ); // Zero
	 * var_dump( type::bool(-1) ); // Negative
	 * ```
	 * @example String to boolean
	 * ```php
	 * var_dump( type::bool('string') ); // String
	 * var_dump( type::bool('')       ); // Empty String
	 * ```
	 * @example Array to boolean
	 * ```php
	 * var_dump( type::bool(array ('A', 'B', 'C')) ); // Array
	 * var_dump( type::bool(array ())              ); // Empty Array
	 * ```
	 * @example Semantic evaluation
	 * ```php
	 * // A string that is not empty, but "falsy" word is evaluated as false.
	 * var_dump( type::bool('false') );
	 * var_dump( type::bool('False') );
	 * var_dump( type::bool('FALSE') );
	 * var_dump( type::bool('null')  );
	 * var_dump( type::bool('Null')  );
	 * var_dump( type::bool('NULL')  );
	 * var_dump( type::bool('no')    );
	 * var_dump( type::bool('No')    );
	 * var_dump( type::bool('NO')    );
	 * var_dump( type::bool('off')   );
	 * var_dump( type::bool('Off')   );
	 * var_dump( type::bool('OFF')   );
	 * ```
	 * @example Methods evaluation
	 * ```php
	 * class Truthy {
	 *   function toBool() {
	 *     return true;
	 *   }
	 * }
	 *
	 * class Falsy {
	 *   function toBool() {
	 *     return false;
	 *   }
	 * }
	 *
	 * $obj1 = new Truthy();
	 * $obj2 = new Falsy();
	 * var_dump( type::bool($obj1) );
	 * var_dump( type::bool($obj2) );
	 * ```
	 * @param mixed $X A variable to treat as a boolean
	 * @param boolean $Alt *(optional)* An alternative value to return if evaluation has failed
	 * @return boolean
	 */
	static function bool($X, $Alt = false) {
		if (is_bool($X)) return $X;
		if (is_string($X)) {
			if (!$X) return false;
			switch (str::trim($X)) {
				case 'false':
				case 'False':
				case 'FALSE':
				case 'null':
				case 'Null':
				case 'NULL':
				case 'no':
				case 'No':
				case 'NO':
				case 'off':
				case 'Off':
				case 'OFF':
					return false;
			}
		} else if (is_object($X)) {
			if ($X instanceof \Countable) return $X->count() > 0;
			$r = null;
			if (is_callable(array ($X, 'toBoolean'))) $r = $X->toBoolean();
			else if (is_callable(array ($X, 'toBool'))) $r = $X->toBool();
			if (is_bool($r)) return $r;
		}
		try {
			return (bool) $X;
		} catch (ErrorException $e) {
			return $Alt;
		}
	}

	/**
	 * Evaluates `$X` as an integer
	 *
	 * If `$X` is an object, calls `$X->toInteger()` or `$X->toInt()` if they exist.
	 *
	 * @example Demonstration
	 * ```php
	 * var_dump( type::int(true)                  ); // True
	 * var_dump( type::int(false)                 ); // False
	 * echo "\n";
	 * var_dump( type::int('string')              ); // String
	 * var_dump( type::int('0123')                ); // Numeric String
	 * echo "\n";
	 * var_dump( type::int(array ('A', 'B', 'C')) ); // Array
	 * var_dump( type::int(array ())              ); // Empty Array
	 * ```
	 * @example Methods evaluation
	 * ```php
	 * class Hundred {
	 *   function toInt() {
	 *     return 100;
	 *   }
	 * }
	 *
	 * $obj = new Hundred();
	 * var_dump( type::int($obj)     ); // Same as $obj->toInt()
	 * var_dump( type::int($obj) * 3 ); // 3 hundreds
	 * ```
	 * @example Fail-safe
	 * ```php
	 * $obj = new stdClass(); // Uncastable object
	 * // If the evaluation has failed, 0 is returned by default.
	 * // You can change it to any value by 2nd parameter
	 * var_dump( type::int($obj)     );
	 * var_dump( type::int($obj, -1) );
	 * ```
	 * @param mixed $X
	 * @param integer $Alt *(optional)* An alternative value to return if evaluation has failed
	 * @return integer
	 */
	static function int($X, $Alt = 0) {
		if (is_int($X)) return $X;
		if (is_object($X)) {
			$r = null;
			if (is_callable(array ($X, 'toInteger'))) $r = $X->toInteger();
			else if (is_callable(array ($X, 'toInt'))) $r = $X->toInt();
			if (is_int($r)) return $r;
		}
		try {
			return (int) $X;
		} catch (ErrorException $e) {
			return $Alt;
		}
	}

	/**
	 * Evaluates `$X` as an string
	 *
	 * If `$X` is an object, calls `$X->__toString()`, `$X->toString()` or `$X->toStr()` if they exist.
	 *
	 * @example Demonstration
	 * ```php
	 * var_dump( type::str(true)  ); // True
	 * var_dump( type::str(false) ); // False
	 * echo "\n";
	 * var_dump( type::str(1)     ); // Integer
	 * var_dump( type::str(0)     ); // Zero
	 * var_dump( type::str(-1)    ); // Negative
	 * echo "\n";
	 * var_dump( type::str(1.23)  ); // Float
	 * ```
	 * @example Semantic evaluation
	 * ```php
	 * // An array or iterable object is evaluated as a comma-separated list
	 * $var1 = array ('A', 'B', 'C'); // Array
	 * $var2 = array (                // Associative Array
	 *   'X' => 'A',
	 *   'Y' => 'B',
	 *   'Z' => 'C'
	 * );
	 * var_dump( type::str($var1) );
	 * var_dump( type::str($var2) );
	 * ```
	 * @example Method evaluation
	 * ```php
	 * class Greeting {
	 *   function toStr() {
	 *     return 'Hello';
	 *   }
	 * }
	 *
	 * $obj = new Greeting();
	 * var_dump( type::str($obj) );    // Same as $obj->toStr();
	 * echo type::str($obj).' World.'; // 'Hello World'
	 * ```
	 * @param mixed $X
	 * @param string $Alt *(optional)* An alternative value to return if the evaluation failed
	 * @return string
	 */
	static function str($X, $Alt = '') {
		if (is_string($X)) return $X;
		if (is_object($X)) {
			if (is_callable(array ($X, '__toString'))) return (string) $X;
			$r = null;
			if (is_callable(array ($X, 'toString'))) $r = $X->toString();
			else if (is_callable(array ($X, 'toStr'))) $r = $X->toStr();
			if (is_string($r)) return $r;
		}
		if (type::is_iterable($X)) { // Convert into a comma-separated string
			$r = '';
			foreach ($X as $i => $item) {
				if (!$iValue = type::str($item)) continue;
				if (is_string($i)) $r .= "{$i}: {$iValue}, ";
				else $r .= "{$iValue}, ";
			}
			return $r ? substr($r, 0, -2) : $r;
		}
		try {
			return (string) $X;
		} catch (ErrorException $e) {
			return $Alt;
		}
	}

	/**
	 * Evaluates `$X` as an array
	 *
	 * If `$X` is an object, calls `$X->toArray()` or `$X->toArr()` if they exist.
	 *
	 * @example Demonstration
	 * ```php
	 * var_dump( type::arr(null) ); // Null
	 * var_dump( type::arr(true) ); // Boolean
	 * var_dump( type::arr(1)    ); // Integer
	 * ```
	 * @example Method evaluation
	 * ```php
	 * class Stack {
	 *   private $items;
	 *
	 *   function __construct() {
	 *     $this->items = func_get_args();
	 *   }
	 *
	 *   function toArray() {
	 *     return $this->items;
	 *   }
	 * }
	 *
	 * $obj = new Stack('A', 'B', 'C');
	 * var_export( type::arr($obj) );
	 * ```
	 * @param mixed $X
	 * @param array $Alt *(optional)* An alternative value to return if casting failed
	 * @return array
	 */
	static function arr($X, $Alt = array ()) {
		if (is_array($X)) return $X;
		if (is_object($X)) {
			$r = null;
			if (is_callable(array ($X, 'toArray'))) $r = $X->toArray();
			else if (is_callable(array ($X, 'toArr'))) $r = $X->toArr();
			if (is_array($r)) return $r;
			if ($X instanceof \Traversable) {
				$r = array ();
				foreach ($X as $i => $item) $r[$i] = $item;
				return $r;
			}
		}
		try {
			return (array) $X;
		} catch (ErrorException $e) {
			return $Alt;
		}
	}
}
