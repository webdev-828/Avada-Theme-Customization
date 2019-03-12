<?php
/**
 * ImageFrame implementation, it extends DDElementTemplate like all other elements
 */
	class TF_ImageFrame extends DDElementTemplate {
		public function __construct() {
			
			parent::__construct();
		} 
		
		// Implementation for the element structure.
		public function create_element_structure() {
			
			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] 		= get_class($this);
			// element id
			$this->config['id']	   		= 'image_frame';
			// element name
			$this->config['name']	 		= __('Image Frame', 'fusion-core');
			// element icon
			$this->config['icon_url']  		= "icons/sc-text_block.png";
			// css class related to this element
			$this->config['css_class'] 		= "fusion_element_box";
			// element icon class
			$this->config['icon_class']		= 'fusion-icon builder-options-icon fusiona-image';
			// tooltip that will be displyed upon mouse over the element
			//$this->config['tool_tip']  		= 'Creates an Image Frame';
			// any special html data attribute (i.e. data-width) needs to be passed
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] 			= array("drop_level"   => "4");
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {
			
			
			$innerHtml  = '<div class="fusion_iconbox textblock_element textblock_element_style" id="fusion_image_frame">';
			$innerHtml .= '<div class="bilder_icon_container"><span class="fusion_iconbox_icon"><i class="fusiona-image"></i><sub class="sub">'.__('Image Frame', 'fusion-core').'</sub><div class="img_frame_section">Image here</div><div class="img_frame_gallery">gallery_id</div></span></div>';
			$innerHtml .= '</div>';
			$this->config['innerHtml'] = $innerHtml;

		}
		
		//this function defines TextBlock sub elements or structure
		function popup_elements() {
			
			$border_size 				= FusionHelper::fusion_create_dropdown_data( 0, 10 );
			$reverse_choices			= FusionHelper::get_reversed_choice_data();
			$animation_speed 			= FusionHelper::get_animation_speed_data();
			$animation_direction 		= FusionHelper::get_animation_direction_data();
			$animation_type 			= FusionHelper::get_animation_type_data();
			
			$this->config['subElements'] = array(
				array("name" 			=> __('Frame Style Type', 'fusion-core'),
					  "desc" 			=> __('Select the frame style type.', 'fusion-core'),
					  "id" 				=> "fusion_style",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "none",
					  "allowedValues" 	=> array('none' 			=> __('None', 'fusion-core'),
												 'glow' 			=> __('Glow', 'fusion-core'),
												 'dropshadow' 		=> __('Drop Shadow', 'fusion-core'),
												 'bottomshadow' 	=> __('Bottom Shadow', 'fusion-core')) 
					  ),

				array("name" 			=> __('Hover Type', 'fusion-core'),
					  "desc" 			=> __('Select the hover effect type.', 'fusion-core'),
					  "id" 				=> "fusion_hover_type",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "none",
					  "allowedValues" 	=> array('none' 			=> __('None', 'fusion-core'),
												 'zoomin' 			=> __('Zoom In', 'fusion-core'),
												 'zoomout' 			=> __('Zoom Out', 'fusion-core'),
												 'liftup' 			=> __('Lift Up', 'fusion-core')) 
					  ),
					  
				array("name" 			=> __('Border Color', 'fusion-core'),
					  "desc" 			=> __('Controls the border color. Leave blank for theme option selection.', 'fusion-core'),
					  "id" 				=> "fusion_bordercolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Border Size', 'fusion-core'),
					  "desc" 			=> __('In pixels (px), ex: 1px. Leave blank for theme option selection.', 'fusion-core'),
					  "id" 				=> "fusion_bordersize",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "0px",
					  ),

				array("name" 			=> __('Border Radius', 'fusion-core'),
					  "desc"			=> __('Choose the radius of the image. In pixels (px), ex: 1px, or "round".  Leave blank for theme option selection.', 'fusion-core'),
					  "id" 				=> "fusion_borderradius",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "0" 
					  ),						  
					  
				array("name" 			=> __('Style Color', 'fusion-core'),
					  "desc" 			=> __('For all style types except border. Controls the style color. Leave blank for theme option selection.', 'fusion-core'),
					  "id" 				=> "fusion_stylecolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Align', 'fusion-core'),
					  "desc" 			=> __('Choose how to align the image.', 'fusion-core'),
					  "id" 				=> "fusion_align",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "none",
					  "allowedValues" 	=> array('none'				=> __('None', 'fusion-core'),
					  							'left' 				=> __('Left', 'fusion-core'),
												 'right' 			=> __('Right', 'fusion-core'),
												 'center' 			=> __('Center', 'fusion-core')) 
					  ),
					  
				array("name" 			=> __('Image lightbox', 'fusion-core'),
					  "desc" 			=> __('Show image in Lightbox.', 'fusion-core'),
					  "id" 				=> "fusion_lightbox",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "no",
					  "allowedValues" 	=> $reverse_choices 
					  ),
					  
				array("name" 			=> __('Gallery ID', 'fusion-core'),
					  "desc"			=> __('Set a name for the lightbox gallery this image frame should belong to.', 'fusion-core'),
					  "id" 				=> "fusion_gallery",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
					  ),					  
					  
				array("name" 			=> __('Lightbox Image', 'fusion-core'),
					  "desc" 			=> __('Upload an image that will show up in the lightbox.', 'fusion-core'),
					  "id" 				=> "fusion_lightboximage",
					  "type" 			=> ElementTypeEnum::UPLOAD,
					  "upid" 			=> "2",
					  "value" 			=> ""
					  ),						  
					  
				array("name" 			=> __('Image', 'fusion-core'),
					  "desc" 			=> __('Upload an image to display in the frame.', 'fusion-core'),
					  "id" 				=> "fusion_image",
					  "type" 			=> ElementTypeEnum::UPLOAD,
					  "upid" 			=> "1",
					  "value" 			=> ""
					  ),				  
					  
				array("name" 			=> __('Image Alt Text', 'fusion-core'),
					  "desc"			=> __('The alt attribute provides alternative information if an image cannot be viewed.', 'fusion-core'),
					  "id" 				=> "fusion_alt",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
					  ),
					  
				array("name" 			=> __('Picture Link URL', 'fusion-core'),
					  "desc"			=> __('Add the URL the picture will link to, ex: http://example.com.', 'fusion-core'),
					  "id" 				=> "fusion_link",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
					  ),

				array("name"	  		=> __('Link Target', 'fusion-core'),
					  "desc"	  		=> __('_self = open in same window<br>_blank = open in new window.', 'fusion-core'),
					  "id"				=> "fusion_target",
					  "type"	  		=> ElementTypeEnum::SELECT,
					  "value"	   		=> "_self",
					  "allowedValues"   => array('_self'	=>'_self',
											   '_blank'	 =>'_blank') 
		   			  ),					  
				
				array("name" 			=> __('Animation Type', 'fusion-core'),
					  "desc" 			=> __('Select the type of animation to use on the shortcode.', 'fusion-core'),
					  "id" 				=> "fusion_animation_type",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "0",
					  "allowedValues" 	=> $animation_type
					 ),
				
				array("name" 			=> __('Direction of Animation', 'fusion-core'),
					  "desc" 			=> __('Select the incoming direction for the animation.', 'fusion-core'),
					  "id" 				=> "fusion_animation_direction",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues" 	=> $animation_direction 
					 ),
				
				array("name" 			=> __('Speed of Animation', 'fusion-core'),
					  "desc"			=> __('Type in speed of animation in seconds (0.1 - 1).', 'fusion-core'),
					  "id" 				=> "fusion_animation_speed",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "0.1" ,
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
				array(
					"name"          => __( 'Hide on Mobile', 'fusion-core' ),
					"desc"          => __( 'Select yes to hide full width container on mobile.', 'fusion-core' ),
					"id"            => "hide_on_mobile",
					"type"          => ElementTypeEnum::SELECT,
					"value"         => "no",
					"allowedValues" => array(
						'no'  => __( 'No', 'fusion-core' ),
						'yes' => __( 'Yes', 'fusion-core' ),
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