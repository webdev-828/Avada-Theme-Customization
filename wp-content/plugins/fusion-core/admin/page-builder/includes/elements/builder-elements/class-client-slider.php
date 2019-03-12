<?php
/**
 * ClientSlider element implementation, it extends DDElementTemplate like all other elements
 */
	class TF_ClientSlider extends DDElementTemplate {
		public function __construct( $am_elements = array() ) {
			parent::__construct($am_elements);
		} 

		// Implementation for the element structure.
		public function create_element_structure() {
			
			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] 		= get_class($this);
			// element id
			$this->config['id']	   		= 'client_slider';
			// element name
			$this->config['name']	 		= __('Client Slider', 'fusion-core');
			// element icon
			$this->config['icon_url']  		= "icons/sc-text_block.png";
			// css class related to this element
			$this->config['css_class'] 		= "fusion_element_box";
			// element icon class
			$this->config['icon_class']		= 'fusion-icon builder-options-icon fusiona-users';
			// tooltip that will be displyed upon mouse over the element
			//$this->config['tool_tip']  		= 'Creates a Clients Slider';
			// any special html data attribute (i.e. data-width) needs to be passed
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] 			= array("drop_level"   => "4");
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {
			
			
			$innerHtml  = '<div class="fusion_iconbox textblock_element textblock_element_style" id="fusion_client_slider">';
			$innerHtml .= '<div class="bilder_icon_container"><span class="fusion_iconbox_icon"><i class="fusiona-users"></i><sub class="sub">'.__('Client Slider', 'fusion-core').'</sub><ul class="client_slider_elements"><li></li></ul></span></div>';
			$innerHtml .= '</div>';

			$this->config['innerHtml'] = $innerHtml;

		}
		
		//this function defines TextBlock sub elements or structure
		function popup_elements( $am_elements ) {

	  $am_array = array();

	  $am_array[] = array ( 
							array( "name"	 => __('Client Website Link', 'fusion-core'),
										"desc"		=> __('Add the url to client\'s website <br>ex: http://example.com', 'fusion-core'),
										"id"		=> "fusion_url[0]",
										"type"		=> ElementTypeEnum::INPUT,
										"value"	   => array() 
							),
						  array("name"	=> __('Button Target', 'fusion-core'),
									  "desc"		=> __('_self = open in same window<br>_blank = open in new window', 'fusion-core'),
									  "id"		=> "fusion_target[0]",
									  "type"		=> ElementTypeEnum::SELECT,
							"value"	   => array("_self"),
									  "allowedValues"   => array('_self'	=>'_self',
																 '_blank'	 =>'_blank') 
						  ),
						  array("name"	=> __('Client Image', 'fusion-core'),
									  "desc"		=> __('Upload the client image', 'fusion-core'),
									  "id"		=> "fusion_image[0]",
									  "type"		=> ElementTypeEnum::UPLOAD,
							"upid"		=> array(1),
									  "value"	   => array()
							),
						  array( "name"	 => __('Image Alt Text', 'fusion-core'),
										"desc"		=> __('The alt attribute provides alternative information if an image cannot be viewed', 'fusion-core'),
										"id"		=> "fusion_alt[0]",
										"type"		=> ElementTypeEnum::INPUT,
										"value"	   => array() 
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
				array("name" 			=> __('Picture Size', 'fusion-core'),
					  "desc"			=> __('fixed = width and height will be fixed<br>auto = width and height will adjust to the image.', 'fusion-core'),
					  "id" 				=> "fusion_picture_size",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> array( "fixed"),
					  "allowedValues" 	=> array('fixed'  =>__('Fixed', 'fusion-core'),
								  				 'auto' =>__('Auto', 'fusion-core'))
					   
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
					  "buttonText"		=> __('Add New Client Image', 'fusion-core'),
					  "id"				=> "am_fusion_client",
					  "elements" 		=> $am_array
					  ),
				);
		}
	}