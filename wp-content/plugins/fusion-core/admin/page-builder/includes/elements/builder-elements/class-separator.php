<?php
/**
 * Separator element implementation, it extends DDElementTemplate like all other elements
 */
	class TF_Separator extends DDElementTemplate {
		public function __construct() {
			
			parent::__construct();
		} 
		
		// Implementation for the element structure.
		public function create_element_structure() {
			
			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] 		= get_class($this);
			// element id
			$this->config['id']	   		= 'separator_element';
			// element name
			$this->config['name']	 		= __('Separator', 'fusion-core');
			// element icon
			$this->config['icon_url']  		= "icons/sc-text_block.png";
			// css class related to this element
			$this->config['css_class'] 		= "fusion_element_box";
			// element icon class
			$this->config['icon_class']		= 'fusion-icon builder-options-icon fusiona-minus';
			// tooltip that will be displyed upon mous over the element
			//$this->config['tool_tip']  		= 'Creates a Separator Element';
			// any special html data attribute (i.e. data-width) needs to be passed
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] 			= array("drop_level"   => "4");
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {
			
			
			$innerHtml  = '<div class="fusion_iconbox textblock_element textblock_element_style" id="fusion_seprator">';
			$innerHtml .= '<div class="bilder_icon_container"><span class="fusion_iconbox_icon"><span class="upper_container" style="selector:spattrib"><i class="fusiona-minus"></i><sub class="sub">'.__('Separator', 'fusion-core').'</sub></span><section class="separator double_dotted" style="selector:sattrib"><i class="fake_class" style="selector:iattrib"></i></section></span></div>';
			$innerHtml .= '</div>';
			$this->config['innerHtml'] = $innerHtml;
		}
		
		//this function defines TextBlock sub elements or structure
		function popup_elements() {
			$margin_data = FusionHelper::fusion_create_dropdown_data(1,100);
			$choices = FusionHelper::get_shortcode_choices_with_default();
			$this->config['subElements'] = array(
			
			   array("name" 			=> __('Style', 'fusion-core'),
					  "desc" 			=> __('Choose the separator line style', 'fusion-core'),
					  "id" 				=> "fusion_style",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "none",
					  "allowedValues" 	=> array(		'none' => __('No Style', 'fusion-core'),
		'single' => __('Single Border Solid', 'fusion-core'),
		'double' => __('Double Border Solid', 'fusion-core'),
		'single|dashed' => __('Single Border Dashed', 'fusion-core'),
		'double|dashed' => __('Double Border Dashed', 'fusion-core'),
		'single|dotted' => __('Single Border Dotted', 'fusion-core'),
		'double|dotted' => __('Double Border Dotted', 'fusion-core'),
		'shadow' => __('Shadow', 'fusion-core')) 
					 ),
				
				array("name" 			=> __('Margin Top', 'fusion-core'),
					  "desc"			=> __('Spacing above the separator. In pixels or percentage, ex: 10px or 10%.', 'fusion-core'),
					  "id" 				=> "fusion_top",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" ,
					  ),
					  
				array("name" 			=> __('Margin Bottom', 'fusion-core'),
					  "desc"			=> __('Spacing below the separator. In pixels or percentage, ex: 10px or 10%.', 'fusion-core'),
					  "id" 				=> "fusion_bottom",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" ,
					  ),
					  
				array("name" 			=> __('Separator Color', 'fusion-core'),
					  "desc" 			=> __('Controls the separator color. Leave blank for theme option selection.', 'fusion-core'),
					  "id" 				=> "fusion_sepcolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),

				array("name" 			=> __('Border Size', 'fusion-core'),
					  "desc"			=> __('In pixels (px), ex: 1px. Leave blank for theme option selection.', 'fusion-core'),
					  "id" 				=> "fusion_border_size",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" ,
					  ),
					  
				array("name" 			=> __('Select Icon', 'fusion-core'),
					  "desc" 			=> __('Click an icon to select, click again to deselect', 'fusion-core'),
					  "id" 				=> "icon",
					  "type" 			=> ElementTypeEnum::ICON_BOX,
					  "value" 			=> "",
					  "list"			=> FusionHelper::GET_ICONS_LIST()
					  ),
					  
				array("name" 			=> __('Circled Icon', 'fusion-core'),
					  "desc" 			=> __('Choose to have a circle in separator color around the icon.', 'fusion-core'),
					  "id" 				=> "fusion_circle",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues" 	=> $choices
					  ),	
					  
				array("name" 			=> __('Circle Color', 'fusion-core'),
					  "desc" 			=> __('Controls the background color of the circle around the icon.', 'fusion-core'),
					  "id" 				=> "fusion_circlecolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),					  
					  
				array("name" 			=> __('Separator Width', 'fusion-core'),
					  "desc"			=> __('In pixels (px or %), ex: 1px, ex: 50%. Leave blank for full width.', 'fusion-core'),
					  "id" 				=> "fusion_width",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
					  ),
					  
				array("name" 			=> __('Alignment', 'fusion-core'),
					  "desc" 			=> __('Select the separator alignment; only works when a width is specified.', 'fusion-core'),
					  "id" 				=> "fusion_alignment",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues" 	=> array('center' 	=> __('Center', 'fusion-core'),
					  							 'left' 	=> __('Left', 'fusion-core'),
												 'right' 	=> __('Right', 'fusion-core'))
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