<?php namespace amekusa\plz; main::required;

/**
 * Path utilities
 *
 * To get started, place the following line around top of your code.
 * ```php
 * use amekusa\plz\path;
 * ```
 */
abstract class path {

	/**
	 * Returns a normalized path
	 * @example Absolute path
	 * ```php
	 * echo path::normal('/srv//http/example.com///');
	 * ```
	 * @example Relative path
	 * ```php
	 * echo path::normal('images//favicon.svg');
	 * ```
	 * @example Normalize to slash
	 * ```php
	 * echo path::normal('xxx\yyy\zzz');
	 * ```
	 * @example Normalize to backslash
	 * ```php
	 * echo path::normal('xxx/yyy/zzz', '\\');
	 * ```
	 * @param string $X Path to normalize
	 * @param string $Separator [`'/'`] Directory separator
	 */
	static function normal($X, $Separator = '/') {
		return preg_replace('/(?:\/|\\\\|'.preg_quote('/', '/').')+/',
				$Separator, $X);
	}

	/**
	 * Returns the extension of a file path
	 * @example Demonstration
	 * ```php
	 * echo path::ext('choosy-developers-choose.gif');
	 * ```
	 * @param string $X A file path
	 * @return string The extension of `$X`
	 */
	static function ext($X) {
		return substr($X, strrpos($X, '.'));
	}

	/**
	 * Returns a single path that is concatenated with supplied paths
	 * @example Demonstration
	 * ```php
	 * echo path::join('/srv', 'http/', '/example.com');
	 * ```
	 * @example Passing an array
	 * ```php
	 * echo path::join(array ('/srv', 'http', 'example.com'));
	 * ```
	 * @param array|string* $Paths Pieces of a path
	 */
	static function join($Paths) {
		return path::join_with('/', func_num_args() > 1 ? func_get_args() :
				(type::is_iterable($Paths) ? $Paths : type::arr($Paths)));
	}

	static function join_with($Separator, $Paths) {
		if (!$Paths) return '';
		return path::normal(implode($Separator, array_filter($Paths,
				__NAMESPACE__.'\type::str')), $Separator);
	}
}
