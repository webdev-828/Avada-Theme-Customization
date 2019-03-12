<?php
/**
 * Table element implementation, it extends DDElementTemplate like all other elements
 */
	class TF_Table extends DDElementTemplate {
		public function __construct() {
			
			parent::__construct();
		} 
		
		// Implementation for the element structure.
		public function create_element_structure() {
			
			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] 		= get_class($this);
			// element id
			$this->config['id']	   		= 'table_box';
			// element name
			$this->config['name']	 		= __('Table', 'fusion-core');
			// element icon
			$this->config['icon_url']  		= "icons/sc-text_block.png";
			// css class related to this element
			$this->config['css_class'] 		= "fusion_element_box";
			// element icon class
			$this->config['icon_class']		= 'fusion-icon builder-options-icon fusiona-table';
			// tooltip that will be displyed upon mouse over the element
			//$this->config['tool_tip']  		= 'Creates a Table Element';
			// any special html data attribute (i.e. data-width) needs to be passed
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] 			= array("drop_level"   => "4");
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {
			
			$innerHtml  = '<div class="fusion_iconbox textblock_element textblock_element_style" id="fusion_table">';
			$innerHtml .= '<div class="bilder_icon_container"><span class="fusion_iconbox_icon"><i class="fusiona-table"></i><sub class="sub">'.__('Table', 'fusion-core').'</sub><p>style = <span class="table_style">1</span> columns = <font class="table_columns">4</font></p></span></div>';
			$innerHtml .= '</div>';
			$this->config['innerHtml'] = $innerHtml;
			
		}
		
		//this function defines TextBlock sub elements or structure
		function popup_elements() {
			$this->config['subElements'] = array(
			
			   array("name" 			=> __('Type', 'fusion-core'),
					  "desc" 			=> __('Select the table style', 'fusion-core'),
					  "id" 				=> "fusion_table_type",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "1",
					  "allowedValues" 	=> array('1' 			=> __('Style 1', 'fusion-core'),
												 '2' 			=> __('Style 2', 'fusion-core')) 
					 ),
					 
				 array("name" 			=> __('Number of Columns', 'fusion-core'),
					  "desc" 			=> __('Select how many columns to display', 'fusion-core'),
					  "id" 				=> "fusion_table_columns",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "1",
					  "allowedValues" 	=> array('1' 			=> '1 Column',
												 '2' 			=> '2 Columns',
												 '3'			=> '3 Columns',
												 '4'			=> '4 Columns',
												 '5'			=> '5 Columns') 
					 ),
					 
				array("name" 			=> __('Table', 'fusion-core'),
					  "desc" 			=> __(' ', 'fusion-core'),
					  "id" 				=> "fusion_table_content",
					  "type" 			=> ElementTypeEnum::HTML_EDITOR,
					  "value" 			=> ""),
				);
		}
	}