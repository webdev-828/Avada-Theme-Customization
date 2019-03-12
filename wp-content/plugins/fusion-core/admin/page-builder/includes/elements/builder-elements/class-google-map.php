<?php
/**
 * GoogleMap implementation, it extends DDElementTemplate like all other elements
 */
	class TF_GoogleMap extends DDElementTemplate {
		public function __construct() {
			
			parent::__construct();
		} 
		
		// Implementation for the element structure.
		public function create_element_structure() {
			
			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] 		= get_class($this);
			// element id
			$this->config['id']	   		= 'google_map';
			// element name
			$this->config['name']	 		= __('Google Map', 'fusion-core');
			// element icon
			$this->config['icon_url']  		= "icons/sc-text_block.png";
			// css class related to this element
			$this->config['css_class'] 		= "fusion_element_box";
			// element icon class
			$this->config['icon_class']		= 'fusion-icon builder-options-icon fusiona-map fusion_has_colorpicker';
			// tooltip that will be displyed upon mouse over the element
			//$this->config['tool_tip']  		= 'Creates a Google Map Element';
			// any special html data attribute (i.e. data-width) needs to be passed
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] 			= array("drop_level"   => "4");
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {
			
			$innerHtml  = '<div class="fusion_iconbox textblock_element textblock_element_style" id="fusion_google_map">';
			$innerHtml .= '<div class="bilder_icon_container"><span class="fusion_iconbox_icon"><i class="fusiona-map"></i><sub class="sub">'.__('Google Map', 'fusion-core').'</sub><p class="google_map_address">12345 West Elm Street, New York City ,NY 33544</p></span></div>';
			$innerHtml .= '</div>';
			$this->config['innerHtml'] = $innerHtml;
		}
	
		//this function defines TextBlock sub elements or structure
		function popup_elements() {
			
			$zoom_levels 				= FusionHelper::fusion_create_dropdown_data( 1, 25 );
			$choices					= FusionHelper::get_shortcode_choices();
			
			$this->config['subElements'] = array(
			
				array("name" 			=> __('Map Type', 'fusion-core'),
					  "desc" 			=> __('Select the type of google map to display', 'fusion-core'),
					  "id" 				=> "fusion_type",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "roadmap",
					  "allowedValues" 	=> array('roadmap' 		=>__('Roadmap', 'fusion-core'),
												 'satellite' 	=>__('Satellite', 'fusion-core'),
												 'hybrid' 		=> __('Hybrid', 'fusion-core'),
												 'terrain' 		=> __('Terrain', 'fusion-core'))
					  ),
											   
				array("name" 			=> __('Map Width', 'fusion-core'),
					  "desc" 			=> __('Map width in percentage or pixels. ex: 100%, or 940px', 'fusion-core'),
					  "id" 				=> "fusion_width",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "100%"
					  ),
				
				array("name" 			=> __('Map Height', 'fusion-core'),
					  "desc" 			=> __('Map height in pixels. ex: 300px', 'fusion-core'),
					  "id" 				=> "fusion_height",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "300px"
					  ),
					  
				array("name" 			=> __('Zoom Level', 'fusion-core'),
					  "desc" 			=> __('Higher number will be more zoomed in.', 'fusion-core'),
					  "id" 				=> "fusion_zoom",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "14",
					  "allowedValues" 	=> $zoom_levels
					 ),
				
				array("name" 			=> __('Scrollwheel on Map', 'fusion-core'),
					  "desc" 			=> __('Enable zooming using a mouse\'s scroll wheel', 'fusion-core'),
					  "id" 				=> "fusion_scrollwheel",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "yes",
					  "allowedValues" 	=> $choices 
					 ),
				
				array("name" 			=> __('Show Scale Control on Map', 'fusion-core'),
					  "desc"			=> __('Display the map scale', 'fusion-core'),
					  "id" 				=> "fusion_scale",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "yes" ,
					  "allowedValues" 	=> $choices 
					  ),
					  
				array("name" 			=> __('Show Pan Control on Map', 'fusion-core'),
					  "desc"			=> __('Displays pan control button', 'fusion-core'),
					  "id" 				=> "fusion_zoom_pancontrol",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "yes" ,
					  "allowedValues" 	=> $choices 
					  ),

				array("name" 			=> __('Address Pin Animation', 'fusion-core'),
					  "desc"			=> __('Choose to animate the address pins when the map first loads.', 'fusion-core'),
					  "id" 				=> "fusion_animation",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "yes" ,
					  "allowedValues" 	=> $choices 
					  ),

				array("name" 			=> __('Show tooltip by default', 'fusion-core'),
					  "desc"			=> __('Display or hide tooltip by default when the map first loads.', 'fusion-core'),
					  "id" 				=> "fusion_popup",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "yes" ,
					  "allowedValues" 	=> $choices 
					  ),
				
				array("name" 			=> __('Select the Map Styling Switch', 'fusion-core'),
					  "desc" 			=> __('Choose default styling for classic google map styles. Choose theme styling for our custom style. Choose custom styling to make your own with the advanced options below.', 'fusion-core'),
					  "id" 				=> "fusion_mapstyle",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "default",
					  "allowedValues" 	=> array('default' 		=> __('Default Styling', 'fusion-core'),
											   'theme' 			=> __('Theme Styling', 'fusion-core'),
											   'custom' 		=> __('Custom Styling', 'fusion-core'))
					  ),
					  
				array("name" 			=> __('Map Overlay Color', 'fusion-core'),
					  "desc" 			=> __('Custom styling setting only. Pick an overlaying color for the map. Works best with "roadmap" type.', 'fusion-core'),
					  "id" 				=> "fusion_overlaycolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Infobox Styling', 'fusion-core'),
					  "desc" 			=> __('Custom styling setting only. Choose between default or custom info box.', 'fusion-core'),
					  "id" 				=> "fusion_infobox",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "default",
					  "allowedValues" 	=> array('default' 		=> __('Default Infobox', 'fusion-core'),
											   'custom' 		=> __('Custom Infobox', 'fusion-core'))
					  ),
					  
				array("name" 			=> __('Infobox Content', 'fusion-core'),
					  "desc" 			=> __('Custom styling setting only. Type in custom info box content to replace address string. For multiple addresses, separate info box contents by using the | symbol. ex: InfoBox 1|InfoBox 2|InfoBox 3.', 'fusion-core'),
					  "id" 				=> "fusion_infoboxcontent",
					  "type" 			=> ElementTypeEnum::TEXTAREA,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Info Box Text Color', 'fusion-core'),
					  "desc" 			=> __('Custom styling setting only. Pick a color for the info box text.', 'fusion-core'),
					  "id" 				=> "fusion_infoboxtextcolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Info Box Background Color', 'fusion-core'),
					  "desc" 			=> __('Custom styling setting only. Pick a color for the info box background.', 'fusion-core'),
					  "id" 				=> "fusion_infoboxbackgroundcolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Custom Marker Icon', 'fusion-core'),
					  "desc" 			=> __('Custom styling setting only. Use full image urls for custom marker icons or input "theme" for our custom marker. For multiple addresses, separate icons by using the | symbol or use one for all. ex: Icon 1|Icon 2|Icon 3', 'fusion-core'),
					  "id" 				=> "fusion_icon",
					  "type" 			=> ElementTypeEnum::TEXTAREA,
					  "value" 			=> ""
					  ),
					  
			   array("name" 			=> __('Address', 'fusion-core'),
					  "desc" 			=> __('Add your address to the location you wish to show on the map. If the location is off, please try to use long/lat coordinates with latlng=. ex: latlng=12.381068,-1.492711. For multiple addresses, separate addresses by using the | symbol. ex: Address 1|Address 2|Address 3.', 'fusion-core'),
					  "id" 				=> "fusion_content",
					  "type" 			=> ElementTypeEnum::TEXTAREA,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('CSS Class', 'fusion-core'),
					  "desc"			=> __('Add a class to the wrapping HTML element.', 'fusion-core'),
					  "id" 				=> "fusion_class",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
					  ),

		array("name"	  => __('CSS ID', 'fusion-core'),
					  "desc"	  => __('Add an ID to the wrapping HTML element.', 'fusion-core'),
					  "id"		=> "fusion_id",
					  "type"	  => ElementTypeEnum::INPUT,
					  "value"	   => "" 
			),
				
				);
		}
	}