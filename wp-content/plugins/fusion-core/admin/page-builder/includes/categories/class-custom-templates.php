<?php
/**
 * Custom Templates implementation
 */
class CustomTemplates {
	private $value = array();
	private $elements = array();
	
	public function __construct() {
		
		$this->value['id'] 		= "custom_templates_div";
		$this->value['name'] 	= __('Custom Templates', 'fusion-core');
		$this->value['icon'] 	= "icon_pack/tab_icon_3.png";
		$this->value['class']	= "fusion-tab fusiona-file-alt";
		$this->load_elements();
	}
	
	public function to_array() {
		
		$this->value['elements'] = $this->elements;
		return $this->value;
	}
	
	/**
	 * Load all the category's elements
	 */
	private function load_elements() {
	   
	}  
}