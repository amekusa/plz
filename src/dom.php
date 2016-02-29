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
	 * Returns a DOM attribute expression
	 * @example "class" attribute
	 * ```php >> html
	 * $class = 'cols';
	 * echo '<div' . dom::attr('class', $class) . '>';
	 * ```
	 * @example Returns nothing if the value is empty
	 * ```php >> html
	 * $class = ''; // Empty string
	 * echo '<div' . dom::attr('class', $class) . '>';
	 * ```
	 * @example Boolean attribute
	 * ```php >> html
	 * echo '<input type="radio"' . dom::attr('checked', true) . '/>' . "\n";
	 * echo '<input type="radio"' . dom::attr('checked', false) . '/>';
	 * ```
	 * @param string $Name Attribute name
	 * @param mixed $Value Attribute value
	 * @param mixed $Default *(optional)* Default value of the attribute
	 * @return string DOM attribute expression
	 */
	static function attr($Name, $Value, $Default = null) {
		if (is_bool($Value)) return $Value ? " $Name=\"$Name\"" : '';
		if (!$Value && !is_numeric($Value)) return isset($Default) ? dom::attr($Name, $Default) : '';
		return " $Name=\"".htmlspecialchars($Value, ENT_QUOTES, null, false)."\"";
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
