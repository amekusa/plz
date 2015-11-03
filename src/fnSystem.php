<?php namespace amekusa\plz;

trait fnSystem {
}

/**
 * Verifies a PHP directive has a specific value
 *
 * @param string $xName The name of the directive
 * @param mixed $xValue The value of the directive
 * @return boolean Returns true if the directive has a correct value. Otherwise false
 */
function ensure_ini($xName, $xValue) {
	if ($x = ini_get($xName) === false)
		throw new \RuntimeException("No such directive: {$xName}");

	if ($x === $xValue) return true;

	if (ini_set($xName, $xValue) === false)
		throw new \RuntimeException("The value of the directive: {$xName} must be " . print_r($xValue));

	return true;
}

function ignore_errors() {
	error_reporting(0);
}

function ignore_warnings() {
	error_reporting(E_USER_NOTICE);
}
