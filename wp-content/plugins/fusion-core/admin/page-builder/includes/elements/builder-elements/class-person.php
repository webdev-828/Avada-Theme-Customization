<?php
/**
 * Person element implementation, it extends DDElementTemplate like all other elements
 */
	class TF_Person extends DDElementTemplate {
		public function __construct() {

			parent::__construct();
		}

		// Implementation for the element structure.
		public function create_element_structure() {

			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class']  = get_class( $this );
			// element id
			$this->config['id']         = 'person_box';
			// element name
			$this->config['name']       = esc_html__( 'Person', 'fusion-core' );
			// element icon
			$this->config['icon_url']   = 'icons/sc-text_block.png';
			// css class related to this element
			$this->config['css_class']  = 'fusion_element_box';
			// element icon class
			$this->config['icon_class'] = 'fusion-icon builder-options-icon fusiona-user';
			// tooltip that will be displyed upon mouse over the element
			//$this->config['tool_tip']          = 'Creates a Person Element';
			// any special html data attribute ( i.e. data-width ) needs to be passed
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level,
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data']       = array( 'drop_level'   => '4' );
		}

		/**
		 * override default implemenation for this function as this element have special view
		 */
		public function create_visual_editor( $params ) {
			$this->config['innerHtml']  = '<div class="fusion_iconbox textblock_element textblock_element_style" id="fusion_person">';
				$this->config['innerHtml'] .= '<div class="bilder_icon_container">';
					$this->config['innerHtml'] .= '<span class="fusion_iconbox_icon">';
						$this->config['innerHtml'] .= '<i class="fusiona-user"></i>';
						$this->config['innerHtml'] .= '<sub class="sub">' . esc_html__( 'Person', 'fusion-core' ) . '</sub>';
						$this->config['innerHtml'] .= '<div class="img_frame_section">' . esc_html__( 'Image here', 'fusion-core' ) . '</div>';
						$this->config['innerHtml'] .= '<p class="person_name">Luke Beck</p>';
					$this->config['innerHtml'] .= '</span>';
				$this->config['innerHtml'] .= '</div>';
			$this->config['innerHtml'] .= '</div>';
		}

		/**
		 * this function defines TextBlock sub elements or structure
		 */
		function popup_elements() {

			$choices         = FusionHelper::get_shortcode_choices_with_default();
			$reverse_choices = FusionHelper::get_reversed_choice_data();

			$this->config['subElements'] = array(
				array(
					'name'          => esc_html__( 'Name', 'fusion-core' ),
					'desc'          => esc_html__( 'Insert the name of the person.', 'fusion-core' ),
					'id'            => 'fusion_name',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => '',
				),
				array(
					'name'          => esc_html__( 'Title', 'fusion-core' ),
					'desc'          => esc_html__( 'Insert the title of the person', 'fusion-core' ),
					'id'            => 'fusion_title',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => '',
				),
				array(
					'name'          => esc_html__( 'Profile Description', 'fusion-core' ),
					'desc'          => esc_html__( 'Enter the content to be displayed', 'fusion-core' ),
					'id'            => 'fusion_content_wp',
					'type'          => ElementTypeEnum::TEXTAREA,
					'value'         => '',
				),
				array(
					'name'          => esc_html__( 'Picture', 'fusion-core' ),
					'desc'          => esc_html__( 'Upload an image to display.', 'fusion-core' ),
					'id'            => 'fusion_picture',
					'upid'          => '1',
					'type'          => ElementTypeEnum::UPLOAD,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'Picture Link URL', 'fusion-core' ),
					'desc'          => esc_html__( 'Add the URL the picture will link to, ex: http://example.com.', 'fusion-core' ),
					'id'            => 'fusion_piclink',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'Link Target', 'fusion-core' ),
					'desc'          => __( '_self = open in same window<br>_blank = open in new window', 'fusion-core' ),
					'id'            => 'fusion_target',
					'type'          => ElementTypeEnum::SELECT,
					'value'         => '_self',
					'allowedValues' => array(
						'_self'  =>'_self',
						'_blank' =>'_blank'
					),
				),
				array(
					'name'          => esc_html__( 'Picture Style Type', 'fusion-core' ),
					'desc'          => esc_html__( 'Selected the style type for the picture.', 'fusion-core' ),
					'id'            => 'fusion_picstyle',
					'type'          => ElementTypeEnum::SELECT,
					'value'         => '',
					'allowedValues' => array(
						'none'         => esc_html__( 'None', 'fusion-core' ),
						'glow'         => esc_html__( 'Glow', 'fusion-core' ),
						'dropshadow'   => esc_html__( 'Drop Shadow', 'fusion-core' ),
						'bottomshadow' => esc_html__( 'Bottom Shadow', 'fusion-core' )
					),
				),
				array(
					'name'          => esc_html__( 'Hover Type', 'fusion-core' ),
					'desc'          => esc_html__( 'Select the hover effect type.', 'fusion-core' ),
					'id'            => 'fusion_hover_type',
					'type'          => ElementTypeEnum::SELECT,
					'value'         => 'none',
					'allowedValues' => array(
						'none'    => esc_html__( 'None', 'fusion-core' ),
						'zoomin'  => esc_html__( 'Zoom In', 'fusion-core' ),
						'zoomout' => esc_html__( 'Zoom Out', 'fusion-core' ),
						'liftup'  => esc_html__( 'Lift Up', 'fusion-core' ),
					),
				),
				array(
					'name'          => esc_html__( 'Background Color', 'fusion-core' ),
					'desc'          => esc_html__( 'Controls the background color. Leave blank for theme option selection', 'fusion-core' ),
					'id'            => 'fusion_background_color',
					'type'          => ElementTypeEnum::COLOR,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'Content Alignment', 'fusion-core' ),
					'desc'          => esc_html__( 'Choose the alignment of content. Choose default for theme option selection.', 'fusion-core' ),
					'id'            => 'fusion_alignment',
					'type'          => ElementTypeEnum::SELECT,
					'value'         => '',
					'allowedValues' => array(
						''       => esc_html__( 'Default', 'fusion-core' ),
						'left'   => esc_html__( 'Left', 'fusion-core' ),
						'center' => esc_html__( 'Center', 'fusion-core' ),
						'right'  => esc_html__( 'Right', 'fusion-core' ),
					),
				),
				array(
					'name'          => esc_html__( 'Picture Style Color', 'fusion-core' ),
					'desc'          => esc_html__( 'For all style types except border. Controls the style color. Leave blank for theme option selection.', 'fusion-core' ),
					'id'            => 'fusion_picstylecolor',
					'type'          => ElementTypeEnum::COLOR,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'Picture Border Size', 'fusion-core' ),
					'desc'          => esc_html__( 'In pixels ( px ), ex: 1px. Leave blank for theme option selection.', 'fusion-core' ),
					'id'            => 'fusion_picborder',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'Picture Border Color', 'fusion-core' ),
					'desc'          => esc_html__( 'Controls the picture\'s border color. Leave blank for theme option selection.', 'fusion-core' ),
					'id'            => 'fusion_picbordercolor',
					'type'          => ElementTypeEnum::COLOR,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'Picture Border Radius', 'fusion-core' ),
					'desc'          => esc_html__( 'Choose the border radius of the person image. In pixels ( px ), ex: 1px, or "round".  Leave blank for theme option selection.', 'fusion-core' ),
					'id'            => 'fusion_picborderradius',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'Social Icons Position', 'fusion-core' ),
					'desc'          => esc_html__( 'Choose the social icon position. Choose default for theme option selection.', 'fusion-core' ),
					'id'            => 'fusion_icon_position',
					'type'          => ElementTypeEnum::SELECT,
					'value'         => '',
					'allowedValues' => array(
						''       => esc_html__( 'Default', 'fusion-core' ),
						'top'    => esc_html__( 'Top', 'fusion-core' ),
						'bottom' => esc_html__( 'Bottom', 'fusion-core' ),
					),
				),
				array(
					'name'          => esc_html__( 'Boxed Social Icons', 'fusion-core' ),
					'desc'          => esc_html__( 'Choose to get a boxed icons. Choose default for theme option selection.', 'fusion-core' ),
					'id'            => 'fusion_iconboxed',
					'type'          => ElementTypeEnum::SELECT,
					'value'         => '',
					'allowedValues' => $choices
				),
				array(
					'name'          => esc_html__( 'Social Icon Box Radius', 'fusion-core' ),
					'desc'          => esc_html__( 'Choose the border radius of the boxed icons. In pixels ( px ), ex: 1px, or "round". Leave blank for theme option selection.', 'fusion-core' ),
					'id'            => 'fusion_iconboxedradius',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => ''
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
				array(
					'name'          => esc_html__( 'Social Icon Custom Colors', 'fusion-core' ),
					'desc'          => esc_html__( 'Specify the color of social icons. Leave blank for theme option selection.', 'fusion-core' ),
					'id'            => 'fusion_iconcolor',
					'type'          => ElementTypeEnum::TEXTAREA,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'Social Icon Custom Box Colors', 'fusion-core' ),
					'desc'          => esc_html__( 'Specify the box color of social icons. Leave blank for theme option selection.', 'fusion-core' ),
					'id'            => 'fusion_boxcolor',
					'type'          => ElementTypeEnum::TEXTAREA,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'Social Icon Tooltip Position', 'fusion-core' ),
					'desc'          => esc_html__( 'Choose the display position for tooltips. Choose default for theme option selection.', 'fusion-core' ),
					'id'            => 'fusion_icontooltip',
					'type'          => ElementTypeEnum::SELECT,
					'value'         => '',
					'allowedValues' => array(
						''       => esc_html__( 'Default', 'fusion-core' ),
						'top'    => esc_html__( 'Top', 'fusion-core' ),
						'bottom' => esc_html__( 'Bottom', 'fusion-core' ),
						'left'   => esc_html__( 'Left', 'fusion-core' ),
						'Right'  => esc_html__( 'Right', 'fusion-core' ),
					),
				),
				array(
					'name'          => esc_html__( 'Email Address', 'fusion-core' ),
					'desc'          => esc_html__( 'Insert an email address to display the email icon', 'fusion-core' ),
					'id'            => 'fusion_email',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'Facebook Link', 'fusion-core' ),
					'desc'          => esc_html__( 'Insert your custom Facebook link', 'fusion-core' ),
					'id'            => 'fusion_facebook',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'Twitter Link', 'fusion-core' ),
					'desc'          => esc_html__( 'Insert your custom Twitter link', 'fusion-core' ),
					'id'            => 'fusion_twitter',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'Instagram Link', 'fusion-core' ),
					'desc'          => esc_html__( 'Insert your custom Instagram link', 'fusion-core' ),
					'id'            => 'fusion_instagram',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'Dribbble Link', 'fusion-core' ),
					'desc'          => esc_html__( 'Insert your custom Dribbble link', 'fusion-core' ),
					'id'            => 'fusion_dribbble',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'Google+ Link', 'fusion-core' ),
					'desc'          => esc_html__( 'Insert your custom Google+ link', 'fusion-core' ),
					'id'            => 'fusion_google',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'LinkedIn Link', 'fusion-core' ),
					'desc'          => esc_html__( 'Insert your custom LinkedIn link', 'fusion-core' ),
					'id'            => 'fusion_linkedin',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'Blogger Link', 'fusion-core' ),
					'desc'          => esc_html__( 'Insert your custom Blogger link', 'fusion-core' ),
					'id'            => 'fusion_blogger',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'Tumblr Link', 'fusion-core' ),
					'desc'          => esc_html__( 'Insert your custom Tumblr link', 'fusion-core' ),
					'id'            => 'fusion_tumblr',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'Reddit Link', 'fusion-core' ),
					'desc'          => esc_html__( 'Insert your custom Reddit link', 'fusion-core' ),
					'id'            => 'fusion_reddit',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'Yahoo Link', 'fusion-core' ),
					'desc'          => esc_html__( 'Insert your custom Yahoo link', 'fusion-core' ),
					'id'            => 'fusion_yahoo',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'Deviantart Link', 'fusion-core' ),
					'desc'          => esc_html__( 'Insert your custom Deviantart link', 'fusion-core' ),
					'id'            => 'fusion_deviantart',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'Vimeo Link', 'fusion-core' ),
					'desc'          => esc_html__( 'Insert your custom Vimeo link', 'fusion-core' ),
					'id'            => 'fusion_vimeo',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'Youtube Link', 'fusion-core' ),
					'desc'          => esc_html__( 'Insert your custom Youtube link', 'fusion-core' ),
					'id'            => 'fusion_youtube',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'Pinterst Link', 'fusion-core' ),
					'desc'          => esc_html__( 'Insert your custom Pinterest link', 'fusion-core' ),
					'id'            => 'fusion_pinterest',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'RSS Link', 'fusion-core' ),
					'desc'          => esc_html__( 'Insert your custom RSS link', 'fusion-core' ),
					'id'            => 'fusion_rss',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'Digg Link', 'fusion-core' ),
					'desc'          => esc_html__( 'Insert your custom Digg link', 'fusion-core' ),
					'id'            => 'fusion_digg',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'Flickr Link', 'fusion-core' ),
					'desc'          => esc_html__( 'Insert your custom Flickr link', 'fusion-core' ),
					'id'            => 'fusion_flickr',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'Forrst Link', 'fusion-core' ),
					'desc'          => esc_html__( 'Insert your custom Forrst link', 'fusion-core' ),
					'id'            => 'fusion_forrst',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'Myspace Link', 'fusion-core' ),
					'desc'          => esc_html__( 'Insert your custom Myspace link', 'fusion-core' ),
					'id'            => 'fusion_myspace',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'Skype Link', 'fusion-core' ),
					'desc'          => esc_html__( 'Insert your custom Skype link', 'fusion-core' ),
					'id'            => 'fusion_skype',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'PayPal Link', 'fusion-core' ),
					'desc'          => esc_html__( 'Insert your custom PayPal link', 'fusion-core' ),
					'id'            => 'fusion_paypal',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'Dropbox Link', 'fusion-core' ),
					'desc'          => esc_html__( 'Insert your custom Dropbox link', 'fusion-core' ),
					'id'            => 'fusion_dropbox',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'SoundCloud Link', 'fusion-core' ),
					'desc'          => esc_html__( 'Insert your custom SoundCloud link', 'fusion-core' ),
					'id'            => 'fusion_soundcloud',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'VK Link', 'fusion-core' ),
					'desc'          => esc_html__( 'Insert your custom VK link', 'fusion-core' ),
					'id'            => 'fusion_vk',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'Xing Link', 'fusion-core' ),
					'desc'          => esc_html__( 'Insert your custom Xing link', 'fusion-core' ),
					'id'            => 'fusion_xing',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'Show Custom Social Icon', 'fusion-core' ),
					'desc'          => esc_html__( 'Show the custom social icon specified in Theme Options', 'fusion-core' ),
					'id'            => 'fusion_show_custom',
					'type'          => ElementTypeEnum::SELECT,
					'value'         => 'no',
					'allowedValues' => $reverse_choices
				),
				array(
					'name'          => esc_html__( 'CSS Class', 'fusion-core' ),
					'desc'          => esc_html__( 'Add a class to the wrapping HTML element.', 'fusion-core' ),
					'id'            => 'fusion_class',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => ''
				),
				array(
					'name'          => esc_html__( 'CSS ID', 'fusion-core' ),
					'desc'          => esc_html__( 'Add an ID to the wrapping HTML element.', 'fusion-core' ),
					'id'            => 'fusion_id',
					'type'          => ElementTypeEnum::INPUT,
					'value'         => ''
				),
			);
		}
	}
