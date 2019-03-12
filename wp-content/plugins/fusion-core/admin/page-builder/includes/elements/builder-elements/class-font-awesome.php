<?php
/**
 * FontAwesome implementation, it extends DDElementTemplate like all other elements
 */
	class TF_FontAwesome extends DDElementTemplate {
		
		public function __construct() {
			 
			parent::__construct();
		}

		// Implementation for the element structure.
		public function create_element_structure() {
			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] 	= get_class($this);
			// element id
			$this->config['id']	   	= 'font_awesome';
			// element name
			$this->config['name']	 	= __('Font Awesome', 'fusion-core');
			// element icon
			$this->config['icon_url']  	= "icons/sc-icon_box.png";
			// css class related to this element
			$this->config['css_class'] 	= "fusion_element_box";
			// element icon class
			$this->config['icon_class']	= 'fusion-icon builder-options-icon fusiona-flag';
			// tooltip that will be displyed upon mouse over the element
			//$this->config['tool_tip']  	= 'Creates Font Awesome Elements';
			// any special html data attribute (i.e. data-width) needs to be passed
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] 		= array("drop_level"   => "4");
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {
			
			$innerHtml  = '<div class="fusion_iconbox textblock_element textblock_element_style" id="fusion_font_awesome">';
			$innerHtml .= '<div class="bilder_icon_container"><span class="fusion_iconbox_icon"><h3 style="selector:hattrib"><i class="fusiona-flag" style="selector:iattrib"></i></h3></span></div>';
			$innerHtml .= '</div>';
			$this->config['innerHtml'] = $innerHtml;
		}
		
		//this function defines TextBlock sub elements or structure
		function popup_elements() {
			
			$animation_speed 			= FusionHelper::get_animation_speed_data();
			$animation_direction 		= FusionHelper::get_animation_direction_data();
			$animation_type 			= FusionHelper::get_animation_type_data();
			$choices					= FusionHelper::get_shortcode_choices();
			$reverse_choices			= FusionHelper::get_reversed_choice_data();
			
			$this->config['subElements'] = array(
				array("name" 			=> __('Select Icon', 'fusion-core'),
					  "desc" 			=> __('Click an icon to select, click again to deselect.', 'fusion-core'),
					  "id" 				=> "icon",
					  "type" 			=> ElementTypeEnum::ICON_BOX,
					  "value" 			=> "fa-flag",
					  "list"			=> FusionHelper::GET_ICONS_LIST()
					  ),
				
				array("name" 			=> __('Icon in Circle', 'fusion-core'),
					  "desc" 			=> __('Choose to display the icon in a circle', 'fusion-core'),
					  "id" 				=> "fusion_circle",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "yes",
					  "allowedValues" 	=> $choices 
					  ),
				
				array("name" 			=> __('Icon Size', 'fusion-core'),
					  "desc" 			=> __('Set the size of the icon. In pixels (px), ex: 13px.', 'fusion-core'),
					  "id" 				=> "fusion_size",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> ""
					  ),
				
				array("name" 			=> __('Icon Color', 'fusion-core'),
					  "desc" 			=> __('Controls the color of the icon. Leave blank for theme option selection.', 'fusion-core'),
					  "id" 				=> "fusion_iconcolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Icon Circle Background Color', 'fusion-core'),
					  "desc" 			=> __('Controls the color of the circle. Leave blank for theme option selection.', 'fusion-core'),
					  "id" 				=> "fusion_circlecolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Icon Circle Border Color', 'fusion-core'),
					  "desc" 			=> __('Controls the color of the circle border. Leave blank for theme option selection.', 'fusion-core'),
					  "id" 				=> "circlebordercolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Rotate Icon', 'fusion-core'),
					  "desc" 			=> __('Choose to rotate the icon.', 'fusion-core'),
					  "id" 				=> "fusion_rotate",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues" 	=> array('' 			=>'None',
											   '90' 			=>'90',
											   '180' 			=> '180',
											   '270'			=> '270')
					  ),
					  
				array("name" 			=> __('Spinning Icon', 'fusion-core'),
					  "desc" 			=> __('Choose to let the icon spin.', 'fusion-core'),
					  "id" 				=> "fusion_spin",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues" 	=> $reverse_choices 
					  ),
					  
				array("name" 			=> __('Animation Type', 'fusion-core'),
					  "desc" 			=> __('Select the type of animation to use on the shortcode', 'fusion-core'),
					  "id" 				=> "fusion_animation_type",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "0",
					  "allowedValues" 	=> $animation_type 
					 ),
				
				array("name" 			=> __('Direction of Animation', 'fusion-core'),
					  "desc" 			=> __('Select the incoming direction for the animation', 'fusion-core'),
					  "id" 				=> "fusion_animation_direction",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> '',
					  "allowedValues" 	=> $animation_direction 
					 ),
				
				array("name" 			=> __('Speed of Animation', 'fusion-core'),
					  "desc"			=> __('Type in speed of animation in seconds (0.1 - 1)', 'fusion-core'),
					  "id" 				=> "fusion_animation_speed",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
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

			    array("name"	  	=> __('Alignment', 'fusion-core'),
					  "desc"	  	=> __('Select the icon\'s alignment.', 'fusion-core'),
					  "id"			=> "fusion_alignment",
					  "type"	  	=> ElementTypeEnum::SELECT,
					  "value"	   	=> "",
					  "allowedValues"   => array(
							''	  		=> __('Default', 'fusion-core'),
							'left'	 	=> __('Left', 'fusion-core'),
							'center'	=> __('Center', 'fusion-core'),
							'right'		=> __('Right', 'fusion-core')) 
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