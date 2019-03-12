<?php
require ('requires.php');
/**
 * Palette class which load all palette categories and thier elements
 */
class Palette {
	 
	private $categories = array();

	public function __construct() {
		$this->load_categories();
	}

	/**
	 * Load all categories
	 */
	public function load_categories() {
		$column_otpions 		= new ColumnOptions();
		array_push($this->categories, $column_otpions->to_array());
		
		$builder_elements 		= new BuilderElements();
		array_push($this->categories, $builder_elements->to_array());
		
		$custome_templates 		= new CustomTemplates();
		array_push($this->categories, $custome_templates->to_array());  
		
		$pre_built_templates 	= new PreBuiltTemplates();
		array_push($this->categories, $pre_built_templates->to_array());  
	}   

	/**
	 * Convert categories/elements palette array into JSON to be transfered to web page using Ajax
	 * @return type categories JSON
	 */
	public function to_JSON() {
		return json_encode($this->categories);
	}
}