<?php namespace amekusa\plz; main::required;

/**
 * Document Object Model utilities
 *
 * To get started, place the following line around top of your code.
 * ```php
 * use amekusa\plz\dom;
 * ```
 */
abstract class dom {

	/**
	 * @ignore
	 * @example Demonstration
	 * ```php
	 * var_dump( dom::parse('<input class="hoge hage hide" id="uha">') );
	 * ```
	 * @param string $Attributes
	 * @return array Associated array of attributes
	 */
	static function parse($Attributes) {
		$r = array ();
		$matches = array ();
		preg_match_all('/\s+([a-zA-Z:_][a-zA-Z0-9:._-]*)=(".*?[^\\\\]"|\'.*?[^\\\\]\')/', ' '.$Attributes, $matches);
		foreach ($matches[1] as $i => $iMatch)
			$r[$iMatch] = substr($matches[2][$i], 1, -1);
		return $r;
	}

	/**
	 * Returns DOM attribute expression
	 * @example Single attribute
	 * ```php >> html
	 * echo '<div' . dom::attr('class', 'cols') . '>';
	 * ```
	 * @example Boolean attribute
	 * ```php >> html
	 * echo '<input type="radio"' . dom::attr('checked', true) . '/>' . "\n";
	 * echo '<input type="radio"' . dom::attr('checked', false) . '/>';
	 * ```
	 * @param string $X Attribute name
	 * @param mixed $Value Attribute value
	 * @param mixed $Default *(optional)* Default value of the attribute
	 * @return string DOM attribute expression
	 */
	static function attr($X, $Value, $Default = null) {
		if (!$name = htmlspecialchars($X, ENT_QUOTES, null, false)) return '';
		if (is_bool($Value)) return $Value ? " $name=\"$name\"" : '';
		if (!$Value) return isset($Default) ? dom::attr($X, $Default) : '';
		return " $name=\"".htmlspecialchars($Value, ENT_QUOTES, null, false)."\"";
	}

	/**
	 * Returns DOM attribute(s) expression
	 * @example Multiple attributes
	 * ```php >> html
	 * $var = array (
	 *   'name' => 'eula',
	 *   'type' => 'checkbox',
	 *   'value' => 'agreed'
	 * );
	 * echo '<input' . dom::attrs($var) . '/>';
	 * ```
	 * @param array|object $Attrs Associative array the structure of which is `[Name => Value]`
	 * @return string DOM attribute(s) expression
	 */
	static function attrs($Attrs) {
		$r = '';
		foreach ($Attrs as $i => $iValue) $r .= dom::attr($i, $iValue);
		return $r;
	}
}
