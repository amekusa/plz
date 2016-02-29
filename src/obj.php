<?php namespace amekusa\plz; main::required;

/**
 * Object utilities
 *
 * To get started, place the following line around top of your code.
 * ```php
 * use amekusa\plz\obj;
 * ```
 */
abstract class obj {

	/**
	 * Returns whether two objects are equal
	 *
	 * If the objects are **same instance**, returns `true`.
	 *
	 * Otherwise the result is same as `$X == $Y`
	 * except for under certain conditions:
	 *
	 * + If `$X` has `equals()` method, invokes `$X->equals($Y)`
	 * + If `$Y` has `equals()` method, invokes `$Y->equals($X)`
	 *
	 * @example Comparing objects of the same class
	 * ```php
	 * $P1 = new stdClass();
	 * $P1->x = 1.0;
	 * $P1->y = 2.0;
	 *
	 * $P2 = new stdClass();
	 * $P2->x = 2.0;
	 * $P2->y = 4.0;
	 *
	 * $P3 = new stdClass();
	 * $P3->x = 1.0;
	 * $P3->y = 2.0;
	 *
	 * echo 'Does $P1 equal to $P2? - ';
	 * echo obj::eq($P1, $P2) ? 'Yes.' : 'No.';
	 * echo "\n";
	 * echo 'Does $P1 equal to $P3? - ';
	 * echo obj::eq($P1, $P3) ? 'Yes.' : 'No.';
	 * ```
	 * @example `equals()` evaluation
	 * ```php
	 * class Fraction {
	 *   public $numerator, $denominator;
	 *
	 *   function __construct($Numerator, $Denominator) {
	 *     $this->numerator   = $Numerator;
	 *     $this->denominator = $Denominator;
	 *   }
	 *
	 *   function quotient() {
	 *     return $this->numerator / $this->denominator;
	 *   }
	 *
	 *   function equals($Fraction) {
	 *     return $this->quotient() == $Fraction->quotient();
	 *   }
	 * }
	 *
	 * $X = new Fraction(2, 1); // = 2/1
	 * $Y = new Fraction(4, 2); // = 4/2
	 *
	 * echo 'Does $X equal to $Y? - ';
	 * echo obj::eq($X, $Y) ? 'Yes.' : 'No.';
	 * ```
	 * @param object $X An object to compare with `$Y`
	 * @param object $Y An object to compare with `$X`
	 * @return boolean
	 */
	static function eq($X, $Y) {
		if ($X === $Y) return true; // Same instance
		if (is_callable(array ($X, 'equals'))) return $X->equals($Y);
		if (is_callable(array ($Y, 'equals'))) return $Y->equals($X);
		return $X == $Y;
	}

	/**
	 * Returns a property of an object
	 *
	 * If `$X` has a *getter* method, calls it.
	 *
	 * @example Demonstration
	 * ```php
	 * class Student {
	 *   public $name;
	 *   private $age;
	 *
	 *   public function __construct($Name, $Age) {
	 *     $this->name = $Name;
	 *     $this->age = $Age;
	 *   }
	 * }
	 *
	 * $student = new Student('Alice', 21);
	 * var_dump( obj::get($student, 'name') );
	 * // Because of $age is private and no getter method, you can never get it
	 * var_dump( obj::get($student, 'age') );            // Alternates with NULL
	 * var_dump( obj::get($student, 'age', 'Secret!') ); // Alternates with a string
	 * ```
	 * @example Calling a *getter* method
	 * ```php
	 * class Student {
	 *   public $name;
	 *   private $age;
	 *
	 *   public function __construct($Name, $Age) {
	 *     $this->name = $Name;
	 *     $this->age = $Age;
	 *   }
	 *
	 *   // The getter method for $age
	 *   public function getAge() {
	 *     return $this->age;
	 *   }
	 * }
	 *
	 * $student = new Student('Alice', 21);
	 * var_dump( obj::get($student, 'name') );
	 * var_dump( obj::get($student, 'age') );            // Invokes $student->getAge()
	 * var_dump( obj::get($student, 'age', 'Secret!') ); // No alternation
	 * ```
	 * @param object $X An object to retrieve a property from
	 * @param string $Prop The name of a property to get
	 * @param mixed $Alt *(optional)* A fail-safe value
	 */
	static function get($X, $Prop, $Alt = null) {
		$getter = array ($X, 'get' . ucfirst($Prop));
		if (is_callable($getter)) return call_user_func($getter);
		$props = get_object_vars($X);
		if (!array_key_exists($Prop, $props)) return $Alt;
		return $props[$Prop];
	}

	/**
	 * @ignore
	 * @todo Implement
	 * @param unknown $Prop
	 * @param unknown $X
	 */
	static function set($Prop, $X) {
	}
}
