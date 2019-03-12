<?php
class FusionSC_WooFeaturedProductsSlider {

	public static $args;

	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		add_filter( 'fusion_attr_woo-featured-products-slider-shortcode', array( $this, 'attr' ) );
		add_filter( 'fusion_attr_woo-featured-products-slider-shortcode-carousel', array( $this, 'carousel_attr' ) );
		add_shortcode('featured_products_slider', array( $this, 'render' ) );

	}

	/**
	 * Render the shortcode
	 * @param  array $args	 Shortcode paramters
	 * @param  string $content Content between shortcode
	 * @return string		  HTML output
	 */
	function render( $args, $content = '' ) {
		global $woocommerce, $smof_data;

		$html = '';

		if( class_exists( 'Woocommerce' ) ) {

			$defaults = FusionCore_Plugin::set_shortcode_defaults(
				array(
					'class' 			=> '',
					'id' 				=> '',
					'autoplay'       	=> 'no',
					'carousel_layout'	=> 'title_on_rollover',
					'columns'        	=> '5',
					'column_spacing' 	=> '10',
					'mouse_scroll'   	=> 'no',
					'picture_size'    	=> 'auto',
					'scroll_items'		=> '',
					'show_buttons'    	=> 'yes',
					'show_cats'       	=> 'yes',
					'show_nav'        	=> 'yes',
					'show_price'      	=> 'yes',

					// Internal params
					'post_type' 		=> 'product',
					'posts_per_page' 	=> -1,
				), $args
			);

			$defaults['column_spacing'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['column_spacing'], '' );

			( $defaults['show_cats'] == "yes" ) ? ( $defaults['show_cats'] = 'enable' ) : ( $defaults['show_cats'] = 'disable' );
			( $defaults['show_price'] == "yes" ) ? ( $defaults['show_price'] = true ) : ( $defaults['show_price'] = false );
			( $defaults['show_buttons'] == "yes" ) ? ( $defaults['show_buttons'] = true ) : ( $defaults['show_buttons'] = false );

			extract( $defaults );

			self::$args = $defaults;

			$items_in_cart = array();

			if ( $woocommerce->cart && $woocommerce->cart->get_cart() && is_array( $woocommerce->cart->get_cart() ) ) {
				foreach ( $woocommerce->cart->get_cart() as $cart ) {
					$items_in_cart[] = $cart['product_id'];
				}
			}

			$design_class = 'fusion-' . Avada()->settings->get( 'woocommerce_product_box_design' ) . '-product-image-wrapper';

			if ( $picture_size == 'fixed' ) {
				$featured_image_size = 'shop_single';
			} else {
				$featured_image_size = 'full';
			}

			self::$args['meta_query']     = array(
				array(
					'key'     => '_featured',
					'compare' => '=',
					'value'   => 'yes'
				),
				array(
					'key' => '_visibility',
					'value' => array( 'catalog', 'visible' ),
					'compare' => 'IN'
				)						
			);

			$products = new WP_Query( self::$args );
			$product_list = '';

			if( $products->have_posts() ) {

				while( $products->have_posts() ) {
					$products->the_post();

					$id      = get_the_ID();
					$in_cart = in_array( $id, $items_in_cart );
					$image = $price_tag = $terms = '';

					// Title on rollover layout
					if ( $carousel_layout == 'title_on_rollover' ) {
						$image = avada_render_first_featured_image_markup( get_the_ID(), $featured_image_size, get_permalink( get_the_ID() ), TRUE, $show_price, $show_buttons, $show_cats );
						// Title below image layout
					} else {
						if ( $show_buttons == 'yes' ) {
							$image = avada_render_first_featured_image_markup( get_the_ID(), $featured_image_size, get_permalink( get_the_ID() ), TRUE, FALSE, $show_buttons, 'disable', 'disable' );
						} else {
							$image = avada_render_first_featured_image_markup( get_the_ID(), $featured_image_size, get_permalink( get_the_ID() ), TRUE, FALSE, $show_buttons, 'disable', 'disable', '', '', 'no' );
						}

						// Get the post title
						$image .= sprintf( '<h4 %s><a href="%s" target="%s">%s</a></h4>', FusionCore_Plugin::attributes( 'fusion-carousel-title' ), get_permalink( get_the_ID() ), '_self', get_the_title() );

						$image .= '<div class="fusion-carousel-meta">';

						// Get the terms
						if ( $show_cats == 'enable' ) {
							$image .= get_the_term_list( get_the_ID(), 'product_cat', '', ', ', '' );
						}

						// Check if we should render the woo product price
						if ( $show_price ) {
							ob_start();
							woocommerce_get_template( 'loop/price.php' );
							$image .= sprintf( '<div class="fusion-carousel-price">%s</div>', ob_get_clean() );
						}

						$image .= '</div>';
					}

					if( $in_cart ) {
						$product_list .= sprintf( '<li %s><div class="%s"><div %s>%s</div></div></li>', FusionCore_Plugin::attributes( 'fusion-carousel-item' ), $design_class . ' fusion-item-in-cart', FusionCore_Plugin::attributes( 'fusion-carousel-item-wrapper' ), $image );
					} else {
						$product_list .= sprintf( '<li %s><div class="%s"><div %s>%s</div></div></li>', FusionCore_Plugin::attributes( 'fusion-carousel-item' ), $design_class, FusionCore_Plugin::attributes( 'fusion-carousel-item-wrapper' ), $image );
					}
				}
			}
			wp_reset_query();

			$html = sprintf( '<div %s>', FusionCore_Plugin::attributes( 'woo-featured-products-slider-shortcode' ) );
			$html .= sprintf( '<div %s>', FusionCore_Plugin::attributes( 'woo-featured-products-slider-shortcode-carousel' ) );
			$html .= sprintf( '<div %s>', FusionCore_Plugin::attributes( 'fusion-carousel-positioner' ) );
			$html .= sprintf( '<ul %s>', FusionCore_Plugin::attributes( 'fusion-carousel-holder' ) );
			$html .= $product_list;
			$html .= '</ul>';
			// Check if navigation should be shown
			if ( $show_nav == 'yes' ) {
				$html .= sprintf( '<div %s><span %s></span><span %s></span></div>', FusionCore_Plugin::attributes( 'fusion-carousel-nav' ),
					FusionCore_Plugin::attributes( 'fusion-nav-prev' ), FusionCore_Plugin::attributes( 'fusion-nav-next' ) );
			}
			$html .= '</div>';
			$html .= '</div>';
			$html .= '</div>';


		}

		return $html;

	}

	function attr() {

		$attr['class'] = 'fusion-woo-featured-products-slider fusion-woo-slider';

		if( self::$args['class'] ) {
			$attr['class'] .= ' ' . self::$args['class'];
		}

		if( self::$args['id'] ) {
			$attr['id'] = self::$args['id'];
		}

		return $attr;

	}

		function carousel_attr() {

			$attr['class'] = 'fusion-carousel';

			if ( self::$args['carousel_layout'] == 'title_below_image' ) {
				$attr['class'] .= ' fusion-carousel-title-below-image';

				$attr['data-metacontent'] = 'yes';
			} else {
				$attr['class'] .= ' fusion-carousel-title-on-rollover';
			}

			$attr['data-autoplay']    = self::$args['autoplay'];
			$attr['data-columns']     = self::$args['columns'];
			$attr['data-itemmargin']  = self::$args['column_spacing'];
			$attr['data-itemwidth']   = 180;
			$attr['data-touchscroll'] = self::$args['mouse_scroll'];
			$attr['data-imagesize']   = self::$args['picture_size'];
			$attr['data-scrollitems'] = self::$args['scroll_items'];

			return $attr;
		}

}

new FusionSC_WooFeaturedProductsSlider();