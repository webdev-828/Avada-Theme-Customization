<?php
/**
 * PricingTable implementation, it extends DDElementTemplate like all other elements
 */
	class TF_PricingTable extends DDElementTemplate {
		public function __construct() {
			
			parent::__construct();
		} 
		
		// Implementation for the element structure.
		public function create_element_structure() {
			
			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] 		= get_class($this);
			// element id
			$this->config['id']	   		= 'pricing_table';
			// element name
			$this->config['name']	 		= __('Pricing Table', 'fusion-core');
			// element icon
			$this->config['icon_url']  		= "icons/sc-text_block.png";
			// css class related to this element
			$this->config['css_class'] 		= "fusion_element_box";
			// element icon class
			$this->config['icon_class']		= 'fusion-icon builder-options-icon fusiona-dollar';
			// tooltip that will be displyed upon mouse over the element
			//$this->config['tool_tip']  		= 'Creates Prcing Table Element';
			// any special html data attribute (i.e. data-width) needs to be passed
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] 			= array("drop_level"   => "4");
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {
			
			
			$innerHtml  = '<div class="fusion_iconbox textblock_element textblock_element_style" id="fusion_pricing_table">';
			$innerHtml .= '<div class="bilder_icon_container"><span class="fusion_iconbox_icon"><i class="fusiona-dollar"></i><sub class="sub">'.__('Pricing Table', 'fusion-core').'</sub><p>style = <span class="pricing_table_style">1</span> columns = <font class="pricing_table_columns">4</font></p></span></div>';
			$innerHtml .= '</div>';
			$this->config['innerHtml'] = $innerHtml;
		}
		
		//this function defines TextBlock sub elements or structure
		function popup_elements() {
			$this->config['subElements'] = array(
			   array("name" 			=> __('Type', 'fusion-core'),
					  "desc" 			=> __('Select the type of pricing table', 'fusion-core'),
					  "id" 				=> "fusion_pricing_table_type",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "1",
					  "allowedValues" 	=> array('1' 			=>'Style 1',
											   	 '2' 			=>'Style 2') 
					  ),
					  
				array("name" 			=> __('Background Color', 'fusion-core'),
					  "desc" 			=> __('Leave blank for default', 'fusion-core'),
					  "id" 				=> "fusion_pricing_table_backgroundcolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Border Color', 'fusion-core'),
					  "desc" 			=> __('Leave blank for default', 'fusion-core'),
					  "id" 				=> "fusion_pricing_table_bordercolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Divider Color', 'fusion-core'),
					  "desc" 			=> __('Leave blank for default', 'fusion-core'),
					  "id" 				=> "fusion_pricing_table_dividercolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),
					  
				array("name" 			=> __('Number of Columns', 'fusion-core'),
					  "desc" 			=> __('Select how many columns to display', 'fusion-core'),
					  "id" 				=> "fusion_pricing_table_columns",
					  "type" 			=> ElementTypeEnum::SELECT,
					"value" 			=> "<br />[pricing_column title='Standard'][pricing_price currency='$' price='15.55' time='monthly'][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]<br />",
					"allowedValues" 	=> array("<br />[pricing_column title='Standard'][pricing_price currency='$' price='15.55' time='monthly'][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]<br />" 			=>'1 Column',
					"<br />[pricing_column title='Standard'][pricing_price currency='$' price='15.55' time='monthly'][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]<br />[pricing_column title='Standard'][pricing_price currency='$' price='15.55' time='monthly'][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]<br />" 			=>'2 Columns',
					"<br />[pricing_column title='Standard'][pricing_price currency='$' price='15.55' time='monthly'][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]<br />[pricing_column title='Standard'][pricing_price currency='$' price='15.55' time='monthly'][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]<br />[pricing_column title='Standard'][pricing_price currency='$' price='15.55' time='monthly'][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]<br />" => '3 Columns',
					"<br />[pricing_column title='Standard'][pricing_price currency='$' price='15.55' time='monthly'][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]<br />[pricing_column title='Standard'][pricing_price currency='$' price='15.55' time='monthly'][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]<br />[pricing_column title='Standard'][pricing_price currency='$' price='15.55' time='monthly'][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]<br />[pricing_column title='Standard'][pricing_price currency='$' price='15.55' time='monthly'][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]<br />" => '4 Columns',
					"<br />[pricing_column title='Standard'][pricing_price currency='$' price='15.55' time='monthly'][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]<br />[pricing_column title='Standard'][pricing_price currency='$' price='15.55' time='monthly'][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]<br />[pricing_column title='Standard'][pricing_price currency='$' price='15.55' time='monthly'][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]<br />[pricing_column title='Standard'][pricing_price currency='$' price='15.55' time='monthly'][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]<br />[pricing_column title='Standard'][pricing_price currency='$' price='15.55' time='monthly'][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]<br />" => '5 Columns',
					"<br />[pricing_column title='Standard'][pricing_price currency='$' price='15.55' time='monthly'][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]<br />[pricing_column title='Standard'][pricing_price currency='$' price='15.55' time='monthly'][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]<br />[pricing_column title='Standard'][pricing_price currency='$' price='15.55' time='monthly'][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]<br />[pricing_column title='Standard'][pricing_price currency='$' price='15.55' time='monthly'][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]<br />[pricing_column title='Standard'][pricing_price currency='$' price='15.55' time='monthly'][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]<br />[pricing_column title='Standard'][pricing_price currency='$' price='15.55' time='monthly'][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]<br />" => '6 Columns') 
					  ),
					  
				array("name" 			=> __('CSS Class', 'fusion-core'),
					  "desc"			=> __('Add a class to the wrapping HTML element.', 'fusion-core'),
					  "id" 				=> "fusion_pricing_table_class",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
					  ),
					  
				array("name" 			=> __('CSS ID', 'fusion-core'),
					  "desc"			=> __('Add an ID to the wrapping HTML element.', 'fusion-core'),
					  "id" 				=> "fusion_pricing_table_id",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
					  ),
					  
				array("name" 			=> __('Short Code', 'fusion-core'),
					  "desc" 			=> __('Pricing Table short code content', 'fusion-core'),
					  "id" 				=> "fusion_pricing_table_content",
					  "type" 			=> ElementTypeEnum::HTML_EDITOR,
					  "value" 			=> '[fusion_text][pricing_table type="1" backgroundcolor="" bordercolor="" dividercolor="" class="" id=""]<br />
[pricing_column title="Standard"][pricing_price currency="$" price="15.55" time="monthly"][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]<br />
[/pricing_table][/fusion_text]'),

				);
		}
	}