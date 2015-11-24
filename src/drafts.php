<?php namespace amekusa\plz;

/**
 * Gets the extention from a file path.
 * @param string $xPath
 * @return string
 */
function extension($xPath) {
	return substr($xPath, strrpos($xPath, '.') + 1);
}

function ext($xPath) {
	return extension($xPath);
}

/**
 * @param integer $xNumber
 * @return string
 */
function ordinal($xNumber) {
	if (abs($xNumber) % 100 < 21 && abs($xNumber) % 100 > 4) $suffix = 'th';
	else {
		switch ($xNumber % 10) {
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
	return $xNumber . $suffix;
}

function buffer($xCallback = null, $xForcesPush = false) {
	static $buffers = array ();

	if (is_null($xCallback)) return array_pop($buffers);

	ob_start();
	invoke($xCallback);
	$r = ob_get_clean();
	if ($xForcesPush || !empty($r)) $buffers[] = $r;

	return $r;
}

function bf($xCallback = null, $xForcesPush = false) {
	return buffer($xCallback, $xForcesPush);
}

function call($xCallback, $xArgs = null, $xAlt = false) {
	if (!is_callable($xCallback)) return $xAlt;

}

function invoke($xCallback) {
	if (is_callable($xCallback)) return call_user_func($xCallback);
	if (is_string($xCallback)) {
		if (substr($xCallback, -1) != ';') $xCallback .= ';';
		return eval(substr($xCallback, -1) == ';' ? $xCallback : ($xCallback . ';'));
	}
}
