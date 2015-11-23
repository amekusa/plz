<?php namespace amekusa\plz;

abstract class op {

	/**
	 * Returns whether or not X equals Y
	 * @param mixed $X
	 * @param mixed $Y
	 * @return boolean
	 */
	static function eq($X, $Y) {
		if (is_array($X)) {
			if ($X != $Y) return false;
			foreach ($X as $i => $iX) {
				if (!op::eq($X, $Y)) return false;
			}
			return true;
		}
		if (is_object($X)) {
			if ($X === $Y) return true; // Same instance
			if (get_class($X) != get_class($Y)) return false;

			// TODO Implement additional comparing methods

			if ($X instanceof \Traversable) {
				if ($X instanceof \ArrayAccess) {
					foreach ($X as $i => $iX) {
						if (!$Y->offsetExists($i)) return false;
						if (!op::eq($iX, $Y[$i])) return false;
					}
					return true;
				}
			}
		}
		return $X === $Y;
	}
}
