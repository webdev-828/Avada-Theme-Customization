<?php
/**
 * SoundCloud element implementation, it extends DDElementTemplate like all other elements
 */
	class TF_SoundCloud extends DDElementTemplate {
		public function __construct() {
			
			parent::__construct();
		} 
		
		// Implementation for the element structure.
		public function create_element_structure() {
			
			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] 		= get_class($this);
			// element id
			$this->config['id']	   		= 'sound_cloud';
			// element name
			$this->config['name']	 		= __('Soundcloud', 'fusion-core');
			// element icon
			$this->config['icon_url']  		= "icons/sc-text_block.png";
			// css class related to this element
			$this->config['css_class'] 		= "fusion_element_box";
			// element icon class
			$this->config['icon_class']		= 'fusion-icon builder-options-icon fusiona-soundcloud';
			// tooltip that will be displyed upon mouse over the element
			//$this->config['tool_tip']  		= 'Creates a Soundcloud Element';
			// any special html data attribute (i.e. data-width) needs to be passed
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] 			= array("drop_level"   => "4");
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {
			
			$innerHtml  = '<div class="fusion_iconbox textblock_element textblock_element_style" id="fusion_soundcloud">';
			$innerHtml .= '<div class="bilder_icon_container"><span class="fusion_iconbox_icon"><i class="fusiona-soundcloud"></i><sub class="sub">'.__('Soundcloud', 'fusion-core').'</sub><p class="soundcloud_url">Soundcloud URL here</p></span></div>';
			$innerHtml .= '</div>';
			$this->config['innerHtml'] = $innerHtml;
		}
		
		//this function defines TextBlock sub elements or structure
		function popup_elements() {
			
			$choices					= FusionHelper::get_shortcode_choices();
			$reverse_choices			= FusionHelper::get_reversed_choice_data();
			
			$this->config['subElements'] = array(
			
				array("name" 			=> __('SoundCloud Url', 'fusion-core'),
					  "desc"			=> __('The SoundCloud url, ex: http://api.soundcloud.com/tracks/110813479', 'fusion-core'),
					  "id" 				=> "fusion_url",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
					  ),			
					  
				array("name" 			=> __('Layout', 'fusion-core'),
					  "desc" 			=> __('Choose the layout of the soundcloud embed.', 'fusion-core'),
					  "id" 				=> "fusion_layout",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "yes",
					  "allowedValues" 	=> array( 'classic' => 'Classic', 'visual' => 'Visual' )
					  ),					  
					  
				array("name" 			=> __('Show Comments', 'fusion-core'),
					  "desc" 			=> __('Choose to display comments.', 'fusion-core'),
					  "id" 				=> "fusion_comments",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "yes",
					  "allowedValues" 	=> $choices 
					  ),
					  
				array("name" 			=> __('Show Related', 'fusion-core'),
					  "desc" 			=> __('Choose to display related items.', 'fusion-core'),
					  "id" 				=> "fusion_related",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "yes",
					  "allowedValues" 	=> $choices 
					  ),
					  
				array("name" 			=> __('Show User', 'fusion-core'),
					  "desc" 			=> __('Choose to display the user who posted the item.', 'fusion-core'),
					  "id" 				=> "fusion_user",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "yes",
					  "allowedValues" 	=> $choices 
					  ),					  
					  
				array("name" 			=> __('Autoplay', 'fusion-core'),
					  "desc" 			=> __('Choose to autoplay the track', 'fusion-core'),
					  "id" 				=> "fusion_auto_play",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "no",
					  "allowedValues" 	=> $reverse_choices 
					  ),
				
				array("name" 			=> __('Color', 'fusion-core'),
					  "desc" 			=> __('Select the color of the shortcode', 'fusion-core'),
					  "id" 				=> "fusion_color",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> "#ff7700"
					  ),
					  
				array("name" 			=> __('Width', 'fusion-core'),
					  "desc"			=> __('In pixels (px) or percentage (%)', 'fusion-core'),
					  "id" 				=> "fusion_width",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "100%" 
					  ),
					  
				array("name" 			=> __('Height', 'fusion-core'),
					  "desc"			=> __('In pixels (px)', 'fusion-core'),
					  "id" 				=> "fusion_height",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "150px" 
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