<?php
/**
 * Column Options implementation
 */
class ColumnOptions {
	private $value = array();
	private $elements = array();
	
	public function __construct() {
		
		$this->value['id'] 		= "Column_options_div";
		$this->value['name'] 	= __('Column Options', 'fusion-core');
		$this->value['icon'] 	= "icon_pack/tab_icon_1.png";
		$this->value['class']	= "fusion-tab fusiona-columns";
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
		//load all layout category elements
		
		$full_width			= new TF_FullWidthContainer();
		array_push( $this->elements, $full_width->element_to_array() );

		$grid_one 			= new TF_GridOne();
		array_push( $this->elements, $grid_one->element_to_array() );

		$grid_two 			= new TF_GridTwo();
		array_push( $this->elements, $grid_two->element_to_array() );
		
		$grid_three 		= new TF_GridThree();
		array_push( $this->elements, $grid_three->element_to_array() );
		
		$grid_two_third		= new TF_GridTwoThird();
		array_push( $this->elements, $grid_two_third->element_to_array() );
		
		$grid_four			= new TF_GridFour();
		array_push( $this->elements, $grid_four->element_to_array() );
		
		$grid_three_fourth	= new TF_GridThreeFourth();
		array_push( $this->elements, $grid_three_fourth->element_to_array() );
		
		$grid_five			= new TF_GridFive();
		array_push( $this->elements, $grid_five->element_to_array() );
		
		$grid_two_fifth		= new TF_GridTwoFifth();
		array_push( $this->elements, $grid_two_fifth->element_to_array() );
		
		$grid_three_fifth	= new TF_GridThreeFifth();
		array_push( $this->elements, $grid_three_fifth->element_to_array() );
		
		$grid_four_fifth	= new TF_GridFourFifth();
		array_push( $this->elements, $grid_four_fifth->element_to_array() );
		
		$grid_one_six		= new TF_GridSix();
		array_push( $this->elements, $grid_one_six->element_to_array() );
		
		$grid_five_six		= new TF_GridFiveSix();
		array_push( $this->elements, $grid_five_six->element_to_array() );
	   
	}  
}