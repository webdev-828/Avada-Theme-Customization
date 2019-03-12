<?php

	/**
	 * Youtube element implementation, it extends DDElementTemplate like all other elements
	 */
	class TF_Youtube extends DDElementTemplate {
		public function __construct() {

			parent::__construct();
		}

		// Implementation for the element structure.
		public function create_element_structure() {

			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] = get_class( $this );
			// element id
			$this->config['id'] = 'video_youtube';
			// element shortcode base
			$this->config['base'] = 'youtube';
			// element name
			$this->config['name'] = __( 'Youtube', 'fusion-core' );
			// element icon
			$this->config['icon_url'] = "icons/sc-text_block.png";
			// css class related to this element
			$this->config['css_class'] = "fusion_element_box";
			// element icon class
			$this->config['icon_class'] = 'fusion-icon builder-options-icon fusiona-youtube';
			// tooltip that will be displyed upon mouse over the element
			//$this->config['tool_tip']  		= 'Creates a Video Element';
			// any special html data attribute (i.e. data-width) needs to be passed
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] = array( "drop_level" => "4" );
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {

			$innerHtml = '<div class="fusion_iconbox textblock_element textblock_element_style">';
			$innerHtml .= '<div class="bilder_icon_container"><span class="fusion_iconbox_icon"><i class="fusiona-youtube"></i><sub class="sub">' . __( 'Youtube', 'fusion-core' ) . '</sub><p class="youtube_url">http://www.youtube.com/LOfeCR7KqUs</p></span></div>';
			$innerHtml .= '</div>';
			$this->config['innerHtml'] = $innerHtml;

		}

		//this function defines TextBlock sub elements or structure
		function popup_elements() {

			$reverse_choices = FusionHelper::get_reversed_choice_data();

			$this->config['subElements'] = array(

				array(
					"name"  => __( 'Video ID', 'fusion-core' ),
					"desc"  => "For example the Video ID for<br>http://www.youtube.com/LOfeCR7KqUs is <br>LOfeCR7KqUs",
					"id"    => "id",
					"type"  => ElementTypeEnum::INPUT,
					"value" => ""
				),
				array(
					"name"  => __( 'Width', 'fusion-core' ),
					"desc"  => __( 'In pixels but only enter a number, ex: 600', 'fusion-core' ),
					"id"    => "width",
					"type"  => ElementTypeEnum::INPUT,
					"value" => "600"
				),
				array(
					"name"  => __( 'Height', 'fusion-core' ),
					"desc"  => __( 'In pixels but only enter a number, ex: 350', 'fusion-core' ),
					"id"    => "height",
					"type"  => ElementTypeEnum::INPUT,
					"value" => "350"
				),
				array(
					"name"          => __( 'Autoplay Video', 'fusion-core' ),
					"desc"          => __( 'Set to yes to make video autoplaying', 'fusion-core' ),
					"id"            => "autoplay",
					"type"          => ElementTypeEnum::SELECT,
					"value"         => "no",
					"allowedValues" => $reverse_choices
				),
				array(
					"name"  => __( 'AdditionalAPI Parameter', 'fusion-core' ),
					"desc"  => __( 'Use additional API parameter, for example &rel=0 to disable related videos', 'fusion-core' ),
					"id"    => "api_params",
					"type"  => ElementTypeEnum::INPUT,
					"value" => ""
				),
				array(
					"name"  => __( 'CSS Class', 'fusion-core' ),
					"desc"  => __( 'Add a class to the wrapping HTML element.', 'fusion-core' ),
					"id"    => "class",
					"type"  => ElementTypeEnum::INPUT,
					"value" => ""
				),
			);
		}
	}