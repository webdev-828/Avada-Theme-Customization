<?php

	/**
	 * WooShortcodes element implementation, it extends DDElementTemplate like all other elements
	 */
	class TF_WooShortcodes extends DDElementTemplate {
		public function __construct() {

			parent::__construct();
		}

		// Implementation for the element structure.
		public function create_element_structure() {

			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] = get_class( $this );
			// element id
			$this->config['id'] = 'woo_shortcodes';
			// element name
			$this->config['name'] = __( 'Woo Shortcodes', 'fusion-core' );
			// element icon
			$this->config['icon_url'] = "icons/sc-text_block.png";
			// css class related to this element
			$this->config['css_class'] = "fusion_element_box";
			// element icon class
			$this->config['icon_class'] = 'fusion-icon builder-options-icon fusiona-shopping-cart';
			// tooltip that will be displyed upon mouse over the element
			//$this->config['tool_tip']  		= 'Creates a Woo Featured Element';
			// any special html data attribute (i.e. data-width) needs to be passed
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] = array( "drop_level" => "4" );
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {

			$innerHtml = '<div class="fusion_iconbox textblock_element textblock_element_style" id="fusion_shortcodes">';
			$innerHtml .= '<div class="bilder_icon_container"><span class="fusion_iconbox_icon"><i class="fusiona-shopping-cart"></i><sub class="sub">' . __( 'Woo Shortcodes', 'fusion-core' ) . '</sub><p class="woo_shortcode">[woocommerce_order_tracking]</p></span></div>';
			$innerHtml .= '</div>';
			$this->config['innerHtml'] = $innerHtml;
		}

		//this function defines TextBlock sub elements or structure
		function popup_elements() {
			$this->config['subElements'] = array(
				array(
					"name"          => __( 'Shortocode', 'fusion-core' ),
					"desc"          => __( 'Choose woocommerce shortcode', 'fusion-core' ),
					"id"            => "fusion_woo_shortocode",
					"type"          => ElementTypeEnum::SELECT,
					"value"         => "",
					"allowedValues" => array(
						'1' => __( 'Order tracking', 'fusion-core' ),
						'2' => __( 'Product price/cart button', 'fusion-core' ),
						'3' => __( 'Product by SKU/ID', 'fusion-core' ),
						'4' => __( 'Products by SKU/ID', 'fusion-core' ),
						'5' => __( 'Product categories', 'fusion-core' ),
						'6' => __( 'Products by category slug', 'fusion-core' ),
						'7' => __( 'Recent products', 'fusion-core' ),
						'8' => __( 'Featured products', 'fusion-core' ),
						'9' => __( 'Shop Message', 'fusion-core' )
					)
				),
				array(
					"name"  => __( 'Shortcode content', 'fusion-core' ),
					"desc"  => __( 'Shortcode will appear here', 'fusion-core' ),
					"id"    => "fusion_woo_shortocde_content",
					"type"  => ElementTypeEnum::TEXTAREA,
					"value" => "[woocommerce_order_tracking]"
				),
			);
		}
	}