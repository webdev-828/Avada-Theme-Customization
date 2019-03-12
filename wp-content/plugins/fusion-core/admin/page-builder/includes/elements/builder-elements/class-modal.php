<?php
/**
 * Modal implementation, it extends DDElementTemplate like all other elements
 */
	class TF_Modal extends DDElementTemplate {
		public function __construct() {
			
			parent::__construct();
		} 
		
		// Implementation for the element structure.
		public function create_element_structure() {
			
			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] 		= get_class($this);
			// element id
			$this->config['id']	   		= 'elemenet_modal';
			// element name
			$this->config['name']	 		= __('Modal', 'fusion-core');
			// element icon
			$this->config['icon_url']  		= "icons/sc-modal.png";
			// css class related to this element
			$this->config['css_class'] 		= "fusion_element_box";
			// element icon class
			$this->config['icon_class']		= 'fusion-icon builder-options-icon fusiona-external-link';
			// tooltip that will be displyed upon mouse over the element
			//$this->config['tool_tip']  		= 'Creates a simple text block';
			// any special html data attribute (i.e. data-width) needs to be passed
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] 			= array("drop_level"   => "4");
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {
			
			$innerHtml  = '<div class="fusion_iconbox textblock_element textblock_element_style" id="fusion_modal">';
			$innerHtml .= '<div class="bilder_icon_container"><span class="fusion_iconbox_icon"><i class="fusiona-external-link"></i><sub class="sub">'.__('Modal', 'fusion-core').'</sub><p>modal name = <span class="modal_name">myModal</span></p></span></div>';
			$innerHtml .= '</div>';
			$this->config['innerHtml'] = $innerHtml;
		}
		
		//this function defines TextBlock sub elements or structure
		function popup_elements() {
			
			$choices					= FusionHelper::get_shortcode_choices();
			
			$this->config['subElements'] = array(
			
			   array("name" 			=> __('Name Of Modal', 'fusion-core'),
					  "desc"			=> __('Needs to be a unique identifier (lowercase), used for button or modal_text_link shortcode to open the modal. ex: mymodal', 'fusion-core'),
					  "id" 				=> "fusion_name",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
					  ),
					  
				array("name" 			=> __('Modal Heading', 'fusion-core'),
					  "desc"			=> __('Heading text for the modal.', 'fusion-core'),
					  "id" 				=> "fusion_title",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
					  ),
					  
				array("name" 			=> __('Size Of Modal', 'fusion-core'),
					  "desc" 			=> __('Select the modal window size.', 'fusion-core'),
					  "id" 				=> "fusion_size",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues" 	=> array('small' 			=> __('Small', 'fusion-core'),
												 'large' 			=> __('Large', 'fusion-core')
												 ) 
					  ),
					  
				array("name" 			=> __('Background Color', 'fusion-core'),
					  "desc" 			=> __('Controls the modal background color. Leave blank for theme option selection.', 'fusion-core'),
					  "id" 				=> "fusion_background",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Border Color', 'fusion-core'),
					  "desc" 			=> __('Controls the modal border color. Leave blank for theme option selection.', 'fusion-core'),
					  "id" 				=> "fusion_bordercolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Show Footer', 'fusion-core'),
					  "desc" 			=> __('Choose to show the modal footer with close button.', 'fusion-core'),
					  "id" 				=> "fusion_showfooter",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues" 	=> $choices 
					  ),
					  
				array("name" 			=> __('Contents of Modal', 'fusion-core'),
					  "desc"			=> __('Add your content to be displayed in modal.', 'fusion-core'),
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