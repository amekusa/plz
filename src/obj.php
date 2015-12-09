<?php namespace amekusa\plz;

/**
 * A collection of utilities for Objects.
 *
 * To get started, place the following line around top of your code.
 * ```php
 * use amekusa\plz\obj;
 * ```
 */
abstract class obj {

	/**
	 * Returns a property of an object
	 *
	 * If `$X` has a *getter* method, calls it.
	 *
	 * @example Basic usage
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
	 * $r1 = obj::get($student, 'name', 'Secret!'); // $r1 = 'Alice'
	 * $r2 = obj::get($student, 'age', 'Secret!');  // $r2 = 'Secret!'
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
	 *     return $this->age - 2; // Lies :P
	 *   }
	 * }
	 *
	 * $student = new Student('Alice', 21);
	 * $r1 = obj::get($student, 'name', 'Secret!'); // $r1 = 'Alice'
	 * $r2 = obj::get($student, 'age', 'Secret!');  // $r2 = '19'
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
