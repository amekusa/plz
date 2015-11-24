<?php namespace amekusa\plz;

abstract class obj {

	static function get($Prop, $X, $Alt = null) {
		$getter = array ($X, 'get' . ucfirst($Prop));
		if (is_callable($getter)) return call_user_func($getter);
		$props = get_object_vars($X);
		if (!array_key_exists($Prop, $props)) return $Alt;
		return $props[$Prop];
	}

	static function set($Prop, $X) {
	}
}
