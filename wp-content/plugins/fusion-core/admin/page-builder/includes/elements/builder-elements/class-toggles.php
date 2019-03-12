<?php
/**
 * Toggles element implementation, it extends DDElementTemplate like all other elements
 */
	class TF_Toggles extends DDElementTemplate {
		public function __construct( $am_elements = array() ) {
			parent::__construct($am_elements);
		}
		
		// Implementation for the element structure.
		public function create_element_structure() {
			
			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] 		= get_class($this);
			// element id
			$this->config['id']	   		= 'toggles_box';
			// element name
			$this->config['name']	 		= __('Toggles', 'fusion-core');
			// element icon
			$this->config['icon_url']  		= "icons/sc-text_block.png";
			// css class related to this element
			$this->config['css_class'] 		= "fusion_element_box";
			// element icon class
			$this->config['icon_class']		= 'fusion-icon builder-options-icon fusiona-expand-alt';
			// tooltip that will be displyed upon mouse over the element
			//$this->config['tool_tip']  		= 'Creates a Toggles Element';
			// any special html data attribute (i.e. data-width) needs to be passed
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] 			= array("drop_level"   => "4");
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {
			
			$innerHtml  = '<div class="fusion_iconbox textblock_element textblock_element_style" id="fusion_toggles">';
			$innerHtml .= '<div class="bilder_icon_container"><span class="fusion_iconbox_icon"><i class="fusiona-expand-alt"></i><sub class="sub">'.__('Toggles', 'fusion-core').'</sub><ul class="toggles_content"><li>Toggle title here</li><li>Toggle title here</li><li>Toggle title here</li></ul></span></div>';
			$innerHtml .= '</div>';
			$this->config['innerHtml'] = $innerHtml;

		}
		
		//this function defines TextBlock sub elements or structure
		function popup_elements( $am_elements ) {

	  $am_array = array();
	  $am_array[] = array ( 
							array( "name"	 => __('Title', 'fusion-core'),
										"desc"		=> __('Insert the toggle title', 'fusion-core'),
										"id"		=> "fusion_title[0]",
										"type"		=> ElementTypeEnum::INPUT,
										"value"	   => array()
							),
						  array( "name"	 => __('Open by Default', 'fusion-core'),
										"desc"		=> __('Choose to have the toggle open when page loads', 'fusion-core'),
										"id"		=> "fusion_open[0]",
										"type"		=> ElementTypeEnum::SELECT,
										"value"	   => array('no') ,
									  "allowedValues"   => array('no'	 => __('No', 'fusion-core'),
																'yes'	 => __('Yes', 'fusion-core'))
							),
						  array( "name"	 => __('Toggle Content', 'fusion-core'),
										"desc"		=> __('Insert the toggle content', 'fusion-core'),
										"id"		=> "fusion_content_wp[0]",
										"type"		=> ElementTypeEnum::HTML_EDITOR,
										"value"	   => array("") 
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
			
			  	array("name"	 		=> __('Divider Line', 'fusion-core'),
					  "desc"			=> __('Choose to display a divider line between each item.', 'fusion-core'),
					  "id"				=> "fusion_divider_line[0]",
					  "type"			=> ElementTypeEnum::SELECT,
					  "value"	   		=> array('') ,
					  "allowedValues"   => array(''		=> __('Default', 'fusion-core'),
					  							 'yes'	=> __('Yes', 'fusion-core'),
					  							 'no'	=> __('No', 'fusion-core')
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
					  "buttonText"		=> __('Add New Toggle', 'fusion-core'),
					  "id"				=> "am_fusion_toggle",
					  "elements" 		=> $am_array
					  ),
				);
		}
	}