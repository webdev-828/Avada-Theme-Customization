<?php
/**
 * ContentBoxes implementation, it extends DDElementTemplate like all other elements
 */
	class TF_ContentBoxes extends DDElementTemplate {
		public function __construct( $am_elements = array() ) {
			parent::__construct($am_elements);
		} 

		// Implementation for the element structure.
		public function create_element_structure() {
			
			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] 		= get_class($this);
			// element id
			$this->config['id']	   		= 'content_boxes';
			// element name
			$this->config['name']	 		= __('Content Boxes', 'fusion-core');
			// element icon
			$this->config['icon_url']  		= "icons/sc-text_block.png";
			// css class related to this element
			$this->config['css_class'] 		= "fusion_element_box";
			// element icon class
			$this->config['icon_class']		= 'fusion-icon builder-options-icon fusiona-newspaper';
			// tooltip that will be displyed upon mouse over the element
			//$this->config['tool_tip']  		= 'Creates Content Boxes';
			// any special html data attribute (i.e. data-width) needs to be passed
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] 			= array("drop_level"   => "4");
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {
			
			$innerHtml  = '<div class="fusion_iconbox textblock_element textblock_element_style" id="fusion_content_box">';
			$innerHtml .= '<div class="bilder_icon_container"><span class="fusion_iconbox_icon"><i class="fusiona-newspaper"></i><sub class="sub">'.__('Content Boxes', 'fusion-core').'</sub><p>layout = <span class="content_boxes_layout">icon-on-side</span> <br /> columns = <font class="content_boxes_columns">5</font></p></span></div>';
			$innerHtml .= '</div>';

			$this->config['innerHtml'] = $innerHtml;
		}
		
		//this function defines TextBlock sub elements or structure
		function popup_elements( $am_elements ) {
			
			$no_of_columns 						= FusionHelper::fusion_create_dropdown_data(1,6);
			$reverse_choices					= FusionHelper::get_reversed_choice_data();
			$animation_speed 					= FusionHelper::get_animation_speed_data();
			$animation_direction 				= FusionHelper::get_animation_direction_data();
			$animation_type 					= FusionHelper::get_animation_type_data();
			$animation_speed_parent 			= FusionHelper::get_animation_speed_data( true );
			$animation_direction_parent 		= FusionHelper::get_animation_direction_data( true );
			$animation_type_parent 				= FusionHelper::get_animation_type_data( true );

	  $am_array = array();

	  $am_array[] = array ( 
							array( "name"	 => __('Title', 'fusion-core'),
										"desc"		=> __('The box title.', 'fusion-core'),
										"id"		=> "fusion_title[0]",
										"type"		=> ElementTypeEnum::INPUT,
										"value"	   => array() 
							),
						  array( "name"	 => __('Icon', 'fusion-core'),
										"desc"		=> __('Click an icon to select, click again to deselect', 'fusion-core'),
										"id"		=> "icon[0]",
										"type"		=> ElementTypeEnum::ICON_BOX,
										"value"	   => array() ,
						  "list"		=> FusionHelper::GET_ICONS_LIST()
							),
						  
						  array("name"	=> __('Content Box Background Color', 'fusion-core'),
									  "desc"		=> __('Leave blank for theme option selection.', 'fusion-core'),
									  "id"		=> "fusion_backgroundcolor[0]",
									  "type"		=> ElementTypeEnum::COLOR,
									  "value"	   => array (),
									  "settings_lvl" => "child"
							),
						  array("name"	=> __('Icon Color', 'fusion-core'),
									  "desc"		=> __('Leave blank for theme option selection.', 'fusion-core'),
									  "id"		=> "fusion_iconcolor[0]",
									  "type"		=> ElementTypeEnum::COLOR,
									   "settings_lvl" => "child",
									  "value"	   => array ()
							),
						  array("name"	=> __('Icon Background Color', 'fusion-core'),
									  "desc"		=> __('Leave blank for theme option selection.', 'fusion-core'),
									  "id"		=> "fusion_circlecolor[0]",
									  "type"		=> ElementTypeEnum::COLOR,
									  "settings_lvl" => "child",
									  "value"	   => array ()
							),
						  array("name"	=> __('Icon Background Inner Border Color', 'fusion-core'),
									  "desc"		=> __('Leave blank for theme option selection.', 'fusion-core'),
									  "id"		=> "fusion_circlebordercolor[0]",
									  "type"		=> ElementTypeEnum::COLOR,
									  "settings_lvl" => "child",
									  "value"	   => array ('')
							),
						  array("name"	=> __('Icon Background Inner Border Size', 'fusion-core'),
									  "desc"		=> __('Leave blank for theme option selection.', 'fusion-core'),
									  "id"		=> "fusion_circlebordercolorsize[0]",
									  "type"		=> ElementTypeEnum::INPUT,
									  "settings_lvl" => "child",
									  "value"	   => array ('')
							),
						  array("name"	=> __('Icon Background Outer Border Color', 'fusion-core'),
									  "desc"		=> __('Leave blank for theme option selection.', 'fusion-core'),
									  "id"		=> "fusion_outercirclebordercolor[0]",
									  "type"		=> ElementTypeEnum::COLOR,
									  "settings_lvl" => "child",
									  "value"	   => array ('')
							),
						  array("name"	=> __('Icon Background Outer Border Size', 'fusion-core'),
									  "desc"		=> __('Leave blank for theme option selection.', 'fusion-core'),
									  "id"		=> "fusion_outercirclebordersize[0]",
									  "type"		=> ElementTypeEnum::INPUT,
									  "settings_lvl" => "child",
									  "value"	   => array ('')
							),
						  array("name"	=> __('Rotate Icon', 'fusion-core'),
									  "desc"		=> __('Choose to rotate the icon.', 'fusion-core'),
									  "id"		=> "fusion_iconrotate[0]",
									  "type"		=> ElementTypeEnum::SELECT,
							"value"	   => array(""),
									  "allowedValues"   => array(''	   =>'None',
																 '90'	   =>'90',
										 '180'	  =>'180',
										 '270'	  => '270') 
						  ),
						  array("name"	=> __('Spinning Icon', 'fusion-core'),
									  "desc"		=> __('Choose to let the icon spin.', 'fusion-core'),
									  "id"		=> "fusion_iconspin[0]",
									  "type"		=> ElementTypeEnum::SELECT,
							"value"	   => array(""),
									  "allowedValues"   => $reverse_choices 
						  ),
						  array("name"	=> __('Icon Image', 'fusion-core'),
									  "desc"		=> __('To upload your own icon image, deselect the icon above and then upload your icon image', 'fusion-core'),
									  "id"		=> "fusion_image[0]",
									  "type"		=> ElementTypeEnum::UPLOAD,
							"upid"		=> array(1),
									  "value"	   => array()
							),
						  array( "name"	 => __('Icon Image Width', 'fusion-core'),
										"desc"		=> __('If using an icon image, specify the image width in pixels but do not add px, ex: 35', 'fusion-core'),
										"id"		=> "fusion_image_width[0]",
										"type"		=> ElementTypeEnum::INPUT,
										"value"	   => array(35) 
							),
						  array( "name"	 => __('Icon Image Height', 'fusion-core'),
										"desc"		=> __('If using an icon image, specify the image height in pixels but do not add px, ex: 35', 'fusion-core'),
										"id"		=> "fusion_image_height[0]",
										"type"		=> ElementTypeEnum::INPUT,
										"value"	   => array(35) 
							),
						  array( "name"	 => __('Link URL' , 'fusion-core'),
										"desc"		=> __('Add the link\'s url ex: http://example.com', 'fusion-core'),
										"id"		=> "fusion_link[0]",
										"type"		=> ElementTypeEnum::INPUT,
										"value"	   => array() 
							),
						  array( "name"	 => __('Link Text', 'fusion-core'),
										"desc"		=> __('Insert the text to display as the link', 'fusion-core'),
										"id"		=> "fusion_linktext[0]",
										"type"		=> ElementTypeEnum::INPUT,
										"value"	   => array() 
							),
						  array("name"	=> __('Link Target', 'fusion-core'),
									  "desc"		=> __('_self = open in same window<br>_blank = open in new window', 'fusion-core'),
									  "id"		=> "fusion_target[0]",
									  "type"		=> ElementTypeEnum::SELECT,
							"value"	   => array("_self"),
									  "allowedValues"   => array('_self'	=>'_self',
																 '_blank'	 =>'_blank') 
						  ),
						  array( "name"	 => __('Content Box Content', 'fusion-core'),
										"desc"		=> __('Add content for content box', 'fusion-core'),
										"id"		=> "fusion_content_wp[0]",
										"type"		=> ElementTypeEnum::HTML_EDITOR,
										"value"	   => array() 
							),
						array("name"			=> __( 'Animation Type', 'fusion-core' ),
							"desc"				=> __( 'Select the type on animation to use on the shortcode', 'fusion-core' ),
							"id"				=> "fusion_animation_type[0]",
							"type"				=> ElementTypeEnum::SELECT,
							"settings_lvl" => "child",
							"value"	  			=> array(),
							"allowedValues"		=> $animation_type_parent
						),
		
						array("name"			=> __( 'Direction of Animation', 'fusion-core' ),
							"desc"				=> __( 'Select the incoming direction for the animation', 'fusion-core' ),
							"id"				=> "fusion_animation_direction[0]",
							"type"				=> ElementTypeEnum::SELECT,
							"settings_lvl" => "child",
							"value"	   			=> array(),
							"allowedValues"   	=> $animation_direction_parent
						),
				
						array("name"			=> __( 'Speed of Animation', 'fusion-core' ),
							"desc"				=> __( 'Type in speed of animation in seconds (0.1 - 1)', 'fusion-core' ),
							"id"				=> "fusion_animation_speed[0]",
							"type"				=> ElementTypeEnum::SELECT,
							"settings_lvl" => "child",
							"value"	   			=> array(),
							"allowedValues"  	=> $animation_speed_parent
						),

					  );
			
			$this->config['defaults'] = $am_array[0];

			if($am_elements) {
			  $am_array_copy = $am_array[0];
			  $am_array = array();
			  foreach($am_elements as $key => $am_element) {
				$build_am = $am_array_copy;
				foreach($build_am as $build_am_key => $build_am_element) {
				  $build_am[$build_am_key]['value'] = $am_elements[$key][$build_am_key];
				  $build_am[$build_am_key]['id'] = str_replace('[0]', '[' . $key . ']', $build_am_element['id']);
				}
				$am_array[] = $build_am;
			  }
			}

			$this->config['subElements'] = array(
				array("name" 			=> __('Parent / Child Settings', 'fusion-core'),
					  "desc" 			=> __('"Parent Level" settings will control all box styles together. "Child Level" settings will control each box style individually.', 'fusion-core'),
					  "id" 				=> "fusion_settings_lvl",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "child",
					  "allowedValues" 	=> array('parent' => 'Parent Level Settings', 'child' => 'Child Level Settings') 
					  ),
				array("name" 			=> __('Content Box Layout', 'fusion-core'),
					  "desc" 			=> __('Select the layout for the content box', 'fusion-core'),
					  "id" 				=> "fusion_layout",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "icon-with-title",
					  "allowedValues" 	=> array('icon-with-title' 	 	=> __('Classic Icon With Title', 'fusion-core'),
												 'icon-on-top' 		 	=> __('Classic Icon On Top', 'fusion-core'),
												 'icon-on-side' 	 	=> __('Classic Icon On Side', 'fusion-core'),
												 'icon-boxed' 		=> __('Classic Icon Boxed', 'fusion-core'),
												 'clean-vertical' 	 	=> __('Clean Layout Vertical', 'fusion-core'),
												 'clean-horizontal'  	=> __('Clean Layout Horizontal', 'fusion-core'),
												 'timeline-vertical' 	=> __('Timeline Vertical', 'fusion-core'),
												 'timeline-horizontal'  => __('Timeline Horizontal', 'fusion-core')) 
					  ),
				array("name" 			=> __('Number of Columns', 'fusion-core'),
					  "desc" 			=> __('Set the number of columns per row.', 'fusion-core'),
					  "id" 				=> "fusion_columns",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "4",
					  "allowedValues" 	=> $no_of_columns 
					  ),
				array("name"			=> __('Content Alignment', 'fusion-core'),
				"desc"					=> __('Works with "Classic Icon With Title" and "Classic Icon On Side" layout options.', 'fusion-core'),
				"id"					=> "fusion_circle_align",
				"type"					=> ElementTypeEnum::SELECT,
				"value"	   				=> array("left"),
				"allowedValues"   		=> array('left'		=> 'Left',
												'right'	 	=> 'Right') 
						 ),
				array("name"			=> __('Title Size', 'fusion-core'),
					  "desc"			=> __('Controls the size of the title. Leave blank for theme option selection. In pixels ex: 18px.', 'fusion-core'),
					  "id"				=> "fusion_title_size",
					  "type"			=> ElementTypeEnum::INPUT,
					  "value"	   		=> ''
						),
				array("name"			=> __('Title Font Color', 'fusion-core'),
					  "desc"			=> __('Controls the color of the title font. Leave blank for theme option selection. ex: #000', 'fusion-core'),
					  "id"				=> "fusion_title_color",
					  "type"			=> ElementTypeEnum::COLOR,
					  "value"	   		=> ''
						),
				array("name"			=> __('Body Font Color', 'fusion-core'),
					  "desc"			=> __('Controls the color of the body font. Leave blank for theme option selection. ex: #000', 'fusion-core'),
					  "id"				=> "fusion_body_color",
					  "type"			=> ElementTypeEnum::COLOR,
					  "value"	   		=> ''
						),
				array("name"	=> __('Content Box Background Color', 'fusion-core'),
							  "desc"		=> __('Leave blank for theme option selection.', 'fusion-core'),
							  "id"		=> "fusion_backgroundcolor",
							  "type"		=> ElementTypeEnum::COLOR,
							  "value"	   => ''
					),
				array("name"			=> __('Icon Background', 'fusion-core'),
				"desc"					=> __('Choose to show a background behind the icon. Select default for theme option selection.', 'fusion-core'),
				"id"					=> "fusion_icon_circle",
				"type"					=> ElementTypeEnum::SELECT,
				"value"	   				=> '',
				"allowedValues"   		=> array(''		=> 'Default',
												'yes'	 	=> 'Yes', 'no' => 'No') 
						 ),
				array("name" 			=> __('Icon Background Radius', 'fusion-core'),
					  "desc"			=> __('Choose the border radius of the icon background. Leave blank for theme option selection. In pixels (px), ex: 1px, or "round".', 'fusion-core'),
					  "id" 				=> "fusion_icon_circle_radius",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
					  ),
				array("name"	=> __('Icon Color', 'fusion-core'),
							  "desc"		=> __('Leave blank for theme option selection.', 'fusion-core'),
							  "id"		=> "fusion_iconcolor",
							  "type"		=> ElementTypeEnum::COLOR,
							  "value"	   => ''
					),
				array("name"	=> __('Icon Background Color', 'fusion-core'),
							  "desc"		=> __('Leave blank for theme option selection.', 'fusion-core'),
							  "id"		=> "fusion_circlecolor",
							  "type"		=> ElementTypeEnum::COLOR,
							  "value"	   => ''
					),
				array("name"	=> __('Icon Background Inner Border Color', 'fusion-core'),
							  "desc"		=> __('Leave blank for theme option selection.', 'fusion-core'),
							  "id"		=> "fusion_circlebordercolor",
							  "type"		=> ElementTypeEnum::COLOR,
							  "value"	   => ''
					),
				array("name"	=> __('Icon Background Inner Border Size', 'fusion-core'),
							  "desc"		=> __('Leave blank for theme option selection.', 'fusion-core'),
							  "id"		=> "fusion_circlebordercolorsize",
							  "type"		=> ElementTypeEnum::INPUT,
							  "value"	   => ''
					),
				array("name"	=> __('Icon Background Outer Border Color', 'fusion-core'),
							  "desc"		=> __('Leave blank for theme option selection.', 'fusion-core'),
							  "id"		=> "fusion_outercirclebordercolor",
							  "type"		=> ElementTypeEnum::COLOR,
							  "value"	   => ''
					),
				array("name"	=> __('Icon Background Outer Border Size', 'fusion-core'),
							  "desc"		=> __('Leave blank for theme option selection.', 'fusion-core'),
							  "id"		=> "fusion_outercirclebordersize",
							  "type"		=> ElementTypeEnum::INPUT,
							  "value"	   => ''
					),
				array("name"			=> __('Icon Size', 'fusion-core'),
					  "desc"			=> __('Controls the size of the icon.  Leave blank for theme option selection. In pixels ex: 18px.', 'fusion-core'),
					  "id"				=> "fusion_icon_size",
					  "type"			=> ElementTypeEnum::INPUT,
					  "value"	   		=> ''
						),
				array("name"			=> __('Icon Hover Animation Type', 'fusion-core'),
				"desc"					=> __('Select the animation type for icon on hover. Select default for theme option selection.', 'fusion-core'),
				"id"					=> "fusion_icon_hover_type",
				"type"					=> ElementTypeEnum::SELECT,
				"value"	   				=> array(''),
				"allowedValues"   		=> array('' => __('Default', 'Avada'), 'none' => __('None', 'Avada'), 'fade' => __('Fade', 'Avada'), 'slide' => __('Slide', 'Avada'), 'pulsate' => __('Pulsate', 'Avada'))
						 ),
						 
				array("name"	=> __('Hover Animation Color', 'fusion-core'),
							  "desc"		=> __('Select an accent color for the hover animation. Leave blank for theme option selection.', 'fusion-core'),
							  "id"		=> "fusion_hover_animation_color",
							  "type"		=> ElementTypeEnum::COLOR,
							  "value"	   => ''
					),						 
						 
				array("name"			=> __('Link Type', 'fusion-core'),
				"desc"					=> __('Select the type of link that should show in the content box. Select default for theme option selection.', 'fusion-core'),
				"id"					=> "fusion_link_type",
				"type"					=> ElementTypeEnum::SELECT,
				"value"	   				=> array(''),
				"allowedValues"   		=> array('' => 'Default', 'text' => 'Text', 'button-bar' => 'Button Bar', 'button' => 'Button') 
						 ),
				array("name"			=> __('Link Area', 'fusion-core'),
					  "desc"			=> __('Select which area the link will be assigned to. Select default for theme option selection.', 'fusion-core'),
					  "id"				=> "fusion_link_area",
					  "type"			=> ElementTypeEnum::SELECT,
					  "value"	   		=> array(''),
					  "allowedValues"	=> array('' => 'Default', 'link-icon' => 'Link+Icon', 'box' => 'Entire Content Box')
						),
				array("name"	=> __('Link Target', 'fusion-core'),
							  "desc"		=> __('_self = open in same window<br>_blank = open in new window. Select default for theme option selection.', 'fusion-core'),
							  "id"		=> "fusion_target",
							  "type"		=> ElementTypeEnum::SELECT,
					"value"	   => array(''),
							  "allowedValues"   => array('' => 'Default', '_self'	=>'_self',
														 '_blank'	 =>'_blank') 
				  ),
				array(
					"name"  => __( 'Animation Delay', 'fusion-core' ),
					"desc"  => __( 'Controls the delay of animation between each element in a set. In milliseconds, 1000 = 1 second.', 'fusion-core' ),
					"id"    => "animation_delay",
					"type"  => ElementTypeEnum::INPUT,
					"value" => ""
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
				
				array("name"			=> __( 'Animation Type', 'fusion-core' ),
					"desc"				=> __( 'Select the type on animation to use on the shortcode', 'fusion-core' ),
					"id"				=> "fusion_animation_type",
					"type"				=> ElementTypeEnum::SELECT,
					"value"	  			=> '',
					"allowedValues"		=> $animation_type
				),
				array("name"			=> __( 'Direction of Animation', 'fusion-core' ),
					"desc"				=> __( 'Select the incoming direction for the animation', 'fusion-core' ),
					"id"				=> "fusion_animation_direction",
					"type"				=> ElementTypeEnum::SELECT,
					"value"	   			=> array('left'),
					"allowedValues"   	=> $animation_direction
				),
				array("name"			=> __( 'Speed of Animation', 'fusion-core' ),
					"desc"				=> __( 'Type in speed of animation in seconds (0.1 - 1)', 'fusion-core' ),
					"id"				=> "fusion_animation_speed",
					"type"				=> ElementTypeEnum::SELECT,
					"value"	   			=> array('0.1'),
					"allowedValues"  	=> $animation_speed
				),
				array(
					"name"  => __( 'Margin Top', 'fusion-core' ),
					"desc"  => __( 'In pixels (px), ex: 1px.', 'fusion-core' ),
					"id"    => "margin_top",
					"type"  => ElementTypeEnum::INPUT,
					"value" => ""
				),
				array(
					"name"  => __( 'Margin Bottom', 'fusion-core' ),
					"desc"  => __( 'In pixels (px), ex: 1px.', 'fusion-core' ),
					"id"    => "margin_bottom",
					"type"  => ElementTypeEnum::INPUT,
					"value" => ""
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
					  
				array("type" 			=> ElementTypeEnum::ADDMORE,
					  "buttonText"		=> __('Add New Content Box', 'fusion-core'),
					  "id"				=> "am_fusion_content",
					  "elements" 		=> $am_array
					  ),
				);
		}
	}