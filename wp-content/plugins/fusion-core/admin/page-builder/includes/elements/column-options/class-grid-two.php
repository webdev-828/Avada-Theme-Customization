<?php

	/**
	 * One 1/2 layout category implementation, it extends DDElementTemplate like all other elements
	 */
	class TF_GridTwo extends DDElementTemplate {

		public function __construct() {

			parent::__construct();
		}

		// Implementation for the element structure.
		public function create_element_structure() {

			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] = get_class( $this );
			// element id
			$this->config['id'] = 'grid_two';
			// element shortcode base
			$this->config['base'] = 'one_half';
			// element name
			$this->config['name'] = '1/2';
			// element icon
			$this->config['icon_url'] = "icons/sc-two.png";
			// element icon class
			$this->config['icon_class'] = 'fusion-icon fusion-icon-grid-2';
			// css class related to this element
			$this->config['css_class'] = "fusion_layout_column grid_two item-container sort-container ";
			// tooltip that will be displyed upon mouse over the element
			//$this->config['tool_tip']  		= 'Creates a single (1/2) width column';
			// any special html data attribute (i.e. data-width) needs to be passed
			// width determine the ratio of them element related to its parent element in the editor, 
			// it's only important for layout elements.
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] = array( "floated_width" => "0.5", "width" => "2", "drop_level" => "3" );
		}

		// override default implemenation for this function as this element doesn't have any content.
		public function create_visual_editor( $params ) {

			$this->config['innerHtml'] = "";
		}

		//this function defines 1/2 sub elements or structure
		function popup_elements() {
			$animation_speed     = FusionHelper::get_animation_speed_data();
			$animation_direction = FusionHelper::get_animation_direction_data();
			$animation_type      = FusionHelper::get_animation_type_data();

			$this->config['layout_opt']  = true;
			$this->config['subElements'] = array(


				array(
					"name"          => __( 'Last Column', 'fusion-core' ),
					"desc"          => __( 'Choose if the column is last in a set. This has to be set to "Yes" for the last column in a set.', 'fusion-core' ),
					"id"            => "last",
					"type"          => ElementTypeEnum::SELECT,
					"value"         => "no",
					"allowedValues" => array(
						'yes' => __( 'Yes', 'fusion-core' ),
						'no'  => __( 'No', 'fusion-core' ),
					)
				),
				array(
					"name"          => __( 'Column Spacing', 'fusion-core' ),
					"desc"          => __( 'Set to "No" to eliminate margin between columns.', 'fusion-core' ),
					"id"            => "spacing",
					"type"          => ElementTypeEnum::SELECT,
					"value"         => "yes",
					"allowedValues" => array(
						'yes' => __( 'Yes', 'fusion-core' ),
						'no'  => __( 'No', 'fusion-core' ),
					)
				),
				array(
					"name"          => __( 'Center Content Vertically', 'fusion-core' ),
					"desc"          => __( 'Only works with columns inside a full width container that is set to equal heights. Set to "Yes" to center the content vertically.', 'fusion-core' ),
					"id"            => "center_content",
					"type"          => ElementTypeEnum::SELECT,
					"value"         => "no",
					"allowedValues" => array(
						'yes' => __( 'Yes', 'fusion-core' ),
						'no'  => __( 'No', 'fusion-core' ),
					)
				),
				array(
					"name"          => __( 'Hide on Mobile', 'fusion-core' ),
					"desc"          => __( 'Select "Yes" to hide column on mobile.', 'fusion-core' ),
					"id"            => "hide_on_mobile",
					"type"          => ElementTypeEnum::SELECT,
					"value"         => "no",
					"allowedValues" => array(
						'no'  => __( 'No', 'fusion-core' ),
						'yes' => __( 'Yes', 'fusion-core' ),
					)
				),
				array(
					"name"  => __( 'Background Color', 'fusion-core' ),
					"desc"  => __( 'Controls the background color.', 'fusion-core' ),
					"id"    => "background_color",
					"type"  => ElementTypeEnum::COLOR,
					"value" => ""
				),
				array(
					"name"  => __( 'Background Image', 'fusion-core' ),
					"desc"  => __( 'Upload an image to display in the background', 'fusion-core' ),
					"id"    => "background_image",
					"type"  => ElementTypeEnum::UPLOAD,
					"upid"  => "1",
					"value" => ""
				),
				array(
					"name"          => __( 'Background Repeat', 'fusion-core' ),
					"desc"          => __( 'Choose how the background image repeats.', 'fusion-core' ),
					"id"            => "background_repeat",
					"type"          => ElementTypeEnum::SELECT,
					"value"         => "no-repeat",
					"allowedValues" => array(
						'no-repeat' => __( 'No Repeat', 'fusion-core' ),
						'repeat'    => __( 'Repeat Vertically and Horizontally', 'fusion-core' ),
						'repeat-x'  => __( 'Repeat Horizontally', 'fusion-core' ),
						'repeat-y'  => __( 'Repeat Vertically', 'fusion-core' )
					)
				),
				array(
					"name"          => __( 'Background Position', 'fusion-core' ),
					"desc"          => __( 'Choose the postion of the background image.', 'fusion-core' ),
					"id"            => "background_position",
					"type"          => ElementTypeEnum::SELECT,
					"value"         => "left top",
					"allowedValues" => array(
						'left top'      => __( 'Left Top', 'fusion-core' ),
						'left center'   => __( 'Left Center', 'fusion-core' ),
						'left bottom'   => __( 'Left Bottom', 'fusion-core' ),
						'right top'     => __( 'Right Top', 'fusion-core' ),
						'right center'  => __( 'Right Center', 'fusion-core' ),
						'right bottom'  => __( 'Right Bottom', 'fusion-core' ),
						'center top'    => __( 'Center Top', 'fusion-core' ),
						'center center' => __( 'Center Center', 'fusion-core' ),
						'center bottom' => __( 'Center Bottom', 'fusion-core' )
					)
				),
				array("name" 			=> __('Hover Type', 'fusion-core'),
					  "desc" 			=> __('Select the hover effect type. This will disable links and hover effects on elements inside the column.', 'fusion-core'),
					  "id" 				=> "fusion_hover_type",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "none",
					  "allowedValues" 	=> array('none' 			=> __('None', 'fusion-core'),
												 'zoomin' 			=> __('Zoom In', 'fusion-core'),
												 'zoomout' 			=> __('Zoom Out', 'fusion-core'),
												 'liftup' 			=> __('Lift Up', 'fusion-core')) 
				),
				array("name" 			=> __('Link URL', 'fusion-core'),
					  "desc"			=> __('Add the URL the column will link to, ex: http://example.com. This will disable links on elements inside the column.', 'fusion-core'),
					  "id" 				=> "fusion_link",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
				),
				array(
					"name"          => __( 'Border Position', 'fusion-core' ),
					"desc"          => __( 'Choose the postion of the border.', 'fusion-core' ),
					"id"            => "border_position",
					"type"          => ElementTypeEnum::SELECT,
					"value"         => "all",
					"allowedValues" => array(
						'all' => __('All', 'fusion-core'),
						'top' => __('Top', 'fusion-core'),
						'right' => __('Right', 'fusion-core'),
						'bottom' => __('Bottom', 'fusion-core'),
						'left' => __('Left', 'fusion-core')
					)
				),					
				array(
					"name"  => __( 'Border Size', 'fusion-core' ),
					"desc"  => __( 'In pixels (px), ex: 1px.', 'fusion-core' ),
					"id"    => "border_size",
					"type"  => ElementTypeEnum::INPUT,
					"value" => "0px"
				),
				array(
					"name"  => __( 'Border Color', 'fusion-core' ),
					"desc"  => __( 'Controls the border color.', 'fusion-core' ),
					"id"    => "border_color",
					"type"  => ElementTypeEnum::COLOR,
					"value" => ""
				),
				array(
					"name"          => __( 'Border Style', 'fusion-core' ),
					"desc"          => __( 'Controls the border style.', 'fusion-core' ),
					"id"            => "border_style",
					"type"          => ElementTypeEnum::SELECT,
					"value"         => "",
					"allowedValues" => array(
						'solid'  => __( 'Solid', 'fusion-core' ),
						'dashed' => __( 'Dashed', 'fusion-core' ),
						'dotted' => __( 'Dotted', 'fusion-core' )
					)
				),
				array(
					"name"  => __( 'Padding', 'fusion-core' ),
					"desc"  => __( 'In pixels (px), ex: 10px.', 'fusion-core' ),
					"id"    => "padding",
					"type"  => ElementTypeEnum::INPUT,
					"value" => "",
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
				array(
					"name"          => __( 'Animation Type', 'fusion-core' ),
					"desc"          => __( 'Select the type on animation to use on the shortcode', 'fusion-core' ),
					"id"            => "animation_type[0]",
					"type"          => ElementTypeEnum::SELECT,
					"value"         => array(),
					"allowedValues" => $animation_type
				),
				array(
					"name"          => __( 'Direction of Animation', 'fusion-core' ),
					"desc"          => __( 'Select the incoming direction for the animation', 'fusion-core' ),
					"id"            => "animation_direction[0]",
					"type"          => ElementTypeEnum::SELECT,
					"value"         => array(),
					"allowedValues" => $animation_direction
				),
				array(
					"name"          => __( 'Speed of Animation', 'fusion-core' ),
					"desc"          => __( 'Type in speed of animation in seconds (0.1 - 1)', 'fusion-core' ),
					"id"            => "animation_speed[0]",
					"type"          => ElementTypeEnum::SELECT,
					"value"         => array( '0.1' ),
					"allowedValues" => $animation_speed
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
					"name"  => __( 'CSS Class', 'fusion-core' ),
					"desc"  => __( 'Add a class to the wrapping HTML element.', 'fusion-core' ),
					"id"    => "class",
					"type"  => ElementTypeEnum::INPUT,
					"value" => ""
				),
				array(
					"name"  => __( 'CSS ID', 'fusion-core' ),
					"desc"  => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' ),
					"id"    => "id",
					"type"  => ElementTypeEnum::INPUT,
					"value" => ""
				),
			);
		}
	}