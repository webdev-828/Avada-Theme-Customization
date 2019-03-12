<?php
/**
 * CounterCircle implementation, it extends DDElementTemplate like all other elements
 */
	class TF_CounterCircle extends DDElementTemplate {
		public function __construct( $am_elements = array() ) {
			parent::__construct($am_elements);
		} 
		
		// Implementation for the element structure.
		public function create_element_structure() {
			
			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] 		= get_class($this);
			// element id
			$this->config['id']	   		= 'counter_circle';
			// element name
			$this->config['name']	 		= __('Counter Circle', 'fusion-core');
			// element icon
			$this->config['icon_url']  		= "icons/sc-text_block.png";
			// css class related to this element
			$this->config['css_class'] 		= "fusion_element_box";
			// element icon class
			$this->config['icon_class']		= 'fusion-icon builder-options-icon fusiona-clock';
			// tooltip that will be displyed upon mouse over the element
			//$this->config['tool_tip']  		= 'Creates a Counter Circle';
			// any special html data attribute (i.e. data-width) needs to be passed
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] 			= array("drop_level"   => "4");
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {
			
			$innerHtml  = '<div class="fusion_iconbox textblock_element textblock_element_style">';
			$innerHtml .= '<div class="bilder_icon_container"><span class="fusion_iconbox_icon"><i class="fusiona-clock"></i><sub class="sub">'.__('Counter Circle', 'fusion-core').'</sub></span></div>';
			$innerHtml .= '</div>';

			$this->config['innerHtml'] = $innerHtml;
		}
		
		//this function defines TextBlock sub elements or structure
		function popup_elements( $am_elements ) {
			
			$fille_area_data 			= FusionHelper::fusion_create_dropdown_data( 1, 100 );
			$reverse_choices			= FusionHelper::get_reversed_choice_data();

	  $am_array = array();
	  $am_array[] = array ( 
							array( "name"	 => __('Filled Area Percentage', 'fusion-core'),
										"desc"		=> __('From 1% to 100%', 'fusion-core'),
										"id"		=> "fusion_value[0]",
										"type"		=> ElementTypeEnum::SELECT,
										"value"	   => array('') ,
						  "allowedValues"   => $fille_area_data
							),
						  array( "name"	 => __('Filled Color', 'fusion-core'),
										"desc"		=> __('Controls the color of the filled in area. Leave blank for theme option selection.', 'fusion-core'),
										"id"		=> "fusion_filledcolor[0]",
										"type"		=> ElementTypeEnum::COLOR,
										"value"	   => array('') 
							),
						  array( "name"	 => __('Unfilled Color', 'fusion-core'),
										"desc"		=> __('Controls the color of the unfilled in area. Leave blank for theme option selection.'),
										"id"		=> "fusion_unfilledcolor[0]",
										"type"		=> ElementTypeEnum::COLOR,
										"value"	   => array('') 
							),
						  array( "name"	 => __('Size of the Counter', 'fusion-core'),
										"desc"		=> __('Insert size of the counter in px. ex: 220', 'fusion-core'),
										"id"		=> "fusion_size[0]",
										"type"		=> ElementTypeEnum::INPUT,
										"value"	   => array("220") 
							),
						  array( "name"	 => __('Show Scales', 'fusion-core'),
										"desc"		=> __('Choose to show a scale around circles.', 'fusion-core'),
										"id"		=> "fusion_scales[0]",
										"type"		=> ElementTypeEnum::SELECT,
										"value"	   => array(''),
						  "allowedValues"   => $reverse_choices
							),
						  array( "name"	 => __('Countdown', 'fusion-core'),
										"desc"		=> __('Choose to let the circle filling move counter clockwise.', 'fusion-core'),
										"id"		=> "fusion_countdown[0]",
										"type"		=> ElementTypeEnum::SELECT,
										"value"	   => array('') ,
						  "allowedValues"   => $reverse_choices
							),
						  array( "name"	 => __('Animation Speed', 'fusion-core'),
										"desc"		=> __('Insert animation speed in milliseconds', 'fusion-core'),
										"id"		=> "fusion_speed[0]",
										"type"		=> ElementTypeEnum::INPUT,
										"value"	   => array("1500") 
							),
						  array( "name"	 => __('Counter Circle Text', 'fusion-core'),
										"desc"		=> __('Insert text for counter circle box, keep it short', 'fusion-core'),
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
					  "buttonText"		=> __('Add New Counter Circle', 'fusion-core'),
					  "id"				=> "am_fusion_circle",
					  "elements" 		=> $am_array
					  ),
				);
		}
	}