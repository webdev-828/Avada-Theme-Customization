<?php
/**
 * ImageCarousel implementation, it extends DDElementTemplate like all other elements
 */
	class TF_ImageCarousel extends DDElementTemplate {
		public function __construct( $am_elements = array() ) {
			parent::__construct($am_elements);
		}
		
		// Implementation for the element structure.
		public function create_element_structure() {
			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] 		= get_class($this);
			// element id
			$this->config['id']	   		= 'image_carousel';
			// element name
			$this->config['name']	 		= __('Image Carousel', 'fusion-core');
			// element icon
			$this->config['icon_url']  		= "icons/sc-text_block.png";
			// css class related to this element
			$this->config['css_class'] 		= "fusion_element_box";
			// element icon class
			$this->config['icon_class']		= 'fusion-icon builder-options-icon fusiona-images';
			// tooltip that will be displyed upon mouse over the element
			//$this->config['tool_tip']  		= 'Creates an Image Coursel';
			// any special html data attribute (i.e. data-width) needs to be passed
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] 			= array("drop_level"   => "4");
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {
			
			$innerHtml  = '<div class="fusion_iconbox textblock_element textblock_element_style" id="fusion_image_carousel">';
			$innerHtml .= '<div class="bilder_icon_container"><span class="fusion_iconbox_icon"><i class="fusiona-images"></i><sub class="sub">'.__('Image Carousel', 'fusion-core').'</sub><ul class="image_carousel_elements"><li></li><li></li><li></li><li></li><li></li></ul></span></div>';
			$innerHtml .= '</div>';
			$this->config['innerHtml'] = $innerHtml;
		}
		
		//this function defines TextBlock sub elements or structure
		function popup_elements( $am_elements ) {
			$no_of_columns 				= FusionHelper::fusion_create_dropdown_data( 1 , 6 );

	 		$am_array = array();
	  		$am_array[] = array ( 
							array( "name"	 => __('Image Website Link', 'fusion-core'),
										"desc"		=> __('Add the url to image\'s website. If lightbox option is enabled, you have to add the full image link to show it in the lightbox.', 'fusion-core'),
										"id"		=> "fusion_url[0]",
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
						  array( "name"	 => __('Image', 'fusion-core'),
										"desc"		=> __('Upload an image to display.', 'fusion-core'),
										"id"		=> "fusion_image[0]",
										"type"		=> ElementTypeEnum::UPLOAD,
										"upid"		=> array(1),
									  	"value"	   => array()									
							),
						  array( "name"	 => __('Image Alt Text', 'fusion-core'),
										"desc"		=> __('The alt attribute provides alternative information if an image cannot be viewed.', 'fusion-core'),
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
					  "desc" 			=> __('fixed = width and height will be fixed<br>auto = width and height will adjust to the image.', 'fusion-core'),
					  "id" 				=> "fusion_picture_size",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "fixed",
					  "allowedValues" 	=> array('fixed' 				=> __('Fixed', 'fusion-core'),
												 'auto' 				=> __('Auto', 'fusion-core')) 
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

				array("name" 			=> __('Autoplay', 'fusion-core'),
					  "desc" 			=> __('Choose to autoplay the carousel.', 'fusion-core'),
					  "id" 				=> "fusion_autoplay",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "no",
					  "allowedValues" 	=> array('yes' 				=> __('Yes', 'fusion-core'),
												 'no' 				=> __('No', 'fusion-core')) 
					  ),			  	
			  	
				array("name" 			=> __('Maximum Columns', 'fusion-core'),
					  "desc" 			=> __('Select the number of max columns to display.', 'fusion-core'),
					  "id" 				=> "fusion_columns",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "5",
					  "allowedValues" 	=> $no_of_columns
					  ),
					  
				array("name" 			=> __('Column Spacing', 'fusion-core'),
					  "desc" 			=> __("Insert the amount of spacing between items without 'px'. ex: 13.", 'fusion-core'),
					  "id" 				=> "fusion_column_spacing",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "13",
					 ),				 
					 
				array("name" 			=> __('Scroll Items', 'fusion-core'),
					  "desc" 			=> __("Insert the amount of items to scroll. Leave empty to scroll number of visible items.", 'fusion-core'),
					  "id" 				=> "fusion_scroll_items",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "",
					 ),						 
			  	
				array("name" 			=> __('Show Navigation', 'fusion-core'),
					  "desc" 			=> __('Choose to show navigation buttons on the carousel.', 'fusion-core'),
					  "id" 				=> "fusion_navigation",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "yes",
					  "allowedValues" 	=> array('yes' 				=> __('Yes', 'fusion-core'),
												 'no' 				=> __('No', 'fusion-core')) 
					  ),	
					  
				array("name" 			=> __('Mouse Scroll', 'fusion-core'),
					  "desc" 			=> __('Choose to enable mouse drag control on the carousel. IMPORTANT: For easy draggability, when mouse scroll is activated, links will be disabled.', 'fusion-core'),
					  "id" 				=> "fusion_mouse_scroll",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "no",
					  "allowedValues" 	=> array('yes' 				=> __('Yes', 'fusion-core'),
												 'no' 				=> __('No', 'fusion-core')) 
					  ),	
					  
				array("name" 			=> __('Border', 'fusion-core'),
					  "desc" 			=> __('Choose to enable a border around the images.', 'fusion-core'),
					  "id" 				=> "fusion_border",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "yes",
					  "allowedValues" 	=> array('yes' 				=> __('Yes', 'fusion-core'),
												 'no' 				=> __('No', 'fusion-core')) 
					  ),
 	
				array("name" 			=> __('Image lightbox', 'fusion-core'),
					  "desc" 			=> __('Show image in lightbox.', 'fusion-core'),
					  "id" 				=> "fusion_lightbox",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "yes",
					  "allowedValues" 	=> array('yes' 				=> __('Yes', 'fusion-core'),
												 'no' 				=> __('No', 'fusion-core')) 
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
					  "buttonText"		=> __('Add New Image', 'fusion-core'),
					  "id"				=> "am_fusion_image",
					  "elements" 		=> $am_array
											
					  )
					  
				);
		}
	}