<?php
/**
 * Section Separator element implementation, it extends DDElementTemplate like all other elements
 */
	class TF_SectionSeparator extends DDElementTemplate {
		public function __construct() {
			
			parent::__construct();
		} 
		
		// Implementation for the element structure.
		public function create_element_structure() {
			
			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] 		= get_class($this);
			// element id
			$this->config['id']	   		= 'section_separator';
			// element name
			$this->config['name']	 		= __('Section Separator', 'fusion-core');
			// element icon
			$this->config['icon_url']  		= "icons/sc-text_block.png";
			// css class related to this element
			$this->config['css_class'] 		= "fusion_element_box";
			// element icon class
			$this->config['icon_class']		= 'fusion-icon builder-options-icon fusiona-ellipsis';
			// tooltip that will be displyed upon mouse over the element
			//$this->config['tool_tip']  		= 'Creates a Separator Element';
			// any special html data attribute (i.e. data-width) needs to be passed
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] 			= array("drop_level"   => "4");
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {
			
			
			$innerHtml  = '<div class="fusion_iconbox textblock_element textblock_element_style">';
			$innerHtml .= '<div class="bilder_icon_container"><span class="fusion_iconbox_icon"><i class="fusiona-ellipsis"></i><sub class="sub">'.__('Section Separator', 'fusion-core').'</sub></span></div>';
			$innerHtml .= '</div>';
			$this->config['innerHtml'] = $innerHtml;
		}
		
		//this function defines TextBlock sub elements or structure
		function popup_elements() {
			$margin_data = FusionHelper::fusion_create_dropdown_data(1,100);
			$this->config['subElements'] = array(
			
			   array("name" 			=> __('Position of the Divider Candy', 'fusion-core'),
					  "desc" 			=> __('Select the position of the triangle candy', 'fusion-core'),
					  "id" 				=> "fusion_divider_candy",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues" 	=> array('top' 			=> __('Top', 'fusion-core'),
												 'bottom' 		=> __('Bottom', 'fusion-core'),
												 'bottom,top' 	=> __('Top and Bottom', 'fusion-core')) 
					 ),
				array("name" 			=> __('Select Icon', 'fusion-core'),
					  "desc" 			=> __('Click an icon to select, click again to deselect', 'fusion-core'),
					  "id" 				=> "icon",
					  "type" 			=> ElementTypeEnum::ICON_BOX,
					  "value" 			=> "",
					  "list"			=> FusionHelper::GET_ICONS_LIST()
					  ),
					  
				array("name" 			=> __('Icon Color', 'fusion-core'),
					  "desc" 			=> __('Leave blank for theme option selection.', 'fusion-core'),
					  "id" 				=> "fusion_iconcolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Border', 'fusion-core'),
					  "desc"			=> __('In pixels (px), ex: 1px. Leave blank for theme option selection.', 'fusion-core'),
					  "id" 				=> "fusion_border",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "1px" 
					  ),
					  
				array("name" 			=> __('Border Color', 'fusion-core'),
					  "desc" 			=> __('Controls the border color. Leave blank for theme option selection.', 'fusion-core'),
					  "id" 				=> "fusion_bordercolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Background Color of Divider Candy', 'fusion-core'),
					  "desc" 			=> __('Controls the background color of the triangle. Leave blank for theme option selection.', 'fusion-core'),
					  "id" 				=> "fusion_backgroundcolor",
					  "type" 			=> ElementTypeEnum::COLOR,
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