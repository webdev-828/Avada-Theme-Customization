<?php
/**
 * CounterBox implementation, it extends DDElementTemplate like all other elements
 */
	class TF_CounterBox extends DDElementTemplate {
		public function __construct( $am_elements = array() ) {
			parent::__construct($am_elements);
		}
		
		// Implementation for the element structure.
		public function create_element_structure() {
			
			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] 		= get_class($this);
			// element id
			$this->config['id']	   		= 'counter_box';
			// element name
			$this->config['name']	 		= __('Counter Box', 'fusion-core');
			// element icon
			$this->config['icon_url']  		= "icons/sc-text_block.png";
			// css class related to this element
			$this->config['css_class'] 		= "fusion_element_box";
			// element icon class
			$this->config['icon_class']		= 'fusion-icon builder-options-icon fusiona-browser';
			// tooltip that will be displyed upon mouse over the element
			//$this->config['tool_tip']  		= 'Creates a Counter Box';
			// any special html data attribute (i.e. data-width) needs to be passed
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] 			= array("drop_level"   => "4");
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {
			
			$innerHtml  = '<div class="fusion_iconbox textblock_element textblock_element_style" id="fusion_counter_box">';
			$innerHtml .= '<div class="bilder_icon_container"><span class="fusion_iconbox_icon"><i class="fusiona-browser"></i><sub class="sub">'.__('Counter Box', 'fusion-core').'</sub><p>columns = <font class="counter_box_columns">5</font></p></span></div>';
			$innerHtml .= '</div>';

			$this->config['innerHtml'] = $innerHtml;
		}
		
		//this function defines TextBlock sub elements or structure
		function popup_elements( $am_elements ) {
			
			$fille_area_data 			= FusionHelper::fusion_create_dropdown_data( 1, 100 );
			$no_of_columns 				= FusionHelper::fusion_create_dropdown_data(1,6);
			$choices					= FusionHelper::get_shortcode_choices();
			
	  $am_array = array();
	  $am_array[] = array ( 
							array( "name"	 => __('Counter Value', 'fusion-core'),
										"desc"		=> __('The number to which the counter will animate.', 'fusion-core'),
										"id"		=> "fusion_value[0]",
										"type"		=> ElementTypeEnum::INPUT,
										"value"	   => array("") 
							),
							
						  array( "name"	 => __('Delimiter Digit', 'fusion-core'),
										"desc"		=> __('Insert a delimiter digit for better readability. ex: ,', 'fusion-core'),
										"id"		=> "fusion_delimiter[0]",
										"type"		=> ElementTypeEnum::INPUT,
										"value"	   => array("") 
							),								
							
						  array( "name"	 => __('Counter Box Unit', 'fusion-core'),
										"desc"		=> __('Insert a unit for the counter. ex %', 'fusion-core'),
										"id"		=> "fusion_unit[0]",
										"type"		=> ElementTypeEnum::INPUT,
										"value"	   => array("") 
							),
						  array("name"	=> __('Unit Position', 'fusion-core'),
									  "desc"		=> __('Choose the positioning of the unit.', 'fusion-core'),
									  "id"		=> "fusion_unitpos[0]",
									  "type"		=> ElementTypeEnum::SELECT,
							"value"	   => array(""),
									  "allowedValues"   => array('suffix'   =>__('After Counter', 'fusion-core'),
																 'prefix'   =>__('Before Counter', 'fusion-core')) 
						  ),
						  array( "name"	 => __('Icon', 'fusion-core'),
										"desc"		=> __('Click an icon to select, click again to deselect', 'fusion-core'),
										"id"		=> "icon[0]",
										"type"		=> ElementTypeEnum::ICON_BOX,
										"value"	   => array() ,
						  "list"		=> FusionHelper::GET_ICONS_LIST()
							),
						  array("name"	=> __('Counter Direction', 'fusion-core'),
									  "desc"		=> __('Choose to count up or down.', 'fusion-core'),
									  "id"		=> "fusion_direction[0]",
									  "type"		=> ElementTypeEnum::SELECT,
							"value"	   => array(""),
									  "allowedValues"   => array('up'	 =>__('Count Up', 'fusion-core'),
																 'down'   =>__('Count Down', 'fusion-core')) 
						  ),
						  array( "name"	 => __('Counter Box Text', 'fusion-core'),
										"desc"		=> __('Insert text for counter box', 'fusion-core'),
										"id"		=> "fusion_content[0]",
										"type"		=> ElementTypeEnum::INPUT,
										"value"	   => array("Text") 
							)
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
					  "value" 			=> "4",
					  "allowedValues" 	=> $no_of_columns 
					  ),
				  array("name"	=> __('Counter Box Title Font Color', 'fusion-core'),
							  "desc"		=> __('Controls the color of the counter "value" and icon. Leave blank for theme option styling.', 'fusion-core'),
							  "id"		=> "fusion_color",
							  "type"		=> ElementTypeEnum::COLOR,
							  "value"	   => array(),
				  ),
				array("name" 			=> __('Counter Box Title Font Size', 'fusion-core'),
					  "desc"			=> __('Controls the size of the counter "value" and icon. Enter the font size without \'px\' ex: 50. Leave blank for theme option styling.', 'fusion-core'),
					  "id" 				=> "fusion_title_size",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
					  ),
				array("name" 			=> __('Counter Box Icon Size', 'fusion-core'),
					  "desc"			=> __('Controls the size of the icon. Enter the font size without \'px\'. Default is 50. Leave blank for theme option styling.', 'fusion-core'),
					  "id" 				=> "fusion_icon_size",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
					  ),
				array("name" 			=> __('Counter Box Icon Top', 'fusion-core'),
					  "desc"			=> __('Controls the position of the icon. Select Default for theme option styling.', 'fusion-core'),
					  "id" 				=> "fusion_icon_top",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues"   => array( '' => __( 'Default', 'fusion-core' ), 'no' => __( 'No', 'fusion-core' ), 'yes' => __( 'Yes', 'fusion-core' ) )
					  ),
				  array("name"	=> __('Counter Box Body Font Color', 'fusion-core'),
							  "desc"		=> __('Controls the color of the counter body text. Leave blank for theme option styling.', 'fusion-core'),
							  "id"		=> "fusion_body_color",
							  "type"		=> ElementTypeEnum::COLOR,
							  "value"	   => array(),
				  ),
				array("name" 			=> __('Counter Box Body Font Size', 'fusion-core'),
					  "desc"			=> __('Controls the size of the counter body text. Enter the font size without \'px\' ex: 13. Leave blank for theme option styling.', 'fusion-core'),
					  "id" 				=> "fusion_body_size",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
					  ),
				  array("name"	=> __('Counter Box Border Color', 'fusion-core'),
							  "desc"		=> __('Controls the color of the border.', 'fusion-core'),
							  "id"		=> "fusion_border_color",
							  "type"		=> ElementTypeEnum::COLOR,
							  "value"	   => array(),
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
				array("type" 			=> ElementTypeEnum::ADDMORE,
					  "buttonText"		=> __('Add New Counter Box', 'fusion-core'),
					  "id"				=> "cb_fusion_box",
					  "elements" 		=> $am_array
											
					  )
				);
		}
	}