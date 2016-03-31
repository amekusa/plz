<?php namespace amekusa\plz; main::required;

/**
 * A catchable error representation
 * @author amekusa <post@amekusa.com>
 */
class ErrorException extends \ErrorException {

	/**
	 * Creates an instance
	 * @param string $Msg [optional] The Exception message to throw
	 * @param integer $Code [optional] The Exception code
	 * @param integer $Severity [optional] The severity level of the exception
	 * @param string $File [optional] The filename where the exception is thrown
	 * @param integer $Line [optional] The line number where the exception is thrown
	 * @param \Exception $Previous [optional] The previous exception used for the exception chaining
	 * @return ErrorException An instance
	 */
	public static function create($Msg = null, $Code = null, $Severity = null, $File = null, $Line = null, $Previous = null) {
		return new static($Msg, $Code, $Severity, $File, $Line, $Previous);
	}

	/**
	 * Triggers a PHP error
	 * @param boolean $ShowsStackTrace [optional] Whether to include the stack trace into the error message
	 */
	public function trigger($ShowsStackTrace = false) {
		$msg = $this->getMessage();
		if ($ShowsStackTrace) $msg .= "\nStack Trace:\n{$this->getTraceAsString()}";
		$severity = null;
		switch ($this->getSeverity()) {
			case E_NOTICE:
			case E_USER_NOTICE:
				$severity = E_USER_NOTICE;
				break;
			case E_WARNING:
			case E_USER_WARNING:
				$severity = E_USER_WARNING;
				break;
			case E_DEPRECATED:
			case E_USER_DEPRECATED:
				$severity = E_USER_DEPRECATED;
				break;
			default:
				$severity = E_USER_ERROR;
		}
		trigger_error($msg, $severity);
	}

	/**
	 * Whether the current error reporting level satisfies this exception's severity
	 * @return boolean
	 */
	public function shouldReport() {
		return error_reporting() & $this->getSeverity();
	}
}
