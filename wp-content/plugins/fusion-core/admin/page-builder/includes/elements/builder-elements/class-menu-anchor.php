<?php
/**
 * Menu Anchor implementation, it extends DDElementTemplate like all other elements
 */
	class TF_MenuAnchor extends DDElementTemplate {
		public function __construct() {
			
			parent::__construct();
		} 
		
		// Implementation for the element structure.
		public function create_element_structure() {
			
			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] 		= get_class($this);
			// element id
			$this->config['id']	   		= 'menu_anchor';
			// element name
			$this->config['name']	 		= __('Menu Anchor', 'fusion-core');
			// element icon
			$this->config['icon_url']  		= "icons/sc-menu_anchor.png";
			// css class related to this element
			$this->config['css_class'] 		= "fusion_element_box";
			// element icon class
			$this->config['icon_class']		= 'fusion-icon builder-options-icon fusiona-anchor';
			// tooltip that will be displyed upon mouse over the element
			//$this->config['tool_tip']  		= 'Creates a simple text block';
			// any special html data attribute (i.e. data-width) needs to be passed
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] 			= array("drop_level"   => "4");
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {
			
			$innerHtml  = '<div class="fusion_iconbox textblock_element textblock_element_style">';
			$innerHtml .= '<div class="bilder_icon_container"><span class="fusion_iconbox_icon"><i class="fusiona-anchor"></i><sub class="sub">'.__('Menu Anchor', 'fusion-core').'</sub><p>menu anchor name = <span class="anchor_name">myAnchor</span></p></span></div>';
			$innerHtml .= '</div>';
			$this->config['innerHtml'] = $innerHtml;
		}
		
		//this function defines TextBlock sub elements or structure
		function popup_elements() {
			
			$this->config['subElements'] = array(
			
			   array("name" 			=> __('Name Of Menu Anchor', 'fusion-core'),
					  "desc"			=> __('This name will be the id you will have to use in your one page menu.', 'fusion-core'),
					  "id" 				=> "fusion_name",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
					  ),
					  
				
				);
		}
	}