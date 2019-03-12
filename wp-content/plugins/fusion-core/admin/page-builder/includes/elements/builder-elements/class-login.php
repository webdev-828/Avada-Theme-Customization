<?php
/**
 * Login implementation, it extends DDElementTemplate like all other elements
 */
	class TF_Login extends DDElementTemplate {
		
		public function __construct() {
			 
			parent::__construct();
		}

		// Implementation for the element structure.
		public function create_element_structure() {
			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] 	= get_class( $this );
			// element id
			$this->config['id']	   		= 'user_login';
			// element name
			$this->config['name']	 	= __('User Login', 'fusion-core');
			// element icon
			$this->config['icon_url']  		= "icons/sc-text_block.png";
			// css class related to this element
			$this->config['css_class'] 		= "fusion_element_box";
			// element icon class
			$this->config['icon_class']		= 'fusion-icon builder-options-icon dashicons dashicons-lock';
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] 		= array("drop_level"   => "4");
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {

			$innerHtml  = '<div class="fusion_iconbox textblock_element textblock_element_style" id="fusion_countdown">';
			$innerHtml .= '<div class="builder_icon_container"><span class="fusion_iconbox_icon"><i class="dashicons dashicons-lock"></i><sub class="sub">'. $this->config['name'] .'</sub>';
			$innerHtml .= '<div class="fusion-login-box fusion_login" style="display:none;">' . __( 'Login Element' ) . '</div>';
			$innerHtml .= '<div class="fusion-login-box fusion_register" style="display:none;">' . __( 'Register Element' ) . '</div>';
			$innerHtml .= '<div class="fusion-login-box fusion_lost_password" style="display:none;">' . __( 'Lost Password Element' ) . '</div>';
			$innerHtml .= '</span></div></div>';
			$this->config['innerHtml'] = $innerHtml;
		}
		
		//this function defines TextBlock sub elements or structure
		function popup_elements() {

			$choices_with_default = FusionHelper::get_shortcode_choices_with_default();
			
			$this->config['subElements'] = array(
			
				array(
						"name" 			=> __('Login Elements', 'fusion-core'),
						"desc" 			=> __('Choose the login element you want to use.', 'fusion-core'),
						"id" 			=> "fusion_login_type",
						"type"          => ElementTypeEnum::SELECT,
						"value"         => "fusion_login",
						"allowedValues" => array(
							'fusion_fusion_login' 			=> __( 'Login Element', 'fusion-core' ),
							'fusion_fusion_register' 		=> __( 'Register Element', 'fusion-core' ),
							'fusion_fusion_lost_password' 	=> __( 'Lost Password Element', 'fusion-core' )
						)
				),
				
				array("name" 			=> __('Text Align', 'fusion-core'),
					  "desc" 			=> __('Choose the alignment of all content parts. "Text Flow" follows the default text align of the site. "Center" will center all elements.', 'fusion-core'),
					  "id" 				=> "fusion_textflow",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value"         	=> "fusion_login",
					  "allowedValues" 	=> array(
					  							''				=> __( 'Default', 'fusion-core' ),
					  							'textflow'		=> __( 'Text Flow', 'fusion-core' ),
					  							'center' 		=> __( 'Center', 'fusion-core' )
											) 
				),			
				
				array("name" 			=> __('Heading', 'fusion-core'),
					  "desc"			=> __('Choose a heading text.', 'fusion-core'),
					  "id" 				=> "fusion_heading_text",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
				),
					  
				array("name" 			=> __('Caption', 'fusion-core'),
					  "desc"			=> __('Choose a caption text.', 'fusion-core'),
					  "id" 				=> "fusion_caption_text",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
				),				  
				
				array("name" 			=> __('Button Span', 'fusion-core'),
					  "desc" 			=> __('Choose to have the button span the full width.', 'fusion-core'),
					  "id" 				=> "fusion_button_span",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "default",
					  "allowedValues" 	=> $choices_with_default
				),
				
				array("name" 			=> __('Form Backgound Color', 'fusion-core'),
					  "desc" 			=> __('Choose a background color for the form wrapping box.', 'fusion-core'),
					  "id" 				=> "fusion_background_color",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
				),					
					 
				array("name" 			=> __('Heading Color', 'fusion-core'),
					  "desc" 			=> __('Choose a heading color. Leave empty for Theme Option default.', 'fusion-core'),
					  "id" 				=> "fusion_heading_color",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
				),
					
				array("name" 			=> __('Caption Color', 'fusion-core'),
					  "desc" 			=> __('Choose a caption color. Leave empty for Theme Option default.', 'fusion-core'),
					  "id" 				=> "fusion_caption_color",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
				),
				
				array("name" 			=> __('Link Color', 'fusion-core'),
					  "desc" 			=> __('Choose a link color. Leave empty for Theme Option default.', 'fusion-core'),
					  "id" 				=> "fusion_link_color",
					  "type" 			=> ElementTypeEnum::COLOR,
					  "value" 			=> ""
				),
					
				array("name" 			=> __('Redirection Link', 'fusion-core'),
					  "desc" 			=> __('Add the url to which a user should redirected after form submission. Leave empty to use the same page.', 'fusion-core'),
					  "id" 				=> "fusion_redirection",
					  "type"			=> ElementTypeEnum::INPUT,
					  "value" 			=> ""
				),
					  
				array("name" 			=> __('Register Link', 'fusion-core'),
					  "desc" 			=> __('Add the url the "Register" link should open.', 'fusion-core'),
					  "id" 				=> "fusion_register",
					  "type"			=> ElementTypeEnum::INPUT,
					  "value" 			=> ""
				),		
					  
				array("name" 			=> __('Lost Password Link', 'fusion-core'),
					  "desc" 			=> __('Add the url the "Lost Password" link should open.', 'fusion-core'),
					  "id" 				=> "fusion_lost_password",
					  "type"			=> ElementTypeEnum::INPUT,
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
				)
				
			);
		}
	}
