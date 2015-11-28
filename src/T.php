<?php namespace amekusa\plz;

/**
 * A collection of type enumerations
 */
class T {

	const
		/** Represents `resource` */
		RES = 64,
		/** Represents `object` */
		OBJ = 32,
		/** Represents `array` */
		ARR = 16,
		/** Represents `string` */
		STR = 8,
		/** Represents `float` */
		FLOAT = 4,
		/** Represents `integer` */
		INT = 2,
		/** Represents `boolean` */
		BOOL = 1,
		/** Represents `unknown_type` */
		UNKNOWN = 0;

	/**
	 * Returns a type enumeration by name
	 * @param string $Type A type name
	 * @return integer A type enumeration
	 */
	public static function enum($Type) {
		static $map = array (
			'resource' => self::RES,
			'object' => self::OBJ,
			'array' => self::ARR,
			'arr' => self::ARR,
			'string' => self::STR,
			'str' => self::STR,
			'real' => self::FLOAT,
			'double' => self::FLOAT,
			'float' => self::FLOAT,
			'long' => self::INT,
			'integer' => self::INT,
			'int' => self::INT,
			'boolean' => self::BOOL,
			'bool' => self::BOOL
		);
		if (!isset($map[$Type])) return self::UNKNOWN;
		return $map[$Type];
	}
}
