<?php namespace amekusa\plz;

abstract class num {

	/**
	 * TODO Write doc
	 * @param integer $X
	 * @return string
	 */
	static function ordinal($X) {
		if (abs($X) % 100 < 21 && abs($X) % 100 > 4) $suffix = 'th';
		else {
			switch ($X % 10) {
				case 1:
					$suffix = 'st';
					break;
				case 2:
					$suffix = 'nd';
					break;
				case 3:
					$suffix = 'rd';
					break;
			}
		}
		return $X . $suffix;
	}

	/**
	 * TODO Write doc
	 * @param integer $X
	 * @return string
	 */
	static function abc($X) {
		static $map = null;
		if (!$map) $map = range('a', 'z');
		return $map[$X % 26];
	}
}
