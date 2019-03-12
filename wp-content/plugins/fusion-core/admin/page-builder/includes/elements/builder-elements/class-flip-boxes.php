<?php
/**
 * Flip Boxes implementation, it extends DDElementTemplate like all other elements
 */
	class TF_FlipBoxes extends DDElementTemplate {
		public function __construct( $am_elements = array() ) {
			parent::__construct($am_elements);
		} 
		
		// Implementation for the element structure.
		public function create_element_structure() {
			
			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] 		= get_class($this);
			// element id
			$this->config['id']	   		= 'flip_boxes';
			// element name
			$this->config['name']	 		= __('Flip Boxes', 'fusion-core');
			// element icon
			$this->config['icon_url']  		= "icons/sc-text_block.png";
			// css class related to this element
			$this->config['css_class'] 		= "fusion_element_box";
			// element icon class
			$this->config['icon_class']		= 'fusion-icon builder-options-icon fusiona-loop-alt2';
			// tooltip that will be displyed upon mouse over the element
			//$this->config['tool_tip']  		= 'Creates Elastic Slider';
			// any special html data attribute (i.e. data-width) needs to be passed
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] 			= array("drop_level"   => "4");
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {
			
			$innerHtml  = '<div class="fusion_iconbox textblock_element textblock_element_style" id="fusion_flip_boxs">';
			$innerHtml .= '<div class="bilder_icon_container"><span class="fusion_iconbox_icon"><i class="fusiona-loop-alt2"></i><sub class="sub">'.__('Flip Boxes', 'fusion-core').'</sub><p>columns = <font class="flip_boxes_columns">5</font></p></span></div>';
			$innerHtml .= '</div>';
			$this->config['innerHtml'] = $innerHtml;
		}
		
		//this function defines TextBlock sub elements or structure
		function popup_elements( $am_elements ) {
			
			$no_of_columns 				= FusionHelper::fusion_create_dropdown_data( 1 , 6 );
			$border_size 				= FusionHelper::fusion_create_dropdown_data( 0, 10 );
			$reverse_choices			= FusionHelper::get_reversed_choice_data();
			$animation_speed 			= FusionHelper::get_animation_speed_data();
			$animation_direction 		= FusionHelper::get_animation_direction_data();
			$animation_type 			= FusionHelper::get_animation_type_data();
			$choices					= FusionHelper::get_shortcode_choices();

			$am_array = array();

			$am_array[] = array ( 
													array("name" 			=> __('Flip Box Frontside Heading', 'fusion-core'),
					  								"desc" 					=> __('Add a heading for the frontside of the flip box.', 'fusion-core'),
					  								"id" 					=> "fusion_titlefront[0]",
					  								"type" 					=> ElementTypeEnum::INPUT,
					  								"value" 				=> array ("Your Content Goes Here")
					  								),
													array("name" 			=> __('Flip Box Backside Heading', 'fusion-core'),
					  								"desc" 					=> __('Add a heading for the backside of the flip box.', 'fusion-core'),
					  								"id" 					=> "fusion_titleback[0]",
					  								"type" 					=> ElementTypeEnum::INPUT,
					  								"value" 				=> array ("Your Content Goes Here")
					  								),
													array( "name" 			=> __('Flip Box Frontside Content', 'fusion-core'),
					  							  	"desc"					=> __('Add content for the frontside of the flip box.', 'fusion-core'),
					  							  	"id" 					=> "fusion_text_front[0]",
					  							  	"type" 					=> ElementTypeEnum::INPUT,
					  							  	"value" 				=> array("Your Content Goes Here") 
					  								),
													array( "name" 			=> __('Flip Box Backside Content', 'fusion-core'),
					  							  	"desc"					=> __('Add content for the backside of the flip box.', 'fusion-core'),
					  							  	"id" 					=> "fusion_content_wp[0]",
					  							  	"type" 					=> ElementTypeEnum::HTML_EDITOR,
					  							  	"value" 				=> array("Your Content Goes Here") 
					  								),
													array("name" 			=> __('Background Color Frontside', 'fusion-core'),
					  								"desc" 					=> __('Controls the background color of the frontside. Leave blank for theme option selection. NOTE: flip boxes must have background colors to work correctly in all browsers.', 'fusion-core'),
					  								"id" 					=> "fusion_backgroundcolorfront[0]",
					  								"type" 					=> ElementTypeEnum::COLOR,
					  								"value" 				=> array ()
					  								),
													array("name" 			=> __('Heading Color Frontside', 'fusion-core'),
					  								"desc" 					=> __('Controls the heading color of the frontside. Leave blank for theme option selection.', 'fusion-core'),
					  								"id" 					=> "fusion_titlecolorfront[0]",
					  								"type" 					=> ElementTypeEnum::COLOR,
					  								"value" 				=> array ()
					  								),
													array("name" 			=> __('Text Color Frontside', 'fusion-core'),
					  								"desc" 					=> __('Controls the text color of the frontside. Leave blank for theme option selection.', 'fusion-core'),
					  								"id" 					=> "fusion_textcolorfront[0]",
					  								"type" 					=> ElementTypeEnum::COLOR,
					  								"value" 				=> array ()
					  								),
													array("name" 			=> __('Background Color Backside', 'fusion-core'),
					  								"desc" 					=> __('Controls the background color of the backside. Leave blank for theme option selection. NOTE: flip boxes must have background colors to work correctly in all browsers.', 'fusion-core'),
					  								"id" 					=> "fusion_backgroundcolorback[0]",
					  								"type" 					=> ElementTypeEnum::COLOR,
					  								"value" 				=> array ()
					  								),
													array("name" 			=> __('Heading Color Backside', 'fusion-core'),
					  								"desc" 					=> __('Controls the heading color of the backside. Leave blank for theme option selection.', 'fusion-core'),
					  								"id" 					=> "fusion_titlecolorback[0]",
					  								"type" 					=> ElementTypeEnum::COLOR,
					  								"value" 				=> array ()
					  								),
													array("name" 			=> __('Text Color Backside', 'fusion-core'),
					  								"desc" 					=> __('Controls the text color of the backside. Leave blank for theme option selection.', 'fusion-core'),
					  								"id" 					=> "fusion_textcolorback[0]",
					  								"type" 					=> ElementTypeEnum::COLOR,
					  								"value" 				=> array ()
					  								),
													array("name" 			=> __('Border Size', 'fusion-core'),
													"desc" 					=> __('In pixels (px), ex: 1px. Leave blank for theme option selection.', 'fusion-core'),
													"id" 					=> "fusion_bordersize[0]",
													"type" 					=> ElementTypeEnum::INPUT,
													"value" 				=> array("1px"),
													),
													array("name" 			=> __('Border Color', 'fusion-core'),
					  								"desc" 					=> __('Controls the border color. Leave blank for theme option selection.', 'fusion-core'),
					  								"id" 					=> "fusion_bordercolor[0]",
					  								"type" 					=> ElementTypeEnum::COLOR,
					  								"value" 				=> array ("")
					  								),
													array("name" 			=> __('Border Radius', 'fusion-core'),
					  								"desc" 					=> __('Controls the flip box border radius. In pixels (px), ex: 1px, or "round". Leave blank for theme option selection.', 'fusion-core'),
					  								"id" 					=> "fusion_borderradius[0]",
					  								"type" 					=> ElementTypeEnum::INPUT,
					  								"value" 				=> array ("4px")
					  								),
													array("name" 			=> __('Icon', 'fusion-core'),
					  								"desc" 					=> __('Click an icon to select, click again to deselect.', 'fusion-core'),
					  								"id" 					=> "fusion_icon[0]",
					  								"type" 					=> ElementTypeEnum::ICON_BOX,
					  								"value" 				=> array (""),
					  								"list"					=> FusionHelper::GET_ICONS_LIST()
					  								),
													array("name" 			=> __('Icon Color', 'fusion-core'),
					  								"desc" 					=> __('Controls the color of the icon. Leave blank for theme option selection.', 'fusion-core'),
					  								"id" 					=> "fusion_iconcolor[0]",
					  								"type" 					=> ElementTypeEnum::COLOR,
					  								"value" 				=> array ("")
					  								),
													array("name" 			=> __('Icon Circle', 'fusion-core'),
													  "desc" 				=> __('Choose to use a circled background on the icon.', 'fusion-core'),
													  "id" 					=> "fusion_circle[0]",
													  "type" 				=> ElementTypeEnum::SELECT,
													  "value" 				=> array( "yes"),
													  "allowedValues" 		=> $choices 
													  ),
													array("name" 			=> __('Icon Circle Background Color', 'fusion-core'),
					  								"desc" 					=> __('Controls the color of the circle. Leave blank for theme option selection.', 'fusion-core'),
					  								"id" 					=> "fusion_circlecolor[0]",
					  								"type" 					=> ElementTypeEnum::COLOR,
					  								"value" 				=> array ("")
					  								),
													
													array("name" 			=> __('Icon Circle Border Color', 'fusion-core'),
					  								"desc" 					=> __('Controls the color of the circle border. Leave blank for theme option selection.', 'fusion-core'),
					  								"id" 					=> "fusion_circlebordercolor[0]",
					  								"type" 					=> ElementTypeEnum::COLOR,
					  								"value" 				=> array ("")
					  								),
													array("name" 			=> __('Rotate Icon', 'fusion-core'),
													"desc" 					=> __('Choose to rotate the icon.', 'fusion-core'),
													"id" 					=> "fusion_rotate[0]",
													"type" 					=> ElementTypeEnum::SELECT,
													"value" 				=> "",
													"allowedValues" 		=> array('' 			=>'None',
																					'90' 			=>'90',
																					'180' 			=> '180',
																					'270'			=> '270')
													),
													array("name" 			=> __('Spinning Icon', 'fusion-core'),
													  "desc" 				=> __('Choose to let the icon spin.', 'fusion-core'),
													  "id" 					=> "fusion_iconspin[0]",
													  "type" 				=> ElementTypeEnum::SELECT,
													  "value" 				=> array( "no" ),
													  "allowedValues" 		=> $reverse_choices 
													  ),
													  array("name" 			=> __('Icon Image', 'fusion-core'),
					  									"desc" 				=> __('To upload your own icon image, deselect the icon above and then upload your icon image.', 'fusion-core'),
					  									"id" 				=> "fusion_image[0]",
					  									"type" 				=> ElementTypeEnum::UPLOAD,
					  									"upid" 				=> array(1),
					  									"value" 			=> array("")
					  								),
													array("name" 			=> __('Icon Image Width', 'fusion-core'),
					  									"desc" 				=> __('If using an icon image, specify the image width in pixels but do not add px, ex: 35.', 'fusion-core'),
					  									"id" 				=> "fusion_image_width[0]",
					  									"type" 				=> ElementTypeEnum::INPUT,
					  									"value" 			=> array ("35")
					  								),
													array("name" 			=> __('Icon Image Height', 'fusion-core'),
					  									"desc" 				=> __('If using an icon image, specify the image height in pixels but do not add px, ex: 35.', 'fusion-core'),
					  									"id" 				=> "fusion_image_height[0]",
					  									"type" 				=> ElementTypeEnum::INPUT,
					  									"value" 			=> array ("35")
					  								),
													array("name" 			=> __('Animation Type', 'fusion-core'),
													"desc" 					=> __('Select the type of animation to use on the shortcode.', 'fusion-core'),
													"id" 					=> "fusion_animation_type[0]",
													"type" 					=> ElementTypeEnum::SELECT,
													"value" 				=> array(""),
													"allowedValues" 		=> $animation_type
													),
													array("name" 			=> __('Direction of Animation', 'fusion-core'),
													"desc" 					=> __('Select the incoming direction for the animation.', 'fusion-core'),
													"id" 					=> "fusion_animation_direction[0]",
													"type" 					=> ElementTypeEnum::SELECT,
													"value" 				=> array(""),
													"allowedValues" 		=> $animation_direction
													),
													array("name" 			=> __('Speed of Animation', 'fusion-core'),
													"desc" 					=> __('Type in speed of animation in seconds (0.1 - 1).', 'fusion-core'),
													"id" 					=> "fusion_animation_speed[0]",
													"type" 					=> ElementTypeEnum::SELECT,
													"value" 				=> array(""),
													"allowedValues" 		=> $animation_speed
													),
													
													array("name" 			=> __( 'Offset of Animation', 'fusion-core' ),
														  "desc"			=> __( 'Choose when the animation should start.', 'fusion-core' ),
														  "id" 				=> "fusion_animation_offset[0]",
														  "type" 			=> ElementTypeEnum::SELECT,
														  "value" 			=> "",
														  "allowedValues" 	=> array(
					  															''					=> __( 'Default', 'fusion-core' ),														  
																				'top-into-view' 	=> __( 'Top of element hits bottom of viewport', 'fusion-core' ),
																				'top-mid-of-view' 	=> __( 'Top of element hits middle of viewport', 'fusion-core' ),
																				'bottom-in-view' 	=> __( 'Bottom of element enters viewport', 'fusion-core' ),
																				)
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
			
				array("name" 			=> __('Number of Columns', 'fusion-core'),
					  "desc" 			=> __('Set the number of columns per row.', 'fusion-core'),
					  "id" 				=> "fusion_columns",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "1",
					  "allowedValues" 	=> $no_of_columns
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
					  "buttonText"		=> __('Add New Flip Box', 'fusion-core'),
					  "id"				=> "am_fusion_content",
					  "elements" 		=> $am_array
					  ),
				);
		}
	}