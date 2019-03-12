<?php
/**
 * Enum class represent all possible element types.
 * You can add more types are and implement its logic in class-dd-element-template.php
 */
final class ElementTypeEnum {
	
	private function __construct() {} // huh, empty constructor. 
	
	const INPUT			= "input";
	const COLOR			= "color";
	const HIDDEN		= "hidden";
	const SELECT		= "select";
	const RADIO			= "radio";
	const CHECKBOX		= "checkbox";
	const TEXTAREA		= "textarea";
	const HTML_EDITOR	= "html_editor";
	const ICON_BOX		= "icon_box";
	const MULTI			= "multiselect";
	const PARAGRAPH 	= "paragraph";
	const UPLOAD		= "upload";
	const GALLERY		= "gallery";
	const ADDMORE		= "addmore";
}


