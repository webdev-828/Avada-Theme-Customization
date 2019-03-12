<?php
/**
 * LightBox element implementation, it extends DDElementTemplate like all other elements
 */
	class TF_LightBox extends DDElementTemplate {
		public function __construct() {
			
			parent::__construct();
		} 
		
		// Implementation for the element structure.
		public function create_element_structure() {
			
			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] 		= get_class($this);
			// element id
			$this->config['id']	   		= 'light_box';
			// element name
			$this->config['name']	 		= __('Lightbox', 'fusion-core');
			// element icon
			$this->config['icon_url']  		= "icons/sc-text_block.png";
			// css class related to this element
			$this->config['css_class'] 		= "fusion_element_box";
			// element icon class
			$this->config['icon_class']		= 'fusion-icon builder-options-icon  fusiona-uniF602';
			// tooltip that will be displyed upon mouse over the element
			//$this->config['tool_tip']  		= 'Creates Lightbox Element';
			// any special html data attribute (i.e. data-width) needs to be passed
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] 			= array("drop_level"   => "4");
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {
			
			$innerHtml  = '<div class="fusion_iconbox textblock_element textblock_element_style" id="fusion_light_box">';
			$innerHtml .= '<div class="bilder_icon_container"><span class="fusion_iconbox_icon"><i class="fusiona-uniF602"></i><sub class="sub">'.__('Lightbox', 'fusion-core').'</sub><div class="img_frame_section">Image here</div></span></div>';
			$innerHtml .= '</div>';
			$this->config['innerHtml'] = $innerHtml;
		}
		
		//this function defines TextBlock sub elements or structure
		function popup_elements() {
			$this->config['subElements'] = array(
			
				array("name" 			=> __('Full Image', 'fusion-core'),
					  "desc" 			=> __('Upload an image that will show up in the lightbox.', 'fusion-core'),
					  "id" 				=> "fusion_full_image",
					  "upid" 			=> "1",
					  "type" 			=> ElementTypeEnum::UPLOAD,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Thumbnail Image', 'fusion-core'),
					  "desc" 			=> __('Clicking this image will show lightbox.', 'fusion-core'),
					  "id" 				=> "fusion_thumb_image",
					  "upid" 			=> "2",
					  "type" 			=> ElementTypeEnum::UPLOAD,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Alt Text', 'fusion-core'),
					  "desc"			=> __('The alt attribute provides alternative information if an image cannot be viewed.', 'fusion-core'),
					  "id" 				=> "fusion_alt",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
					  ),
					  
				array("name" 			=> __('Lightbox Title', 'fusion-core'),
					  "desc"			=> __('This will show up in the lightbox as a title above the image.', 'fusion-core'),
					  "id" 				=> "fusion_title",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
					  ),
					  
				array("name" 			=> __('Lightbox Caption', 'fusion-core'),
					  "desc"			=> __('This will show up in the lightbox as a caption below the image.', 'fusion-core'),
					  "id" 				=> "fusion_caption",
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