<?php

function lsGetInput($default, $current, $attrs = array()) {

	// Markup
	$el = phpQuery::newDocumentHTML('<input>')->children();
	$type = is_string($default['value']) ? 'text' : 'number';
	$name = is_string($default['keys']) ? $default['keys'] : $default['keys'][0];
	$el->attr('type', $type);
	$el->attr('name', $name);
	$el->val($default['value']);

	// Attributes
	$attrs = isset($default['attrs']) ? array_merge($default['attrs'], $attrs) : $attrs;
	if(isset($attrs) && is_array($attrs)) {
		foreach($attrs as $attr => $val) {
			$el->attr($attr, $val);
		}
	}

	// Tooltip
	if(isset($default['tooltip'])) {
		$el->attr('data-help', $default['tooltip']);
	}

	// Override the default
	if(isset($current[$name]) && $current[$name] !== '') {
		$el->val(stripslashes($current[$name]));
	}

	echo $el;
}

function lsGetCheckbox($default, $current, $attrs = array()) {

	// Markup
	$el = phpQuery::newDocumentHTML('<input>')->children();
	$name = is_string($default['keys']) ? $default['keys'] : $default['keys'][0];
	$el->attr('type', 'checkbox');
	$el->attr('name', $name);

	// Attributes
	$attrs = isset($default['attrs']) ? array_merge($default['attrs'], $attrs) : $attrs;
	if(isset($attrs) && is_array($attrs)) {
		foreach($attrs as $attr => $val) {
			$el->attr($attr, $val);
		}
	}

	// Checked?
	if($default['value'] === true && count($current) < 3) {
		$el->attr('checked', 'checked');
	} elseif(isset($current[$name]) && $current[$name] != false && $current[$name] !== 'false') {
		$el->attr('checked', 'checked');
	}

	echo $el;
}

function lsGetSelect($default, $current, $attrs = array()) {

	// Var to hold data to print
	$el = phpQuery::newDocumentHTML('<select>')->children();
	$name = is_string($default['keys']) ? $default['keys'] : $default['keys'][0];
	$el->attr('name', $name);
	$value = $default['value'];
	$options = array();

	// Attributes
	$attrs = isset($default['attrs']) ? array_merge($default['attrs'], $attrs) : $attrs;
	if(isset($attrs) && is_array($attrs)) {
		foreach($attrs as $attr => $val) {
			if(!is_array($val)) {
				$el->attr($attr, $val);
			}
		}
	}

	// Get options
	if(isset($default['options']) && is_array($default['options'])) {
		$options = $default['options'];
	} elseif(isset($attrs['options']) && is_array($attrs['options'])) {
		$options = $attrs['options'];
	}

	// Override the default
	if(isset($current[$name]) && $current[$name] !== '') {
		$value = $current[$name];
	}

	// Tooltip
	if(isset($default['tooltip'])) {
		$el->attr('data-help', $default['tooltip']);
	}

	// Add options
	foreach($options as $name => $val) {
		$name = is_string($name) ? $name : $val;
		$checked = ($name == $value) ? ' selected="selected"' : '';
		$el->append("<option value=\"$name\" $checked>$val</option>");
	}

	echo $el;
}

?>
