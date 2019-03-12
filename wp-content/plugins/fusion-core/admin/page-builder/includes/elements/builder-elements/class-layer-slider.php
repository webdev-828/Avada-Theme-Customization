<?php
/**
 * LayerSlider implementation, it extends DDElementTemplate like all other elements
 */
	class TF_LayerSlider extends DDElementTemplate {
		public function __construct() {
			
			parent::__construct();
		} 
		
		// Implementation for the element structure.
		public function create_element_structure() {
			
			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] 		= get_class($this);
			// element id
			$this->config['id']	   		= 'layer_sldier';
			// element name
			$this->config['name']	 		= __('Layer Slider', 'fusion-core');
			// element icon
			$this->config['icon_url']  		= "icons/sc-text_block.png";
			// css class related to this element
			$this->config['css_class'] 		= "fusion_element_box";
			// element icon class
			$this->config['icon_class']		= 'fusion-icon builder-options-icon fusiona-stack';
			// tooltip that will be displyed upon mouse over the element
			//$this->config['tool_tip']  		= 'Creates Elastic Slider';
			// any special html data attribute (i.e. data-width) needs to be passed
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] 			= array("drop_level"   => "4");
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {
			
			
			$innerHtml  = '<div class="fusion_iconbox textblock_element textblock_element_style" id="fusion_layer_slider">';
			$innerHtml .= '<div class="bilder_icon_container"><span class="fusion_iconbox_icon"><i class="fusiona-stack"></i><sub class="sub">'.__('Layer Slider', 'fusion-core').'</sub><p>[layerslider id="<span class="layer_slider_id">106</span>"]</p></span></div>';
			$innerHtml .= '</div>';
			$this->config['innerHtml'] = $innerHtml;
		}
		
		//this function defines TextBlock sub elements or structure
		function popup_elements() {
			$slider_options 	= FusionHelper::get_layerslider_slides( );
			$this->config['subElements'] = array(
				array("name" 			=> __('Select Slider', 'fusion-core'),
					  "desc" 			=> __('Select a slider group', 'fusion-core'),
					  "id" 				=> "fusion_slider",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues" 	=> $slider_options
				),
					  
				);
		}
	}