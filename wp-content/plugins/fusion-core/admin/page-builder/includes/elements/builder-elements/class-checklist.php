<?php
/**
 * CheckList element implementation, it extends DDElementTemplate like all other elements
 */
	class TF_CheckList extends DDElementTemplate {

		public function __construct( $am_elements = array() ) {
			parent::__construct($am_elements);
		} 
		
		// Implementation for the element structure.
		public function create_element_structure() {
			
			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] 		= get_class($this);
			// element id
			$this->config['id']	   		= 'check_list';
			// element name
			$this->config['name']	 		= __('Checklist', 'fusion-core');
			// element icon
			$this->config['icon_url']  		= "icons/sc-text_block.png";
			// css class related to this element
			$this->config['css_class'] 		= "fusion_element_box";
			// element icon class
			$this->config['icon_class']		= 'fusion-icon builder-options-icon fusiona-list-ul';
			// tooltip that will be displyed upon mouse over the element
			//$this->config['tool_tip']  		= 'Creates a Checklist';
			// any special html data attribute (i.e. data-width) needs to be passed
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] 			= array("drop_level"   => "4");
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {
			
			$innerHtml  = '<div class="fusion_iconbox textblock_element textblock_element_style" id="fusion_ckecklist">';
			$innerHtml .= '<div class="bilder_icon_container"><span class="fusion_iconbox_icon"><i class="fusiona-list-ul"></i><sub class="sub">'.__('Checklist', 'fusion-core').'</sub><ul class="checklist_elements"><li><i class="fusiona-list-ul"></i> checklist preview text here</li><li><i class="fusiona-list-ul"></i> checklist preview text here</li><li><i class="fusiona-list-ul"></i> checklist preview text here</li></ul></span></div>';
			$innerHtml .= '</div>';

			$this->config['innerHtml'] = $innerHtml;
		}
		
		//this function defines TextBlock sub elements or structure
		function popup_elements( $am_elements ) {
			
			$choices					= FusionHelper::get_shortcode_choices_with_default();
			
	  $am_array = array();

	  $am_array[] = array ( 
						  array("name"	  => __('Select Icon', 'fusion-core'),
									  "desc"		  => __('This setting will override the global setting above. Leave blank for theme option selection.', 'fusion-core'),
									  "id"		  => "fusion_icon[0]",
									  "type"		  => ElementTypeEnum::ICON_BOX,
									  "value"		 => array (""),
							"list"		  => FusionHelper::GET_ICONS_LIST()
							),
						  array( "name"	   => __('List Item Content', 'fusion-core'),
										"desc"		  => __('Add list item content', 'fusion-core'),
										"id"		  => "fusion_content_wp[0]",
										"type"		  => ElementTypeEnum::HTML_EDITOR,
										"value"		 => array('') 
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
				array("name" 			=> __('Select Icon', 'fusion-core'),
					  "desc" 			=> __('Global setting for all list items, this can be overridden individually below. Click an icon to select, click again to deselect.', 'fusion-core'),
					  "id" 				=> "icon",
					  "type" 			=> ElementTypeEnum::ICON_BOX,
					  "value" 			=> "fa-check",
					  "list"			=> FusionHelper::GET_ICONS_LIST()
					  ),
					  
						  array("name"	  => __('Icon Color', 'fusion-core'),
									  "desc"		  => __('Global setting for all list items. Leave blank for theme option selection. Defines the icon color.', 'fusion-core'),
									  "id"		  => "fusion_iconcolor",
									  "type"		  => ElementTypeEnum::COLOR,
									  "value"		 => ''
							),

				array("name" 			=> __('Icon in Circle', 'fusion-core'),
					  "desc" 			=> __('Global setting for all list items. Set to default for theme option selection. Choose to have icons in circles.', 'fusion-core'),
					  "id" 				=> "fusion_circle",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues" 	=> $choices 
					  ),
					  
						   array("name"	   => __('Circle Color', 'fusion-core'),
									  "desc"		  => __('Global setting for all list items. Leave blank for theme option selection. Defines the circle color.', 'fusion-core'),
									  "id"		  => "fusion_circlecolor",
									  "type"		  => ElementTypeEnum::COLOR,
									  "value"		 => ''
							), 
					  
				array("name" 			=> __('Item Size', 'fusion-core'),
					  "desc" 			=> __('Select the list item\'s size. In pixels (px), ex: 13px.', 'fusion-core'),
					  "id" 				=> "fusion_size",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "13px",
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
					  "buttonText"		=> __('Add New List Item', 'fusion-core'),
					  "id"				=> "am_fusion_content",
					  "elements" 		=> $am_array
					  ),
				);

		}
	}