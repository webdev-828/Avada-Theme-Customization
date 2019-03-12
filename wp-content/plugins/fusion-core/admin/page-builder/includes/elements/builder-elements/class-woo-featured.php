<?php

	/**
	 * WooFeatured element implementation, it extends DDElementTemplate like all other elements
	 */
	class TF_WooFeatured extends DDElementTemplate {
		public function __construct() {

			parent::__construct();
		}

		// Implementation for the element structure.
		public function create_element_structure() {

			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] = get_class( $this );
			// element id
			$this->config['id'] = 'woo_featured';
			// element shortcode base
			$this->config['base'] = 'featured_products_slider';
			// element name
			$this->config['name'] = __( 'Woo Featured', 'fusion-core' );
			// element icon
			$this->config['icon_url'] = "icons/sc-text_block.png";
			// css class related to this element
			$this->config['css_class'] = "fusion_element_box";
			// element icon class
			$this->config['icon_class'] = 'fusion-icon builder-options-icon fusiona-star-empty';
			// tooltip that will be displyed upon mouse over the element
			//$this->config['tool_tip']  		= 'Creates a Woo Featured Element';
			// any special html data attribute (i.e. data-width) needs to be passed
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] = array( "drop_level" => "4" );
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {

			$innerHtml = '<div class="fusion_iconbox textblock_element textblock_element_style">';
			$innerHtml .= '<div class="bilder_icon_container"><span class="fusion_iconbox_icon"><i class="fusiona-star-empty"></i><sub class="sub">' . __( 'Woo Featured', 'fusion-core' ) . '</sub></span></div>';
			$innerHtml .= '</div>';
			$this->config['innerHtml'] = $innerHtml;
		}

		//this function defines TextBlock sub elements or structure
		function popup_elements() {
			$no_of_columns           = FusionHelper::fusion_create_dropdown_data( 1, 6 );
			$choices                 = FusionHelper::get_shortcode_choices();		
		
			$this->config['subElements'] = array(
				array(
					"name"          => __( 'Picture Size', 'fusion-core' ),
					"desc"          => __( 'fixed = width and height will be fixed<br>auto = width and height will adjust to the image.', 'fusion-core' ),
					"id"            => "picture_size",
					"type"          => ElementTypeEnum::SELECT,
					"value"         => "auto",
					"allowedValues" => array(
						'fixed' => __( 'Fixed', 'fusion-core' ),
						'auto'  => __( 'Auto', 'fusion-core' )
					)
				),
				array(
					"name"          => __( 'Carousel Layout', 'fusion-core' ),
					"desc"          => __( 'Choose to show titles on rollover image, or below image.', 'fusion-core' ),
					"id"            => "carousel_layout",
					"type"          => ElementTypeEnum::SELECT,
					"value"         => "title_on_rollover",
					"allowedValues" => array(
						'title_on_rollover' => __( 'Title on rollover', 'fusion-core' ),
						'title_below_image' => __( 'Title below image', 'fusion-core' )
					)
				),
				array(
					"name"          => __( 'Carousel Autoplay', 'fusion-core' ),
					"desc"          => __( 'Choose to autoplay the carousel.', 'fusion-core' ),
					"id"            => "autoplay",
					"type"          => ElementTypeEnum::SELECT,
					"value"         => "no",
					"allowedValues" => array(
						'yes' => __( 'Yes', 'fusion-core' ),
						'no'  => __( 'No', 'fusion-core' )
					)
				),
				array(
					"name"          => __( 'Maximum Columns', 'fusion-core' ),
					"desc"          => __( 'Select the number of max columns to display.', 'fusion-core' ),
					"id"            => "columns",
					"type"          => ElementTypeEnum::SELECT,
					"value"         => "5",
					"allowedValues" => $no_of_columns
				),
				array(
					"name"  => __( 'Column Spacing', 'fusion-core' ),
					"desc"  => __( "Insert the amount of spacing between items without 'px'. ex: 13.", 'fusion-core' ),
					"id"    => "column_spacing",
					"type"  => ElementTypeEnum::INPUT,
					"value" => "10",
				),
				array(
					"name" 			=> __('Carousel Scroll Items', 'fusion-core'),
					"desc" 			=> __("Insert the amount of items to scroll. Leave empty to scroll number of visible items.", 'fusion-core'),
					"id" 			=> "fusion_scroll_items",
					"type" 			=> ElementTypeEnum::INPUT,
					"value" 		=> "",	
				),				
				array(
					"name"          => __( 'Carousel Show Navigation', 'fusion-core' ),
					"desc"          => __( 'Choose to show navigation buttons on the carousel.', 'fusion-core' ),
					"id"            => "navigation",
					"type"          => ElementTypeEnum::SELECT,
					"value"         => "yes",
					"allowedValues" => array(
						'yes' => __( 'Yes', 'fusion-core' ),
						'no'  => __( 'No', 'fusion-core' )
					)
				),
				array(
					"name"          => __( 'Carousel Mouse Scroll', 'fusion-core' ),
					"desc"          => __( 'Choose to enable mouse drag control on the carousel.', 'fusion-core' ),
					"id"            => "mouse_scroll",
					"type"          => ElementTypeEnum::SELECT,
					"value"         => "no",
					"allowedValues" => array(
						'yes' => __( 'Yes', 'fusion-core' ),
						'no'  => __( 'No', 'fusion-core' )
					)
				),
				array(
					"name"          => __( 'Show Categories', 'fusion-core' ),
					"desc"          => __( 'Choose to show or hide the categories', 'fusion-core' ),
					"id"            => "show_cats",
					"type"          => ElementTypeEnum::SELECT,
					"value"         => "yes",
					"allowedValues" => $choices
				),
				array(
					"name"          => __( 'Show Price', 'fusion-core' ),
					"desc"          => __( 'Choose to show or hide the price', 'fusion-core' ),
					"id"            => "show_price",
					"type"          => ElementTypeEnum::SELECT,
					"value"         => "yes",
					"allowedValues" => $choices
				),
				array(
					"name"          => __( 'Show Buttons', 'fusion-core' ),
					"desc"          => __( 'Choose to show or hide the icon buttons', 'fusion-core' ),
					"id"            => "show_buttons",
					"type"          => ElementTypeEnum::SELECT,
					"value"         => "yes",
					"allowedValues" => $choices
				),
				array(
					"name"  => __( 'CSS Class', 'fusion-core' ),
					"desc"  => __( 'Add a class to the wrapping HTML element.', 'fusion-core' ),
					"id"    => "class",
					"type"  => ElementTypeEnum::INPUT,
					"value" => ""
				),
				array(
					"name"  => __( 'CSS ID', 'fusion-core' ),
					"desc"  => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' ),
					"id"    => "id",
					"type"  => ElementTypeEnum::INPUT,
					"value" => ""
				),
			);
		}
	}