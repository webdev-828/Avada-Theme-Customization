<?php
/**
 * Alert Box implementation, it extends DDElementTemplate like all other elements
 */
	class TF_AlertBox extends DDElementTemplate {
		
		public function __construct() {
			 
			parent::__construct();
		}

		// Implementation for the element structure.
		public function create_element_structure() {
			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] 	= get_class($this);
			// element id
			$this->config['id']	   	= 'alert_box';
			// element name
			$this->config['name']	 	= __('Alert', 'fusion-core');
			// element icon
			$this->config['icon_url']  	= "icons/sc-notification.png";
			// css class related to this element
			$this->config['css_class'] 	= "fusion_element_box";
			// element icon class
			$this->config['icon_class']	= 'fusion-icon builder-options-icon fusiona-exclamation-sign';
			// tooltip that will be displyed upon mouse over the element
			//$this->config['tool_tip']  	= 'Creates an Alert Box';
			// any special html data attribute (i.e. data-width) needs to be passed
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] 		= array("drop_level"   => "4");
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {

			$innerHtml  = '<div class="fusion_iconbox textblock_element textblock_element_style" id="fusion_alert">';
			$innerHtml .= '<div class="bilder_icon_container"><span class="fusion_iconbox_icon"><i class="fusiona-exclamation-sign"></i><sub class="sub">'.__('Preview text will go here and custom icon choice', 'fusion-core').'</sub></span></div>';
			$innerHtml .= '</div>';
			$this->config['innerHtml'] = $innerHtml;
		}
		
		//this function defines TextBlock sub elements or structure
		function popup_elements() {
			
			$animation_speed 		= FusionHelper::get_animation_speed_data();
			$animation_direction 	= FusionHelper::get_animation_direction_data();
			$animation_type 		= FusionHelper::get_animation_type_data();
			
			$this->config['subElements'] = array(
			
				array("name" 			=> __('Alert Type', 'fusion-core'),
					  "desc" 			=> __('Select the type of alert message. Choose custom for advanced color options below.', 'fusion-core'),
					  "id" 				=> "fusion_type",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "general",
					  "allowedValues" 	=> array('general' 	=>__('General', 'fusion-core'),
											   'error' 		=>__('Error', 'fusion-core'),
											   'success' 	=> __('Success', 'fusion-core'),
											   'notice' 	=> __('Notice', 'fusion-core'),
											   'custom' 	=> __('Custom', 'fusion-core'),)
					  ),
				
				array("name" 			=> __('Accent Color', 'fusion-core'),
					  "desc" 			=> __('Custom setting only. Set the border, text and icon color for custom alert boxes.', 'fusion-core'),
					  "id" 				=> "fusion_accentcolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Background Color', 'fusion-core'),
					  "desc" 			=> __('Custom setting only. Set the background color for custom alert boxes.', 'fusion-core'),
					  "id" 				=> "fusion_backgroundcolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Border Width', 'fusion-core'),
					  "desc"			=> __('Custom setting. For custom alert boxes. In pixels (px), ex: 1px.', 'fusion-core'),
					  "id" 				=> "fusion_bordersize",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "1px" 
					  ),
					  
				array("name" 			=> __('Select Custom Icon', 'fusion-core'),
					  "desc" 			=> __('Custom setting only. Click an icon to select, click again to deselect', 'fusion-core'),
					  "id" 				=> "icon",
					  "type" 			=> ElementTypeEnum::ICON_BOX,
					  "value" 			=> "",
					  "list"			=> FusionHelper::GET_ICONS_LIST()
					  ),

		array("name"	  => __('Box Shadow', 'fusion-core'),
			"desc"	  => __('Display a box shadow below the alert box.', 'fusion-core'),
					  "id"		=> "fusion_boxshadow",
					  "type"	  => ElementTypeEnum::SELECT,
					  "value"	   => "yes",
			"allowedValues"   => array('yes'	=> __('Yes', 'fusion-core'),
											   'no'	 => __('No', 'fusion-core'),)
		   ),
											   
				array("name" 			=> __('Alert Content', 'fusion-core'),
					  "desc" 			=> __('Insert the alert\'s content', 'fusion-core'),
					  "id" 				=> "fusion_content_wp",
					  "type" 			=> ElementTypeEnum::HTML_EDITOR,
					  "value" 			=> __('Your Content Goes Here', 'fusion-core')
					  ),
					  
				array("name" 			=> __('Animation Type', 'fusion-core'),
					  "desc" 			=> __('Select the type of animation to use on the shortcode', 'fusion-core'),
					  "id" 				=> "fusion_animation_type",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues" 	=> $animation_type 
					 ),
				
				array("name" 			=> __('Direction of Animation', 'fusion-core'),
					  "desc" 			=> __('Select the incoming direction for the animation', 'fusion-core'),
					  "id" 				=> "fusion_animation_direction",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "left",
					  "allowedValues" 	=> $animation_direction 
					 ),
				
				array("name" 			=> __('Speed of Animation', 'fusion-core'),
					  "desc"			=> __('Type in speed of animation in seconds (0.1 - 1)', 'fusion-core'),
					  "id" 				=> "fusion_animation_speed",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "" ,
					  "allowedValues"	=> $animation_speed
					  ),
					  
				array("name" 			=> __( 'Offset of Animation', 'fusion-core' ),
					  "desc"			=> __( 'Choose when the animation should start.', 'fusion-core' ),
					  "id" 				=> "fusion_animation_offset",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues" 	=> array(
					  						''					=> __( 'Default', 'fusion-core' ),					  
											'top-into-view' 	=> __( 'Top of element hits bottom of viewport', 'fusion-core' ),
											'top-mid-of-view' 	=> __( 'Top of element hits middle of viewport', 'fusion-core' ),
											'bottom-in-view' 	=> __( 'Bottom of element enters viewport', 'fusion-core' ),
											)
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