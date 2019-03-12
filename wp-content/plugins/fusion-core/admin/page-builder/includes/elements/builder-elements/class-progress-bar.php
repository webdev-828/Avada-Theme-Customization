<?php
/**
 * ProgressBar implementation, it extends DDElementTemplate like all other elements
 */
	class TF_ProgressBar extends DDElementTemplate {
		public function __construct() {
			
			parent::__construct();
		} 
		
		// Implementation for the element structure.
		public function create_element_structure() {
			
			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] 		= get_class($this);
			// element id
			$this->config['id']	   		= 'progress_bar';
			// element name
			$this->config['name']	 		= __('Progress Bar', 'fusion-core');
			// element icon
			$this->config['icon_url']  		= "icons/sc-text_block.png";
			// css class related to this element
			$this->config['css_class'] 		= "fusion_element_box";
			// element icon class
			$this->config['icon_class']		= 'fusion-icon builder-options-icon fusiona-tasks';
			// tooltip that will be displyed upon mouse over the element
			//$this->config['tool_tip']  		= 'Creates a Prcing Bar';
			// any special html data attribute (i.e. data-width) needs to be passed
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] 			= array("drop_level"   => "4");
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {
			
			$innerHtml  = '<div class="fusion_iconbox textblock_element textblock_element_style" id="fusion_progress_bar">';
			$innerHtml .= '<div class="bilder_icon_container"><span class="fusion_iconbox_icon"><i class="fusiona-tasks"></i><sub class="sub">'.__('Progress Bar', 'fusion-core').'</sub><p class="progress_bar_text">HTML Skills</p></span></div>';
			$innerHtml .= '</div>';
			$this->config['innerHtml'] = $innerHtml;
		}
		
		//this function defines TextBlock sub elements or structure
		function popup_elements() {
			
			$filled_area 				= FusionHelper::fusion_create_dropdown_data( 1, 100 );
			$choices 					= FusionHelper::get_shortcode_choices();
			$reverse_choices			= FusionHelper::get_reversed_choice_data();
			
			$this->config['subElements'] = array(
			
				array("name" 			=> __( 'Progress Bar Height', 'fusion-core' ),
					  "desc"			=> __( 'Insert a height for the progress bar. Enter value including any valid CSS unit, ex: 10px. Leave blank for theme option selection.', 'fusion-core' ),
					  "id" 				=> "fusion_height",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
					  ),			
			
				array("name" 			=> __( 'Text Position', 'fusion-core' ),
					  "desc" 			=> __( 'Select the position of the progress bar text. Choose "Default" for theme option selection.', 'fusion-core' ),
					  "id" 				=> "fusion_text_position",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues" 	=> array(
					  						''          => __( 'Default', 'fusion-core' ),					  
											'on_bar'    => __( 'On Bar', 'fusion-core' ),
											'above_bar' => __( 'Above Bar', 'fusion-core' ),
											'below_bar' => __( 'Below Bar', 'fusion-core' )
											)
					  ),			
			
				array("name" 			=> __('Filled Area Percentage', 'fusion-core'),
					  "desc" 			=> __('From 1% to 100%', 'fusion-core'),
					  "id" 				=> "fusion_value",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "1",
					  "allowedValues" 	=> $filled_area 
					  ),
					  
				array("name" 			=> __('Display Percentage Value', 'fusion-core'),
					  "desc" 			=> __('Select if you want the filled area percentage value to be shown.', 'fusion-core'),
					  "id" 				=> "fusion_display_value",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues" 	=> $choices
					  ),					  
					  
				array("name" 			=> __('Progress Bar Unit', 'fusion-core'),
					  "desc"			=> __('Insert a unit for the progress bar. ex %', 'fusion-core'),
					  "id" 				=> "fusion_unit",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
					  ),
					  
				array("name" 			=> __('Filled Color', 'fusion-core'),
					  "desc" 			=> __('Controls the color of the filled in area. Leave blank for theme option selection.', 'fusion-core'),
					  "id" 				=> "fusion_filledcolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),

				array("name" 			=> __('Filled Border Color', 'fusion-core'),
					  "desc" 			=> __('Controls the border color of the filled in area. Leave blank for theme option selection.', 'fusion-core'),
					  "id" 				=> "fusion_filledbordercolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),

				array("name" 			=> __('Filled Border Size', 'fusion-core'),
					  "desc"			=> __('In pixels (px), ex: 1px. Leave blank for theme option selection.', 'fusion-core'),
					  "id" 				=> "fusion_filledbordersize",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> ""
					  ),

				array("name" 			=> __('Unfilled Color', 'fusion-core'),
					  "desc" 			=> __('Controls the color of the unfilled in area. Leave blank for theme option selection.', 'fusion-core'),
					  "id" 				=> "fusion_unfilledcolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Striped Filling', 'fusion-core'),
					  "desc" 			=> __('Choose to get the filled area striped.', 'fusion-core'),
					  "id" 				=> "fusion_striped",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "no",
					  "allowedValues" 	=> $reverse_choices 
					  ),
					  
				array("name" 			=> __('Animated Stripes', 'fusion-core'),
					  "desc" 			=> __('Choose to get the the stripes animated.', 'fusion-core'),
					  "id" 				=> "fusion_animatedstripes",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "no",
					  "allowedValues" 	=> $reverse_choices 
					  ),
					  
				array("name" 			=> __('Text Color', 'fusion-core'),
					  "desc" 			=> __('Controls the text color. Leave blank for theme option selection.', 'fusion-core'),
					  "id" 				=> "fusion_textcolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),
				
				array("name" 			=> __('Progess Bar Text', 'fusion-core'),
					  "desc"			=> __('Text will show up on progess bar', 'fusion-core'),
					  "id" 				=> "fusion_content",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
					  ),
					  
				array("name" 			=> __('CSS Class', 'fusion-core'),
					  "desc"			=> __('Add a class to the wrapping HTML element.', 'fusion-core'),
					  "id" 				=> "fusion_class",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
					  ),
					  
				array("name" 			=> __('CSS ID', 'fusion-core'),
					  "desc"			=> __('Add an ID to the wrapping HTML element.', 'fusion-core'),
					  "id" 				=> "fusion_id",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
					  ),

					  
				);
		}
	}