<?php
/**
 * Button element implementation, it extends DDElementTemplate like all other elements
 */
	class TF_ButtonBlock extends DDElementTemplate {
		public function __construct() {
			
			parent::__construct();
		} 
		
		// Implementation for the element structure.
		public function create_element_structure() {
			
			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] 		= get_class($this);
			// element id
			$this->config['id']	   		= 'button_block';
			// element name
			$this->config['name']	 		= __('Button', 'fusion-core');
			// element icon
			$this->config['icon_url']  		= "icons/sc-text_block.png";
			// css class related to this element
			$this->config['css_class'] 		= "fusion_element_box";
			// element icon class
			$this->config['icon_class']		= 'fusion-icon builder-options-icon fusiona-check-empty';
			// tooltip that will be displyed upon mouse over the element
			//$this->config['tool_tip']  		= 'Creates a Button';
			// any special html data attribute (i.e. data-width) needs to be passed
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] 			= array("drop_level"   => "4");
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {
			
			$innerHtml  = '<div class="fusion_iconbox textblock_element textblock_element_style" id="fusion_button">';
			$innerHtml .= '<div class="bilder_icon_container"> <a title="" target="_self" class="button orange" style="selector:attrib"><span class="fusion-button-text">Button Text</span></a> </div>';
			$innerHtml .= '</div>';
			$this->config['innerHtml'] = $innerHtml;
		}
		
		//this function defines TextBlock sub elements or structure
		function popup_elements() {
			
			$choices					= FusionHelper::get_shortcode_choices();
			$choices_with_default		= FusionHelper::get_shortcode_choices_with_default();
			$leftright					= FusionHelper::get_left_right_data();
			$animation_speed 			= FusionHelper::get_animation_speed_data();
			$animation_direction 		= FusionHelper::get_animation_direction_data();
			$animation_type 			= FusionHelper::get_animation_type_data();
			
			$this->config['subElements'] = array(
				array("name" 			=> __('Button URL', 'fusion-core'),
					  "desc" 			=> __('Add the button\'s url ex: http://example.com', 'fusion-core'),
					  "id" 				=> "fusion_url",
					  "type"			=> ElementTypeEnum::INPUT,
					  "value" 			=> ""),
					  
				array("name" 			=> __('Button Style', 'fusion-core'),
					  "desc" 			=> __('Select the button\'s color. Select default or color name for theme options, or select custom to use advanced color options below.', 'fusion-core'),
					  "id" 				=> "fusion_style",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "default",
					  "allowedValues" 	=> array('default' 			=> __('Default', 'fusion-core'),
					  						   'custom'			=> __('Custom', 'fusion-core'),
											   'green' 			=> __('Green', 'fusion-core'),
											   'darkgreen' 		=> __('Dark Green', 'fusion-core'),
											   'orange' 		=> __('Orange', 'fusion-core'),
											   'blue'			=> __('Blue', 'fusion-core'),
											   'red' 			=> __('Red', 'fusion-core'),
											   'pink' 			=> __('Pink', 'fusion-core'),
											   'darkgray' 		=> __('Dark Gray', 'fusion-core'),
											   'lightgray' 		=> __('Light Gray', 'fusion-core')) 
					 ),
					 
				array("name" 			=> __('Button Size', 'fusion-core'),
					  "desc" 			=> __('Select the button\'s size. Choose default for theme option selection.', 'fusion-core'),
					  "id" 				=> "fusion_size",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues" 	=> array(''	   => __('Default', 'fusion-core'),
						'small' 		=> __('Small', 'fusion-core'),
											   'medium' 		=> __('Medium', 'fusion-core'),
											   'large' 			=> __('Large', 'fusion-core'),
												'xlarge' 		=> __('XLarge', 'fusion-core'),) 
					 ),
					 
				array("name" 			=> __('Button Span', 'fusion-core'),
					  "desc" 			=> __('Choose to have the button span the full width of its container.', 'fusion-core'),
					  "id" 				=> "fusion_button_span",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "default",
					  "allowedValues" 	=> $choices_with_default
					 ),					 
					 
				array("name" 			=> __('Button Type', 'fusion-core'),
					  "desc" 			=> __('Select the button\'s type. Choose default for theme option selection.', 'fusion-core'),
					  "id" 				=> "fusion_type",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues" 	=> array(''	   => __('Default', 'fusion-core'),
						'flat' 		=>__('Flat', 'fusion-core'),
											   '3d' 			=>'3D') 
					 ),
					 
				array("name" 			=> __('Button Shape', 'fusion-core'),
					  "desc" 			=> __('Select the button\'s shape. Choose default for theme option selection.', 'fusion-core'),
					  "id" 				=> "fusion_shape",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues" 	=> array(''	   => __('Default', 'fusion-core'),
						'square' 		=> __('Square', 'fusion-core'),
												'pill' 			=> __('Pill', 'fusion-core'),
												'round' 		=> __('Round', 'fusion-core')) 
					 ),
					 
				array("name" 			=> __('Button Target', 'fusion-core'),
					  "desc" 			=> __('_self = open in same window<br>_blank = open in new window', 'fusion-core'),
					  "id" 				=> "fusion_target",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "_self",
					  "allowedValues" 	=> array('_self' 		=>'_self',
											   '_blank' 		=>'_blank') 
					 ),
					 
				array("name" 			=> __('Button Title attribute', 'fusion-core'),
					  "desc" 			=> __('Set a title attribute for the button link.', 'fusion-core'),
					  "id" 				=> "fusion_title",
					  "type"			=> ElementTypeEnum::INPUT,
					  "value" 			=> ""
					  ),
					  	 
				array("name" 			=> __('Button\'s Text', 'fusion-core'),
					  "desc" 			=> __('Add the text that will display on button', 'fusion-core'),
					  "id" 				=> "fusion_content",
					  "type"			=> ElementTypeEnum::INPUT,
					  "value" 			=> "Button Text"
					  ),
				
				array("name" 			=> __('Button Gradient Top Color', 'fusion-core'),
					  "desc" 			=> __('Custom setting only. Set the top color of the button background.', 'fusion-core'),
					  "id" 				=> "fusion_gradtopcolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Button Gradient Bottom Color', 'fusion-core'),
					  "desc" 			=> __('Custom setting only. Set the bottom color of the button background or leave empty for solid color.', 'fusion-core'),
					  "id" 				=> "fusion_gradbottomcolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Button Gradient Top Color Hover', 'fusion-core'),
					  "desc" 			=> __('Custom setting only. Set the top hover color of the button background.', 'fusion-core'),
					  "id" 				=> "fusion_gradtopcolorhover",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Button Gradient Bottom Color Hover', 'fusion-core'),
					  "desc" 			=> __('Custom setting only. Set the bottom hover color of the button background or leave empty for solid color.', 'fusion-core'),
					  "id" 				=> "fusion_gradbottomcolorhover",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Accent Color', 'fusion-core'),
					  "desc" 			=> __('Custom setting only. This option controls the color of the button border, divider, text and icon.', 'fusion-core'),
					  "id" 				=> "fusion_bordercolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Accent Hover Color', 'fusion-core'),
					  "desc" 			=> __('Custom setting only. This option controls the hover color of the button border, divider, text and icon.', 'fusion-core'),
					  "id" 				=> "fusion_borderhovercolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Bevel Color (3D Mode only)', 'fusion-core'),
					  "desc" 			=> __('Custom setting. Set the bevel color of 3D buttons.', 'fusion-core'),
					  "id" 				=> "fusion_bevelcolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Border Width', 'fusion-core'),
					  "desc"			=> __('Custom setting only. In pixels (px), ex: 1px. Leave blank for theme option selection.', 'fusion-core'),
					  "id" 				=> "fusion_bordersize",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
					  ),
					 
				array("name" 			=> __('Select Custom Icon', 'fusion-core'),
					  "desc" 			=> __('Click an icon to select, click again to deselect', 'fusion-core'),
					  "id" 				=> "icon",
					  "type" 			=> ElementTypeEnum::ICON_BOX,
					  "value" 			=> "",
					  "list"			=> FusionHelper::GET_ICONS_LIST()
					  ),
					  
				
				array("name" 			=> __('Icon Position', 'fusion-core'),
					  "desc" 			=> __('Choose the position of the icon on the button.', 'fusion-core'),
					  "id" 				=> "fusion_iconposition",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues" 	=> $leftright
					 ),
					 
				array("name" 			=> __('Icon Divider', 'fusion-core'),
					  "desc" 			=> __('Choose to display a divider between icon and text.', 'fusion-core'),
					  "id" 				=> "fusion_icondivider",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "no",
					  "allowedValues" 	=> $choices
					 ),
					 
				array("name" 			=> __('Modal Window Anchor', 'fusion-core'),
					  "desc"			=> __('Add the class name of the modal window you want to open on button click.', 'fusion-core'),
					  "id" 				=> "fusion_modal",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
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
					  "allowedValues" 	=> $animation_speed 
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

		  array("name"	  => __('Alignment', 'fusion-core'),
					  "desc"	  => __('Select the button\'s alignment.', 'fusion-core'),
					  "id"		=> "fusion_alignment",
					  "type"	  => ElementTypeEnum::SELECT,
			"value"	   => "",
					  "allowedValues"   => array(''	  => __('Default', 'fusion-core'),
						   'left'	 => __('Left', 'fusion-core'),
											   'center'	  => __('Center', 'fusion-core'),
						 'right'	=> __('Right', 'fusion-core')) 
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