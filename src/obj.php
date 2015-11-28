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
	 * @param object $X
	 * @param string $Prop
	 * @param mixed $Alt *(optional)*
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
