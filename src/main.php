<?php namespace amekusa\plz; main::required;

/**
 * @ignore
 */
abstract class main {
	const required = true;

	static function init() {
		static $done = false;
		if ($done) return;

		set_error_handler(function ($Code, $Msg, $File = null, $Line = null, $Context = null) {
			if (!File || strpos($File, __DIR__) !== 0) return false;

			/**
			 * Handlable errors:
			 * E_WARNING, E_NOTICE, E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE, E_STRICT,
			 * E_RECOVERABLE_ERROR, E_DEPRECATED, E_USER_DEPRECATED
			 *
			 * Unhandlable errors:
			 * E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING
			 */
			switch ($Code) {
				case E_WARNING:
				case E_NOTICE:
				case E_RECOVERABLE_ERROR:
					throw new RecoverableError($Msg, $Code, 1, $File, $Line);
			}

			return false;
		});

		set_exception_handler(function ($E) {
			if (!$E instanceof LocalException) throw $E; // Not Plz issue

			// TODO: Do special (ex. Show bug-report instructions)
			throw $E;
		});

		$done = true;
	}
}

main::init();
