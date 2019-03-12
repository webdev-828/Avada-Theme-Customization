<?php
/**
 * Countdown implementation, it extends DDElementTemplate like all other elements
 */
	class TF_CountDown extends DDElementTemplate {
		
		public function __construct() {
			 
			parent::__construct();
		}

		// Implementation for the element structure.
		public function create_element_structure() {
			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] 	= get_class($this);
			// element id
			$this->config['id']	   		= 'countdown';
			// element name
			$this->config['name']	 	= __('Countdown', 'fusion-core');
			// element icon
			$this->config['icon_url']  		= "icons/sc-text_block.png";
			// css class related to this element
			$this->config['css_class'] 		= "fusion_element_box";
			// element icon class
			$this->config['icon_class']		= 'fusion-icon builder-options-icon fusiona-calendar-check-o';
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] 		= array("drop_level"   => "4");
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {

			$innerHtml  = '<div class="fusion_iconbox textblock_element textblock_element_style" id="fusion_countdown">';
			$innerHtml .= '<div class="builder_icon_container"><span class="fusion_iconbox_icon"><i class="fusiona-calendar-check-o"></i><sub class="sub">'. $this->config['name'] .'</sub>';
			$innerHtml .= '<div class="fusion_countdown_timer"><span class="fusion_dash_weeks"><span class="dash">ph_weeks</span>' . __( 'Weeks', 'fusion-core' ) . '</span><span class="fusion_dash_days"><span class="dash">ph_days</span>' . __( 'Days', 'fusion-core' ) . '</span><span class="fusion_dash_hrs"><span class="dash">ph_hrs</span>' . __( 'Hrs', 'fusion-core' ) . '</span><span class="fusion_dash_mins"><span class="dash">ph_mins</span>' . __( 'Min', 'fusion-core' ) . '</span><span class="fusion_dash_secs"><span class="dash">ph_secs</span>' . __( 'Sec', 'fusion-core' ) . '</span></div>';
			$innerHtml .= '</span></div></div>';
			$this->config['innerHtml'] = $innerHtml;
		}
		
		//this function defines TextBlock sub elements or structure
		function popup_elements() {
				
			$choices_width_default = FusionHelper::get_shortcode_choices_with_default();
			
			$this->config['subElements'] = array(
			
				array("name" 			=> __('Countdown Timer End', 'fusion-core'),
					  "desc" 			=> __('Set the end date and time for the countdown time. Use SQL time format: YYYY-MM-DD HH:MM:SS. E.g: 2016-05-10 12:30:00.', 'fusion-core'),
					  "id" 				=> "fusion_type",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> ""
					  ),
					  
				array(
					"name"          => __( 'Timezone', 'fusion-core' ),
					"desc"          => __( 'Choose which timezone should be used for the countdown calculation.', 'fusion-core' ),
					"id"            => "fusion_timezone",
					"type"          => ElementTypeEnum::SELECT,
					"value"         => "",
					"allowedValues" => array(
						'' 				=> __( 'Default', 'fusion-core' ),
						'site_time' => __( 'Timezone of Site', 'fusion-core' ),
						'user_time' => __( 'Timezone of User', 'fusion-core' )
					)
				),					  
					  
				array("name" 			=> __('Show Weeks', 'fusion-core'),
					  "desc" 			=> __('Select "yes" to show weeks for longer countdowns.', 'fusion-core'),
					  "id" 				=> "fusion_show_weeks",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues" 	=> $choices_width_default 
					 ),					  
				
				array("name" 			=> __('Backgound Color', 'fusion-core'),
					  "desc" 			=> __('Choose a background color for the countdown wrapping box.', 'fusion-core'),
					  "id" 				=> "fusion_background_color",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),
					  
				array(
					"name"  => __( 'Background Image', 'fusion-core' ),
					"desc"  => __( 'Upload an image to display in the background of the countdown wrapping box.', 'fusion-core' ),
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
					"value"         => "",
					"allowedValues" => array(
						'' 			=> __( 'Default', 'fusion-core' ),
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
					"value"         => "",
					"allowedValues" => array(
						'' 				=> __( 'Default', 'fusion-core' ),
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
					  
				array("name" 			=> __('Border Radius', 'fusion-core'),
					  "desc"			=> __('Choose the radius of outer box and also the counter boxes. In pixels (px), ex: 1px.', 'fusion-core'),
					  "id" 				=> "fusion_borderradius",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
					  ),					  
					  
				array("name" 			=> __('Counter Boxes Color', 'fusion-core'),
					  "desc" 			=> __('Choose a background color for the counter boxes.', 'fusion-core'),
					  "id" 				=> "fusion_counterboxes_color",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),					  
					  
				array("name" 			=> __('Counter Boxes Text Color', 'fusion-core'),
					  "desc" 			=> __('Choose a text color for the countdown timer.', 'fusion-core'),
					  "id" 				=> "fusion_counterboxes_textcolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Heading Text', 'fusion-core'),
					  "desc"			=> __('Choose a heading text for the countdown.', 'fusion-core'),
					  "id" 				=> "fusion_heading_text.",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
					  ),
					  
				array("name" 			=> __('Heading Text Color', 'fusion-core'),
					  "desc" 			=> __('Choose a text color for the countdown heading.', 'fusion-core'),
					  "id" 				=> "fusion_heading_textcolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Subheading Text', 'fusion-core'),
					  "desc"			=> __('Choose a subheading text for the countdown.', 'fusion-core'),
					  "id" 				=> "fusion_subheading_text.",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
					  ),
					  
				array("name" 			=> __('Subheading Text Color', 'fusion-core'),
					  "desc" 			=> __('Choose a text color for the countdown subheading.', 'fusion-core'),
					  "id" 				=> "fusion_subheading_textcolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),					  
					  
				array("name" 			=> __('Link Text', 'fusion-core'),
					  "desc"			=> __('Choose a link text for the countdown.', 'fusion-core'),
					  "id" 				=> "fusion_link_text.",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
					  ),
					  
				array("name" 			=> __('Link Text Color', 'fusion-core'),
					  "desc" 			=> __('Choose a text color for the countdown link.', 'fusion-core'),
					  "id" 				=> "fusion_link_textcolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),					  
	
				array("name" 			=> __('Link URL', 'fusion-core'),
					  "desc" 			=> __('Add a url for the link. E.g: http://example.com.', 'fusion-core'),
					  "id" 				=> "fusion_url",
					  "type"			=> ElementTypeEnum::INPUT,
					  "value" 			=> ""),
				
				array("name" 			=> __('Link Target', 'fusion-core'),
					  "desc" 			=> __('_self = open in same window<br>_blank = open in new window', 'fusion-core'),
					  "id" 				=> "fusion_target",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "_self",
					  "allowedValues" 	=> array(
					  							'default'		=> 'Default',
					  							'_self' 		=> '_self',
												'_blank' 		=> '_blank'
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
