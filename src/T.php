<?php namespace amekusa\plz;

class T {
	const
		RES = 64, // resource
		OBJ = 32, // object
		ARR = 16, // array
		STR = 8, // string
		FLOAT = 4, // float
		INT = 2, // integer
		BOOL = 1, // boolean
		UNKNOWN = 0; // unknown_type

	/**
	 * @param string $Type A type expression
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
