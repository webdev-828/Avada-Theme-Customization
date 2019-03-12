<?php
/**
 * SharingBox implementation, it extends DDElementTemplate like all other elements
 */
	class TF_SharingBox extends DDElementTemplate {
		public function __construct() {

			parent::__construct();
		}

		// Implementation for the element structure.
		public function create_element_structure() {

			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] 		= get_class($this);
			// element id
			$this->config['id']	   		= 'sharing_box';
			// element name
			$this->config['name']	 		= __('Sharing Box', 'fusion-core');
			// element icon
			$this->config['icon_url']  		= "icons/sc-text_block.png";
			// css class related to this element
			$this->config['css_class'] 		= "fusion_element_box";
			// element icon class
			$this->config['icon_class']		= 'fusion-icon builder-options-icon fusiona-share2';
			// tooltip that will be displyed upon mouse over the element
			//$this->config['tool_tip']  		= 'Creates a Sharing Box';
			// any special html data attribute (i.e. data-width) needs to be passed
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level,
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] 			= array("drop_level"   => "4");
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {


			$innerHtml  = '<div class="fusion_iconbox textblock_element textblock_element_style" id="fusion_sharing_box">';
			$innerHtml .= '<div class="bilder_icon_container"><span class="fusion_iconbox_icon"><i class="fusiona-share2"></i><sub class="sub">'.__('Sharing Box', 'fusion-core').'</sub><p class="sharing_tagline">This Is The Text Title Is Entered</p></span></div>';
			$innerHtml .= '</div>';
			$this->config['innerHtml'] = $innerHtml;
		}

		//this function defines TextBlock sub elements or structure
		function popup_elements() {

			$reverse_choices			= FusionHelper::get_shortcode_choices_with_default();

			$this->config['subElements'] = array(

				array("name" 			=> __('Tagline', 'fusion-core'),
					  "desc" 			=> __('The title tagline that will display', 'fusion-core'),
					  "id" 				=> "fusion_tagline",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> __('Share This Story, Choose Your Platform!', 'fusion-core')
					  ),

				array("name" 			=> __('Tagline Color', 'fusion-core'),
					  "desc" 			=> __('Controls the text color. Leave blank for theme option selection', 'fusion-core'),
					  "id" 				=> "fusion_taglinecolor",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
					  ),

		array("name"	  => __('Background Color', 'fusion-core'),
					  "desc"	  => __('Controls the background color. Leave blank for theme option selection.', 'fusion-core'),
					  "id"		=> "fusion_backgroundcolor",
					  "type"	  => ElementTypeEnum::COLOR,
					  "value"	   => ""
			),

				array("name" 			=> __('Title', 'fusion-core'),
					  "desc" 			=> __('The post title that will be shared', 'fusion-core'),
					  "id" 				=> "fusion_title",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> ""
					  ),

				array("name" 			=> __('Link to Share', 'fusion-core'),
					  "desc" 			=> "",
					  "id" 				=> "fusion_link",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> ""
					  ),

				array("name" 			=> __('Description', 'fusion-core'),
					  "desc" 			=> __('The description that will be shared', 'fusion-core'),
					  "id" 				=> "fusion_description",
					  "type" 			=> ElementTypeEnum::TEXTAREA,
					  "value" 			=> ""
					  ),

				array("name" 			=> __('Boxed Social Icons', 'fusion-core'),
					  "desc" 			=> __('Choose to get a boxed icons. Choose default for theme option selection.', 'fusion-core'),
					  "id" 				=> "fusion_iconboxed",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues" 	=> $reverse_choices
					  ),

				array("name" 			=> __('Social Icon Box Radius', 'fusion-core'),
					  "desc" 			=> __('Choose the radius of the boxed icons. In pixels (px), ex: 1px, or "round". Leave blank for theme option selection.', 'fusion-core'),
					  "id" 				=> "fusion_iconboxedradius",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "4px"
					  ),
				array(
					'name'          => esc_html__( 'Social Icon Color Type', 'fusion-core' ),
					'desc'          => esc_html__( 'Controls the color type of the social icons. Choose default for theme option selection.', 'fusion-core' ),
					'id'            => 'fusion_iconcolortype',
					'type'          => ElementTypeEnum::SELECT,
					'value'         => '',
					'allowedValues' => array(
						''       => esc_html__( 'Default', 'fusion-core' ),
						'custom' => esc_html__( 'Custom Colors', 'fusion-core' ),
						'brand'  => esc_html__( 'Brand Colors', 'fusion-core' ),
					)
				),
				array("name" 			=> __('Social Icon Custom Colors', 'fusion-core'),
					  "desc" 			=> __('Specify the color of social icons. Leave blank for theme option selection.', 'fusion-core'),
					  "id" 				=> "fusion_iconcolor",
					  "type" 			=> ElementTypeEnum::TEXTAREA,
					  "value" 			=> ""
					  ),

				array("name" 			=> __('Social Icon Custom Box Colors', 'fusion-core'),
					  "desc" 			=> __('Specify the box color of social icons. Leave blank for theme option selection.', 'fusion-core'),
					  "id" 				=> "fusion_boxcolor",
					  "type" 			=> ElementTypeEnum::TEXTAREA,
					  "value" 			=> ""
					  ),

				array("name" 			=> __('Social Icon Tooltip Position', 'fusion-core'),
					  "desc" 			=> __('Choose the display position for tooltips. Choose default for theme option selection.', 'fusion-core'),
					  "id" 				=> "fusion_icontooltip",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues" 	=> array('' 			=> 'Default',
												 'top' 			=> __('Top', 'fusion-core'),
												 'bottom' 		=> __('Bottom', 'fusion-core'),
												 'left' 		=> __('Left', 'fusion-core'),
												 'Right' 		=> __('Right', 'fusion-core'))
					 ),

				array("name" 			=> __('Choose Image to Share on Pinterest', 'fusion-core'),
					  "desc" 			=> "",
					  "id" 				=> "fusion_pinterest_image",
					  "type" 			=> ElementTypeEnum::UPLOAD,
					  "upid" 			=> "1",
					  "value" 			=> ""
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