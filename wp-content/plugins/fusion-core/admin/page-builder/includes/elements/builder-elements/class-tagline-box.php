<?php
/**
 * TaglineBox block implementation, it extends DDElementTemplate like all other elements
 */
	class TF_TaglineBox extends DDElementTemplate {
		public function __construct() {
			
			parent::__construct();
		} 
		
		// Implementation for the element structure.
		public function create_element_structure() {
			
			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] 		= get_class($this);
			// element id
			$this->config['id']	   		= 'tagline_box';
			// element name
			$this->config['name']	 		= __('Tagline Box', 'fusion-core');
			// element icon
			$this->config['icon_url']  		= "icons/sc-text_block.png";
			// css class related to this element
			$this->config['css_class'] 		= "fusion_element_box";
			// element icon class
			$this->config['icon_class']		= 'fusion-icon builder-options-icon fusiona-list-alt';
			// tooltip that will be displyed upon mouse over the element
			//$this->config['tool_tip']  		= 'Creates a Tagline Box';
			// any special html data attribute (i.e. data-width) needs to be passed
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] 			= array("drop_level"   => "4");
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {
			
			$innerHtml  = '<div class="fusion_iconbox textblock_element textblock_element_style" id="fusion_tagline_box">';
			$innerHtml .= '<div class="bilder_icon_container"><span class="fusion_iconbox_icon"><i class="fusiona-list-alt"></i><sub class="sub">'.__('Tagline Box', 'fusion-core').'</sub><p class="tagline_title">Tagline title text will go here...</p></span></div>';
			$innerHtml .= '</div>';
			$this->config['innerHtml'] = $innerHtml;
		}
		//function to create shadow opacity data
		function create_shadow_opacity_data() {
			$opacity_data 	= array();
			$options 		= .1;
			while ($options < 1) {
				
				$opacity_data["fusion_".$options] = $options;
				$options				= $options + .1;
			}
			return $opacity_data;
		}
		//this function defines TextBlock sub elements or structure
		function popup_elements() {
			
			$reverse_choices			= FusionHelper::get_reversed_choice_data();
			$animation_speed 			= FusionHelper::get_animation_speed_data();
			$animation_direction 		= FusionHelper::get_animation_direction_data();
			$animation_type 			= FusionHelper::get_animation_type_data();
			
			$opacity_data = $this->create_shadow_opacity_data();
			$this->config['subElements'] = array(
				array("name" 			=> __('Background Color', 'fusion-core'),
					  "desc" 			=> __('Controls the background color. Leave blank for theme option selection.', 'fusion-core'),
					  "id" 				=> "fusion_backgroundcolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Shadow', 'fusion-core'),
					  "desc" 			=> __('Show the shadow below the box', 'fusion-core'),
					  "id" 				=> "fusion_shadow",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "no",
					  "allowedValues" 	=> $reverse_choices
					  ),
					  
				array("name" 			=> __('Shadow Opacity', 'fusion-core'),
					  "desc" 			=> __('Choose the opacity of the shadow', 'fusion-core'),
					  "id" 				=> "fusion_shadowopacity",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "0.7",
					  "allowedValues" 	=> $opacity_data
					  ),
					  
				array("name" 			=> __('Border', 'fusion-core'),
					  "desc"			=> __('In pixels (px), ex: 1px', 'fusion-core'),
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
					  
				array("name" 			=> __('Highlight Border Position', 'fusion-core'),
					  "desc" 			=> __('Choose the position of the highlight. This border highlight is from theme options primary color and does not take the color from border color above', 'fusion-core'),
					  "id" 				=> "fusion_highlightposition",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "top",
					  "allowedValues" 	=> array('top' 			=> __('Top', 'fusion-core'),
												'bottom' 		=> __('Bottom', 'fusion-core'),
												'left'			=> __('Left', 'fusion-core'),
												'right' 		=> __('Right', 'fusion-core'),
												'none'			=> __('None', 'fusion-core'))
					  ),
					  
				array("name" 			=> __('Content Alignment', 'fusion-core'),
					  "desc" 			=> __('Choose how the content should be displayed.', 'fusion-core'),
					  "id" 				=> "fusion_contentalignment",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues" 	=> array('left' 			=> __('Left', 'fusion-core'),
												'center' 		=> __('Center', 'fusion-core'),
												'right'			=> __('Right', 'fusion-core'))
					  ),
					  
				array("name" 			=> __('Button Text', 'fusion-core'),
					  "desc" 			=> __('Insert the text that will display in the button', 'fusion-core'),
					  "id" 				=> "fusion_button",
					  "type"			=> ElementTypeEnum::INPUT,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Link', 'fusion-core'),
					  "desc" 			=> __('The url the button will link to', 'fusion-core'),
					  "id" 				=> "fusion_url",
					  "type"			=> ElementTypeEnum::INPUT,
					  "value" 			=> ""),
					  
				array("name" 			=> __('Link Target', 'fusion-core'),
					  "desc" 			=> __('_self = open in same window<br>_blank = open in new window', 'fusion-core'),
					  "id" 				=> "fusion_target",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "_self",
					  "allowedValues" 	=> array('_self' 		=>'_self',
											   '_blank' 		=>'_blank') 
					 ),

		array("name"	  => __('Modal Window Anchor', 'fusion-core'),
					  "desc"	  => __('Add the class name of the modal window you want to open on button click.', 'fusion-core'),
					  "id"		=> "fusion_modalanchor",
					  "type"	  => ElementTypeEnum::INPUT,
					  "value"	   => ""),
					 
				array("name" 			=> __('Button Size', 'fusion-core'),
					  "desc" 			=> __('Select the button\'s size.', 'fusion-core'),
					  "id" 				=> "fusion_buttonsize",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues" 	=> array(''		=>__('Default', 'fusion-core'),
					  							'small' 		=>__('Small', 'fusion-core'),
											   'medium' 		=>__('Medium', 'fusion-core'),
											   'large' 			=> __('Large', 'fusion-core'),
											   'xlarge' 		=> __('XLarge', 'fusion-core')) 
					 ),
					 
				array("name" 			=> __('Button Type', 'fusion-core'),
					  "desc" 			=> __('Select the button\'s type.', 'fusion-core'),
					  "id" 				=> "fusion_buttontype",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues" 	=> array(''		=>__('Default', 'fusion-core'),
					  							'flat' 		=>__('Flat', 'fusion-core'),
											   '3D' 			=>'3D') 
					 ),
					 
				array("name" 			=> __('Button Shape', 'fusion-core'),
					  "desc" 			=> __('Select the button\'s shape.', 'fusion-core'),
					  "id" 				=> "fusion_buttonshape",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues" 	=> array(''		=>__('Default', 'fusion-core'),
					  							'square' 		=> __('Square', 'fusion-core'),
											   'pill' 			=> __('Pill', 'fusion-core'),
											   'round' 			=> __('Round', 'fusion-core')) 
					 ),
					 
				array("name" 			=> __('Button Color', 'fusion-core'),
					  "desc" 			=> __('Choose the button color<br>Default uses theme option selection', 'fusion-core'),
					  "id" 				=> "fusion_buttoncolor",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues" 	=> array('' 			=> __('Default', 'fusion-core'),
											   'green' 			=> __('Green', 'fusion-core'),
											   'darkgreen' 		=> __('Dark Green', 'fusion-core'),
											   'orange' 		=> __('Orange', 'fusion-core'),
											   'blue'			=> __('Blue', 'fusion-core'),
											   'red' 			=> __('Red', 'fusion-core'),
											   'pink' 			=> __('Pink', 'fusion-core'),
											   'darkgray' 		=> __('Dark Gray', 'fusion-core'),
											   'lightgray' 		=> __('Light Gray', 'fusion-core')) 
					 ),
					 
				array("name" 			=> __('Tagline Title', 'fusion-core'),
					  "desc"			=> __('Insert the title text', 'fusion-core'),
					  "id" 				=> "fusion_title",
					  "type" 			=> ElementTypeEnum::TEXTAREA,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Tagline Description', 'fusion-core'),
					  "desc"			=> __('Insert the description text', 'fusion-core'),
					  "id" 				=> "fusion_description",
					  "type" 			=> ElementTypeEnum::TEXTAREA,
					  "value" 			=> "" 
					  ),

				array("name" 			=> __('Additional Content', 'fusion-core'),
					  "desc"			=> __('This is additional content you can add to the tagline box. This will show below the title and description if one is used.', 'fusion-core'),
					  "id" 				=> "fusion_additionalcontent",
					  "type" 			=> ElementTypeEnum::HTML_EDITOR,
					  "value" 			=> "" 
					  ),
					  
				array("name" 			=> __('Margin Top', 'fusion-core'),
					  "desc" 			=> __('Add a custom top margin. In pixels.', 'fusion-core'),
					  "id" 				=> "fusion_margin_top",
					  "type"			=> ElementTypeEnum::INPUT,
					  "value" 			=> ""),
					  
				array("name" 			=> __('Margin Bottom', 'fusion-core'),
					  "desc" 			=> __('Add a custom bottom margin. In pixels.', 'fusion-core'),
					  "id" 				=> "fusion_margin_bottom",
					  "type"			=> ElementTypeEnum::INPUT,
					  "value" 			=> ""),						  
					  
				array("name" 			=> __('Animation Type', 'fusion-core'),
					  "desc" 			=> __('Select the type on animation to use on the shortcode', 'fusion-core'),
					  "id" 				=> "fusion_animation_type",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues" 	=> $animation_type 
					 ),
				
				array("name" 			=> __('Direction of Animation', 'fusion-core'),
					  "desc" 			=> __('Select the incoming direction for the animation', 'fusion-core'),
					  "id" 				=> "fusion_animation_direction",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues" 	=> $animation_direction 
					 ),
				
				array("name" 			=> __('Speed of Animation', 'fusion-core'),
					  "desc"			=> __('Type in speed of animation in seconds (0.1 - 1)', 'fusion-core'),
					  "id" 				=> "fusion_animation_speed",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "0.1",
					  "allowedValues"	=> $animation_speed 
					  ),
					  
				array("name" 			=> __( 'Offset of Animation', 'fusion-core' ),
					  "desc"			=> __( 'Choose when the animation shoul start.', 'fusion-core' ),
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