<?php namespace amekusa\plz;

/**
 * @ignore
 */
abstract class main {
	const required = true;

	static function init() {
		static $done = false;
		if ($done) return;

		/**
		 * Converts an error into an ErrorException
		 *
		 * Handlable errors:
		 * E_WARNING, E_NOTICE, E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE, E_STRICT,
		 * E_RECOVERABLE_ERROR, E_DEPRECATED, E_USER_DEPRECATED
		 *
		 * Unhandlable errors:
		 * E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING
		 */
		set_error_handler(function ($Severity, $Msg, $File = null, $Line = null, $Context = null) {
			if (!$File || strpos($File, __DIR__.DIRECTORY_SEPARATOR) !== 0) return false;
					// Passes through the error if it occurred outside of the library

			throw ErrorException::create($Msg, 0, $Severity, $File, $Line);
		});

		// Handles exceptions & errors
		set_exception_handler(function ($E) {
			$reflection = new \ReflectionObject($E);
			if (!$reflection->inNamespace()) throw $E;
					// Passes through the exception that is out of our namespace

			if ($E instanceof ErrorException) {
				if ($E->shouldReport()) $E->trigger(true);
				return;
			}
			// TODO: Do special (ex. Show bug-report instructions)
			throw $E;
		});

		$done = true;
	}
}

main::init();
