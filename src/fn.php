<?php namespace amekusa\plz;

abstract class fn {

	/**
	 * TODO Write doc
	 * @param callable $X
	 * @param array $Args
	 * @param mixed $Alt [null]
	 */
	static function call($X, $Args, $Alt = null) {
		if (!is_callable($X)) {
			if (is_callable($Alt)) return call_user_func($Alt, $Args);
			return $Alt;
		}
		return call_user_func_array($X, $Args);
	}
}
