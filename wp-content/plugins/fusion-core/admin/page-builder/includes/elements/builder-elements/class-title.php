<?php
/**
 * Title element implementation, it extends DDElementTemplate like all other elements
 */
	class TF_Title extends DDElementTemplate {
		public function __construct() {
			
			parent::__construct();
		} 
		
		// Implementation for the element structure.
		public function create_element_structure() {
			
			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] 		= get_class($this);
			// element id
			$this->config['id']	   		= 'title_box';
			// element name
			$this->config['name']	 		= __('Title', 'fusion-core');
			// element icon
			$this->config['icon_url']  		= "icons/sc-text_block.png";
			// css class related to this element
			$this->config['css_class'] 		= "fusion_element_box";
			// element icon class
			$this->config['icon_class']		= 'fusion-icon builder-options-icon fusiona-H';
			// tooltip that will be displyed upon mouse over the element
			//$this->config['tool_tip']  		= 'Creates a Title Element';
			// any special html data attribute (i.e. data-width) needs to be passed
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] 			= array("drop_level"   => "4");
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {
			
			
			$innerHtml  = '<div class="fusion_iconbox textblock_element textblock_element_style" id="fusian_title">';
			$innerHtml .= '<div class="bilder_icon_container"><span class="fusion_iconbox_icon"><section class="double_dotted" ><div class="fusion-title-border"></div><sub class="title_text align_right">'.__('Title', 'fusion-core').'</sub></section></span></div>';
			$innerHtml .= '</div>';
			$this->config['innerHtml'] = $innerHtml;
		}
		
		//this function defines TextBlock sub elements or structure
		function popup_elements() {
			$title_data = FusionHelper::fusion_create_dropdown_data(1, 6);
			$this->config['subElements'] = array(
			
				array("name" 			=> __('Title Size', 'fusion-core'),
					  "desc" 			=> __('Choose the title size, H1-H6', 'fusion-core'),
					  "id" 				=> "fusion_size",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "1",
					  "allowedValues" 	=> $title_data
					  ),
					  
				array("name" 			=> __('Title Alignment', 'fusion-core'),
					  "desc" 			=> __('Choose to align the heading left or right.', 'fusion-core'),
					  "id" 				=> "fusion_contentalign",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "left",
					  "allowedValues" 	=> array('left' 		=> __('Left', 'fusion-core'),
					  							 'center' 		=> __('Center', 'fusion-core'),
											   'right' 			=> __('Right', 'fusion-core')) 
					 ),
					 
				array("name" 			=> __('Separator', 'fusion-core'),
					  "desc" 			=> __('Choose the kind of the title separator you want to use.', 'fusion-core'),
					  "id" 				=> "fusion_style_type",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues" 	=> array(
					  	'default'		  => __('Default', 'fusion-core'),
						'single'		  => __('Single', 'fusion-core'),
						'single solid'	=> __('Single Solid', 'fusion-core'),
						'single dashed'	=> __('Single Dashed', 'fusion-core'),
						'single dotted'	=> __('Single Dotted', 'fusion-core'),
						'double'	 => __('Double', 'fusion-core'),
						 'double solid'	 => __('Double Solid', 'fusion-core'),
						 'double dashed'	 => __('Double Dashed', 'fusion-core'),
						 'double dotted'	 => __('Double Dotted', 'fusion-core'),
						 'underline'	=> __('Underline', 'fusion-core'),
											   'underline solid'		=> __('Underline Solid', 'fusion-core'),
						 'underline dashed'	=> __('Underline Dashed', 'fusion-core'),
						 'underline dotted'	=> __('Underline Dotted', 'fusion-core'),
						 'none'	=> __('None', 'fusion-core'))
					 ),
					 
					 
				array("name" 			=> __('Separator Color', 'fusion-core'),
					  "desc" 			=> __('Controls the separator color. Leave blank for theme option selection.', 'fusion-core'),
					  "id" 				=> "fusion_sepcolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Top Margin', 'fusion-core'),
					  "desc"			=> __('Spacing above the title. In px or em, e.g. 10px.', 'fusion-core'),
					  "id" 				=> "fusion_margin_top",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
					  ),
					  
				array("name" 			=> __('Bottom Margin', 'fusion-core'),
					  "desc"			=> __('Spacing below the title. In px or em, e.g. 10px.', 'fusion-core'),
					  "id" 				=> "fusion_margin_bottom",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
					  ),					  						  
					  
			   array("name" 			=> __('Title', 'fusion-core'),
					  "desc"			=> __('Insert the title text', 'fusion-core'),
					  "id" 				=> "fusion_content_wp",
					  "type" 			=> ElementTypeEnum::HTML_EDITOR,
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