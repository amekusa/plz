<?php namespace amekusa\plz;

abstract class main {

	static function init() {
		static $done = false;
		if ($done) return;

		set_error_handler(function ($xCode, $xMsg, $xFile, $xLine, array $xContext) {
			if (strpos($xFile, __DIR__) !== 0) return false; // Not Plz issue

			/**
			 * Handlable errors:
			 * E_WARNING, E_NOTICE, E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE, E_STRICT,
			 * E_RECOVERABLE_ERROR, E_DEPRECATED, E_USER_DEPRECATED
			 *
			 * Unhandlable errors:
			 * E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING
			 */
			switch ($xCode) {
				case E_RECOVERABLE_ERROR:
					throw new RecoverableError($xMsg, $xCode, 1, $xFile, $xLine);
			}

			return false;
		});

		set_exception_handler(function (\Exception $xE) {
			if (!$xE instanceof LocalException) throw $xE; // Not Plz issue

			// TODO: Do special (ex. Show bug-report instructions)
			throw $xE;
		});

		$done = true;
	}
}

main::init();
