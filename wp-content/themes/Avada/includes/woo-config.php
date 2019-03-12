<?php
	/**
	 * Fusion Framework
	 * WARNING: This file is part of the Fusion Core Framework.
	 * Do not edit the core files.
	 * Add any modifications necessary under a child theme.
	 *
	 * @package  Fusion/Template
	 * @author   ThemeFusion
	 * @link     http://theme-fusion.com
	 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

// Don't duplicate me!
if ( ! class_exists( 'FusionTemplateWoo' ) ) {

	/**
	 * Class to apply woocommerce templates
	 *
	 * @since 4.0.0
	 */
	class FusionTemplateWoo {

		function __construct() {

			add_filter( 'woocommerce_show_page_title', array( $this, 'shop_title' ), 10 );

			remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
			remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
			add_action( 'woocommerce_before_main_content', array( $this, 'before_container' ), 10 );
			add_action( 'woocommerce_after_main_content', array( $this, 'after_container' ), 10 );

			remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
			add_action( 'woocommerce_sidebar', array( $this, 'add_sidebar' ), 10 );

			/**
			 * Products Loop
			 */
			remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
			remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );

			add_action( 'woocommerce_shop_loop_item_title', array( $this, 'product_title' ), 10 );
			add_action( 'avada_woocommerce_buttons_on_rollover',  array( $this, 'avada_woocommerce_template_loop_add_to_cart' ), 10 );
			add_action( 'avada_woocommerce_buttons_on_rollover',  array( $this, 'avada_woocommerce_rollover_buttons_linebreak' ), 15 );
			add_action( 'avada_woocommerce_buttons_on_rollover', array( $this, 'show_details_button' ), 20 );

			if ( Avada()->settings->get( 'woocommerce_product_box_design' ) == 'clean' ) {
				remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
				remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

				add_action( 'woocommerce_after_shop_loop_item', array( $this, 'before_shop_item_buttons' ), 9 );

			} else {
				remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

				add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'avada_show_product_loop_outofstock_flash' ), 10 );
				add_action( 'woocommerce_after_shop_loop_item', array( $this, 'before_shop_item_buttons' ), 5 );
				add_action( 'woocommerce_after_shop_loop_item', array( $this, 'avada_woocommerce_template_loop_add_to_cart' ), 10 );
				add_action( 'woocommerce_after_shop_loop_item', array( $this, 'show_details_button' ), 15 );
				add_action( 'woocommerce_after_shop_loop_item', array( $this, 'after_shop_item_buttons' ), 20 );
			}

			/**
			 * Single Product Page
			 */
			add_action( 'woocommerce_single_product_summary', array( $this, 'add_product_border' ), 19 );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
			add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
			add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 11 );

			// Backwards compatibility to 2.4
			add_filter( 'woocommerce_template_path', array( $this, 'backwards_compatibility' ) );

			/**
			 * WooCommerce 2.3 Remove extra checkout button
			 */
			remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );

			/* Remove extra cart totals from the hook 2.3.8 woo */
			remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );

			// Add welcome user bar to checkout page
			add_action( 'woocommerce_before_checkout_form', 'avada_top_user_container', 1 );

			// Filter the pagination
			add_filter( 'woocommerce_pagination_args', array( $this, 'change_pagination' ) );

		} // end __construct();

		/**
		 * Filter method to modify path to WooCommerce files if WooCommerce is a version less than 2.3
		 *
		 * @since 3.7.2
		 * @return relative path of WooCommerce template files within the theme
		 */
		function backwards_compatibility( $path ) {
			if ( null !== self::get_wc_version() ) {
				if ( ! version_compare( self::get_wc_version(), '2.5', '>=' ) ) {
					$path = 'woocommerce/compatibility/2.4/';
				}
			}

			return $path;
		}

		/**
		 * Helper method to get the version of the currently installed WooCommerce
		 *
		 * @since 3.7.2
		 * @return string woocommerce version number or null
		 */
		private static function get_wc_version() {
			return defined( 'WC_VERSION' ) && WC_VERSION ? WC_VERSION : null;
		}

		function before_container() {
			ob_start();
			Avada()->layout->add_class( 'content_class' );
			$content_class = ob_get_clean();

			ob_start();
			Avada()->layout->add_style( 'content_style' );
			$content_css = ob_get_clean();

			echo '<div class="woocommerce-container"><div id="content" ' . $content_class . ' ' . $content_css . '>';
		}

		function shop_title() {
			return false;
		}

		function after_container() {
			echo '</div></div>';
		}

		function add_sidebar() {
			do_action( 'avada_after_content' );
		}

		function avada_show_product_loop_outofstock_flash() {
			global $product;

			if ( ! $product->is_in_stock() ) {
				printf( '<div class="fusion-out-of-stock"><div class="fusion-position-text">%s</div></div>', __( 'Out of Stock', 'Avada' ) );
			}
		}

		function before_shop_item_buttons() {
			global $post;
			$html = '';
			$buttons_container = '<div class="product-buttons"><div class="product-buttons-container clearfix">';
			if ( isset( $_SERVER['QUERY_STRING'] ) ) {
				parse_str( $_SERVER['QUERY_STRING'], $params );
				if ( isset ( $params['product_view'] ) ){
					$product_view = $params['product_view'];
					if ( $product_view == 'list' ){
						$html = '<div class="product-excerpt product-' . $product_view . '">';
						$html .= '<div class="product-excerpt-container">';
						$html .= '<div class="post-content">';
						$html .= do_shortcode( $post->post_excerpt );
						$html .= '</div>';
						$html .= '</div>';
						$html .= $buttons_container;
						$html .= ' </div>';

						echo $html;
					} else {
						echo $buttons_container;
					}
				} else {
					echo $buttons_container;
				}
			} else {
				echo $buttons_container;
			}
		}

		function avada_woocommerce_template_loop_add_to_cart( $args = array() ) {
			global $product;

			if ( $product &&
				 ( ( $product->is_purchasable() && $product->is_in_stock() ) || $product->is_type( 'external' ) )
			) {

				if ( version_compare( self::get_wc_version(), '2.5', '>=' ) ) {

					$defaults = array(
						'quantity' => 1,
						'class'    => implode( ' ', array_filter( array(
								'button',
								'product_type_' . $product->product_type,
								$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
								$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : ''
						) ) )
					);

					$args = apply_filters( 'woocommerce_loop_add_to_cart_args', wp_parse_args( $args, $defaults ), $product );
				}

				wc_get_template( 'loop/add-to-cart.php' , $args );
			}
		}

		function avada_woocommerce_rollover_buttons_linebreak() {
			global $product;

			if ( $product &&
				 ( ( $product->is_purchasable() && $product->is_in_stock() ) || $product->is_type( 'external' ) )
			) {
			?>
				<span class="fusion-rollover-linebreak">
					<?php if ( Avada()->settings->get( 'woocommerce_product_box_design' ) == 'clean' ): ?>/<?php endif; ?>
				</span>
			<?php
			}
		}

		function show_details_button() {
			global $product;

			$styles = '';
			if ( ( ! $product->is_purchasable() || ! $product->is_in_stock() ) &&
				 ! $product->is_type( 'external' )
			) {
				$styles = ' style="float:none;max-width:none;text-align:center;"';
			}
			echo sprintf( '<a href="%s" class="show_details_button"%s>%s</a>', get_permalink(), $styles, esc_html__( 'Details', 'Avada' ) );
		}

		function after_shop_item_buttons() {
			?></div></div><?php
		}

		function add_product_border() {
			echo '<div class="product-border"></div>';
		}

		function change_pagination( $options ) {
			$options['prev_text'] 	= '<span class="page-prev"></span><span class="page-text">' . __('Previous', 'Avada') . '</span>';
			$options['next_text'] 	= '<span class="page-text">' . __('Next', 'Avada') . '</span><span class="page-next"></span>';
			$options['type']		= 'plain';

			return $options;
		}

		function product_title() {
			echo '<h3 class="product-title"><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h3>';
		}

	} // end FusionTemplateWoo() class

}
$fusion_template_woo = new FusionTemplateWoo();

add_filter( 'get_product_search_form', 'avada_product_search_form' );

function avada_product_search_form( $form ) {
	$form = '<form role="search" method="get" class="searchform" action="' . esc_url( home_url( '/' ) ) . '">
	<div>
	<input type="text" value="' . get_search_query() . '" name="s" class="s" placeholder="' . __( 'Search...', 'Avada' ) . '" />
	<input type="hidden" name="post_type" value="product" />
	</div>
	</form>';

	return $form;
}

remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

if ( Avada()->settings->get( 'woocommerce_avada_ordering' ) ) {
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

	add_action( 'woocommerce_before_shop_loop', 'avada_woocommerce_catalog_ordering', 30 );
}
function avada_woocommerce_catalog_ordering() {

	if ( isset( $_SERVER['QUERY_STRING'] ) ) {
		parse_str( $_SERVER['QUERY_STRING'], $params );

		$query_string = '?' . $_SERVER['QUERY_STRING'];
	} else {
		$query_string = '';
	}

	// replace it with theme option
	if ( Avada()->settings->get( 'woo_items' ) ) {
		$per_page = Avada()->settings->get( 'woo_items' );
	} else {
		$per_page = 12;
	}

	$pob = ! empty( $params['product_orderby'] ) ? $params['product_orderby'] : get_option( 'woocommerce_default_catalog_orderby' );

	if ( ! empty( $params['product_order'] ) ) {
		$po = $params['product_order'];
	} else {
		switch ( $pob ) {
			case 'default':
			case 'menu_order':
				$po = 'asc';
				break;
			case 'date':
				$po = 'desc';
				break;
			case 'price':
				$po = 'asc';
				break;
			case 'price-desc':
				$po = 'desc';
				break;
			case 'popularity':
				$po = 'asc';
				break;
			case 'rating':
				$po = 'desc';
				break;
			case 'name':
				$po = 'asc';
				break;
		}
	}

	$order_string = __( 'Default Order', 'Avada' );

	switch ( $pob ) {
		case 'date':
			$order_string = __( 'Date', 'Avada' );
			break;
		case 'price':
		case 'price-desc':
			$order_string = __( 'Price', 'Avada' );
			break;
		case 'popularity':
			$order_string = __( 'Popularity', 'Avada' );
			break;
		case 'rating':
			$order_string = __( 'Rating', 'Avada' );
			break;
		case 'name':
			$order_string = __( 'Name', 'Avada' );
			break;
	}

	$pc = ! empty( $params['product_count'] ) ? $params['product_count'] : $per_page;
	?>

	<div class="catalog-ordering clearfix">
		<div class="orderby-order-container">
			<ul class="orderby order-dropdown">
				<li>
					<span class="current-li">
						<span class="current-li-content">
							<a aria-haspopup="true"><?php printf( esc_html__( 'Sort by %s', 'Avada' ), '<strong>' . $order_string . '</strong>' ); ?></a>
						</span>
					</span>
					<ul>
						<li class="<?php echo ( $pob == 'menu_order' ) ? 'current' : ''; ?>">
							<a href="<?php echo fusion_add_url_parameter( $query_string, 'product_orderby', 'default' ); ?>"><?php printf( esc_html__( 'Sort by %s', 'Avada' ), '<strong>' . esc_attr__( 'Default Order', 'Avada' ) . '</strong>' ); ?></a>
						</li>
						<li class="<?php echo ( $pob == 'name' ) ? 'current' : ''; ?>">
							<a href="<?php echo fusion_add_url_parameter( $query_string, 'product_orderby', 'name' ); ?>"><?php printf( esc_html__( 'Sort by %s', 'Avada' ), '<strong>' . esc_attr__( 'Name', 'Avada' ) . '</strong>' ); ?></a>
						</li>
						<li class="<?php echo ( $pob == 'price' || $pob == 'price-desc' ) ? 'current' : ''; ?>">
							<a href="<?php echo fusion_add_url_parameter( $query_string, 'product_orderby', 'price' ); ?>"><?php printf( esc_html__( 'Sort by %s', 'Avada' ), '<strong>' . esc_attr__( 'Price', 'Avada' ) . '</strong>' ); ?></a>
						</li>
						<li class="<?php echo ( $pob == 'date' ) ? 'current' : ''; ?>">
							<a href="<?php echo fusion_add_url_parameter( $query_string, 'product_orderby', 'date' ); ?>"><?php printf( esc_html__( 'Sort by %s', 'Avada' ), '<strong>' . esc_attr__( 'Date', 'Avada' ) . '</strong>' ); ?></a>
						</li>
						<li class="<?php echo ( $pob == 'popularity' ) ? 'current' : ''; ?>">
							<a href="<?php echo fusion_add_url_parameter( $query_string, 'product_orderby', 'popularity' ); ?>"><?php printf( esc_html__( 'Sort by %s', 'Avada' ), '<strong>' . esc_attr__( 'Popularity', 'Avada' ) . '</strong>' ); ?></a>
						</li>
						<?php if ( 'no' !== get_option( 'woocommerce_enable_review_rating' ) ) : ?>
							<li class="<?php echo ( $pob == 'rating' ) ? 'current' : ''; ?>">
								<a href="<?php echo fusion_add_url_parameter( $query_string, 'product_orderby', 'rating' ); ?>"><?php printf( esc_html__( 'Sort by %s', 'Avada' ), '<strong>' . esc_attr__( 'Rating', 'Avada' ) . '</strong>' ); ?></a>
							</li>
						<?php endif; ?>
					</ul>
				</li>
			</ul>

			<ul class="order">
				<?php if ( isset( $po ) ) : ?>
					<?php if ( $po == 'desc' ) : ?>
						<li class="desc"><a aria-haspopup="true" href="<?php echo fusion_add_url_parameter( $query_string, 'product_order', 'asc' ); ?>"><i class="fusion-icon-arrow-down2 icomoon-up"></i></a></li>
					<?php else : ?>
						<li class="asc"><a aria-haspopup="true" href="<?php echo fusion_add_url_parameter( $query_string, 'product_order', 'desc' ); ?>"><i class="fusion-icon-arrow-down2"></i></a></li>
					<?php endif; ?>
				<?php endif; ?>
			</ul>
		</div>

		<ul class="sort-count order-dropdown">
			<li>
				<span class="current-li"><a aria-haspopup="true"><?php printf( __( 'Show <strong>%s Products</strong>', 'Avada' ), $per_page ); ?></a></span>
				<ul>
					<li class="<?php echo ( $pc == $per_page ) ? 'current' : ''; ?>">
						<a href="<?php echo fusion_add_url_parameter( $query_string, 'product_count', $per_page ); ?>"><?php printf( __( 'Show <strong>%s Products</strong>', 'Avada' ), $per_page ); ?></a>
					</li>
					<li class="<?php echo ( $pc == $per_page * 2 ) ? 'current' : ''; ?>">
						<a href="<?php echo fusion_add_url_parameter( $query_string, 'product_count', $per_page * 2 ); ?>"><?php printf( __( 'Show <strong>%s Products</strong>', 'Avada' ), $per_page * 2 ); ?></a>
					</li>
					<li class="<?php echo ( $pc == $per_page * 3 ) ? 'current' : ''; ?>">
						<a href="<?php echo fusion_add_url_parameter( $query_string, 'product_count', $per_page * 3 ); ?>"><?php printf( __( 'Show <strong>%s Products</strong>', 'Avada' ), $per_page * 3 ); ?></a>
					</li>
				</ul>
			</li>
		</ul>

		<?php $woocommerce_toggle_grid_list = Avada()->settings->get( 'woocommerce_toggle_grid_list' ); ?>
		<?php $product_view = 'grid'; ?>
		<?php if ( isset( $_SERVER['QUERY_STRING'] ) ) : ?>
			<?php parse_str( $_SERVER['QUERY_STRING'], $params ); ?>
			<?php if ( isset($params[ 'product_view' ] ) ) : ?>
				<?php $product_view = $params['product_view']; ?>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ( $woocommerce_toggle_grid_list ) : ?>
			<ul class="fusion-grid-list-view">
				<li class="fusion-grid-view-li<?php echo ( 'grid' == $product_view ) ? ' active-view' : ''; ?>">
					<a class="fusion-grid-view" aria-haspopup="true" href="<?php echo fusion_add_url_parameter( $query_string, 'product_view', 'grid' ); ?>"><i class="fusion-icon-grid icomoon-grid"></i></a>
				</li>
				<li class="fusion-list-view-li<?php echo ( 'list' == $product_view ) ? ' active-view' : ''; ?>">
					<a class="fusion-list-view" aria-haspopup="true" href="<?php echo fusion_add_url_parameter( $query_string, 'product_view', 'list' ); ?>"><i class="fusion-icon-list icomoon-list"></i></a>
				</li>
			</ul>
		<?php endif; ?>
	</div>
	<?php
}

if ( Avada()->settings->get( 'woocommerce_avada_ordering' ) ) {
	add_action( 'woocommerce_get_catalog_ordering_args', 'avada_woocommerce_get_catalog_ordering_args', 20 );
}
function avada_woocommerce_get_catalog_ordering_args( $args ) {
	global $woocommerce;

	if ( isset( $_SERVER['QUERY_STRING'] ) ) {
		parse_str( $_SERVER['QUERY_STRING'], $params );
	}

	$pob = ! empty( $params['product_orderby'] ) ? $params['product_orderby'] : get_option( 'woocommerce_default_catalog_orderby' );
	$po  = ! empty( $params['product_order'] ) ? $params['product_order'] : 'asc';

	// Remove posts_clause filter, if default ordering is set to rating or popularity to make custom ordering work correctly
	if ( $pob != 'default' ) {
		$woo_default_catalog_orderby = get_option( 'woocommerce_default_catalog_orderby' );
		if ( $woo_default_catalog_orderby == 'rating' ||
			 $woo_default_catalog_orderby == 'popularity'
		) {
			WC()->query->remove_ordering_args();
		}
	}

	$orderby  = 'date';

	$meta_key = '';

	switch ( $pob ) {
		case 'menu_order':
		case 'default':
			return $args;
			break;
		case 'date':
			$order    = 'desc';
			break;
		case 'price':
			$orderby  = 'meta_value_num';
			$order    = 'asc';
			$meta_key = '_price';
			break;
		case 'popularity':
			$orderby  = 'meta_value_num';
			$order    = 'asc';
			$meta_key = 'total_sales';
			break;
		case 'rating':
			$orderby  = 'meta_value_num';
			$order    = 'desc';
			$meta_key = 'average_rating';
			break;
		case 'name':
			$orderby  = 'title';
			$order    = 'asc';
			break;
	}

	switch ( $po ) {
		case 'desc':
			$order = 'desc';
			break;
		case 'asc':
			$order = 'asc';
			break;
		default:
			$order = 'asc';
			break;
	}

	$args['orderby']  = $orderby;
	$args['order']    = $order;
	$args['meta_key'] = $meta_key;

	if ( $pob == 'rating' ) {
		$args['orderby']  = 'menu_order title';
		$args['order']    = $po == 'desc' ? 'desc' : 'asc';
		$args['order']    = strtoupper( $args['order'] );
		$args['meta_key'] = '';

		add_filter( 'posts_clauses', 'fusion_order_by_rating_post_clauses' );
	}

	return $args;
}

/**
 * fusion_order_by_rating_post_clauses function.
 *
 * @access public
 *
 * @param array $args
 *
 * @return array
 */
function fusion_order_by_rating_post_clauses( $args ) {
	global $wpdb;

	$args['fields'] .= ", AVG( $wpdb->commentmeta.meta_value ) as average_rating ";

	$args['where'] .= " AND ( $wpdb->commentmeta.meta_key = 'rating' OR $wpdb->commentmeta.meta_key IS null ) ";

	$args['join'] .= "
	LEFT OUTER JOIN $wpdb->comments ON($wpdb->posts.ID = $wpdb->comments.comment_post_ID)
	LEFT JOIN $wpdb->commentmeta ON($wpdb->comments.comment_ID = $wpdb->commentmeta.comment_id)
	";

	if ( isset( $_SERVER['QUERY_STRING'] ) ) {
		parse_str( $_SERVER['QUERY_STRING'], $params );
	}
	$order = ! empty( $params['product_order'] ) ? $params['product_order'] : 'desc';
	$order = strtoupper( $order );

	$args['orderby'] = "average_rating {$order}, $wpdb->posts.post_date {$order}";

	$args['groupby'] = "$wpdb->posts.ID";

	return $args;
}

add_filter( 'loop_shop_per_page', 'avada_loop_shop_per_page' );
function avada_loop_shop_per_page() {

	if ( isset( $_SERVER['QUERY_STRING'] ) ) {
		parse_str( $_SERVER['QUERY_STRING'], $params );
	}

	if ( Avada()->settings->get( 'woo_items' ) ) {
		$per_page = Avada()->settings->get( 'woo_items' );
	} else {
		$per_page = 12;
	}

	$pc = ! empty( $params['product_count'] ) ? $params['product_count'] : $per_page;

	return $pc;
}

if ( Avada()->settings->get( 'woocommerce_product_box_design' ) != 'clean' ) {
	add_action( 'woocommerce_before_shop_loop_item_title', 'avada_woocommerce_thumbnail', 10 );
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
	function avada_woocommerce_thumbnail() {
		global $product, $woocommerce;

		$items_in_cart = array();

		if ( $woocommerce->cart && $woocommerce->cart->get_cart() && is_array( $woocommerce->cart->get_cart() ) ) {
			foreach ( $woocommerce->cart->get_cart() as $cart ) {
				$items_in_cart[] = $cart['product_id'];
			}
		}

		$id      = get_the_ID();
		$in_cart = in_array( $id, $items_in_cart );
		$size    = 'shop_catalog';


		$attachment_image = '';
		if ( Avada()->settings->get( 'woocommerce_disable_crossfade_effect' ) ) {
			$gallery = get_post_meta( $id, '_product_image_gallery', true );

			if ( ! empty( $gallery ) ) {
				$gallery          = explode( ',', $gallery );
				$first_image_id   = $gallery[0];
				$attachment_image = wp_get_attachment_image( $first_image_id, $size, false, array( 'class' => 'hover-image' ) );
			}
		}
		$thumb_image = get_the_post_thumbnail( $id, $size );

		if ( $attachment_image ) {
			$classes = 'crossfade-images';
		} else {
			$classes = 'featured-image';
		}

		echo '<span class="' . $classes . '">';
		echo $attachment_image;
		echo $thumb_image;
		if ( $in_cart ) {
			echo '<span class="cart-loading"><i class="fusion-icon-check-square-o"></i></span>';
		} else {
			echo '<span class="cart-loading"><i class="fusion-icon-spinner"></i></span>';
		}
		echo '</span>';
	}
}

if ( Avada()->settings->get( 'woocommerce_product_box_design' ) == 'clean' ) {
	add_action( 'woocommerce_before_shop_loop_item_title', 'avada_woocommerce_thumbnail', 10 );
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
	function avada_woocommerce_thumbnail() {
		global $product, $woocommerce;

		$items_in_cart = array();

		if ( $woocommerce->cart && $woocommerce->cart->get_cart() && is_array( $woocommerce->cart->get_cart() ) ) {
			foreach ( $woocommerce->cart->get_cart() as $cart ) {
				$items_in_cart[] = $cart['product_id'];
			}
		}

		$id      = get_the_ID();
		$in_cart = in_array( $id, $items_in_cart );
		$size    = 'shop_catalog';
		$post_permalink = get_permalink();
		$classes = '';

		if ( $in_cart ) {
			$classes = 'fusion-item-in-cart';
		}

		$featured_image_markup = avada_render_first_featured_image_markup( $id, $size, $post_permalink, TRUE, FALSE, TRUE, 'disable', 'disable', '', '', 'yes', TRUE );
		echo '<div class="fusion-clean-product-image-wrapper ' . $classes . '">';
			echo $featured_image_markup;
		echo '</div>';
	}
}

add_filter( 'wp_nav_menu_items', 'fusion_add_woo_cart_to_widget', 20, 4 );
function fusion_add_woo_cart_to_widget( $items, $args ) {
	if ( class_exists( 'WooCommerce') ) {
		$ubermenu = false;

		if ( function_exists( 'ubermenu_get_menu_instance_by_theme_location' ) && ubermenu_get_menu_instance_by_theme_location( $args->theme_location ) ) {
			// disable woo cart on ubermenu navigations
			$ubermenu = true;
		}

		if ( $ubermenu == false && $args->container_class == 'fusion-widget-menu' ) {
			$items .= fusion_add_woo_cart_to_widget_html();
		}
	}

	return $items;
}

add_filter( 'woocommerce_add_to_cart_fragments', 'avada_woocommerce_header_add_to_cart_fragment' );
function avada_woocommerce_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;

	$header_top_cart = avada_nav_woo_cart( 'secondary' );
	$fragments['.fusion-secondary-menu-cart'] = $header_top_cart;

	$header_cart = avada_nav_woo_cart( 'main' );
	$fragments['.fusion-main-menu-cart'] = $header_cart;

	$widget_cart = fusion_add_woo_cart_to_widget_html();
	$fragments['.fusion-widget-cart'] = $widget_cart;

	return $fragments;
}

add_action( 'woocommerce_single_product_summary', 'avada_woocommerce_single_product_summary_open', 1 );
function avada_woocommerce_single_product_summary_open() {
	echo '<div class="summary-container">';
}

add_action( 'woocommerce_single_product_summary', 'avada_woocommerce_single_product_summary_close', 100 );
function avada_woocommerce_single_product_summary_close() {
	echo '</div>';
}

add_action( 'woocommerce_after_single_product_summary', 'avada_woocommerce_after_single_product_summary', 15 );
function avada_woocommerce_after_single_product_summary() {

	$nofollow = '';
	if ( Avada()->settings->get( 'nofollow_social_links' ) ) {
		$nofollow = ' rel="nofollow"';
	}
	$social = '<div class="fusion-clearfix"></div>';
	if ( Avada()->settings->get( 'woocommerce_social_links' ) ) {
		$social .= '<ul class="social-share clearfix">
		<li class="facebook">
			<a href="http://www.facebook.com/sharer.php?m2w&s=100&p&#91;url&#93;=' . get_permalink() . '&p&#91;title&#93;=' . wp_strip_all_tags( get_the_title(), true ) . '" target="_blank"' . $nofollow . '>
				<i class="fontawesome-icon medium circle-yes fusion-icon-facebook"></i>
				<div class="fusion-woo-social-share-text"><span>' . __( 'Share On Facebook', 'Avada' ) . '</span></div>
			</a>
		</li>
		<li class="twitter">
			<a href="https://twitter.com/share?text=' . wp_strip_all_tags( get_the_title(), true ) . ' ' . get_permalink() . '" target="_blank"' . $nofollow . '>
				<i class="fontawesome-icon medium circle-yes fusion-icon-twitter"></i>
				<div class="fusion-woo-social-share-text"><span>' . __( 'Tweet This Product', 'Avada' ) . '</span></div>
			</a>
		</li>
		<li class="pinterest">';
		$full_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
		$social .= '<a href="http://pinterest.com/pin/create/button/?url=' . urlencode( get_permalink() ) . '&amp;description=' . urlencode( wp_strip_all_tags( get_the_title(), true ) ) . '&amp;media=' . urlencode( $full_image[0] ) . '" target="_blank"' . $nofollow . '>
				<i class="fontawesome-icon medium circle-yes fusion-icon-pinterest"></i>
				<div class="fusion-woo-social-share-text"><span>' . __( 'Pin This Product', 'Avada' ) . '</span></div>
			</a>
		</li>
		<li class="email">
			<a href="mailto:?subject=' .rawurlencode( html_entity_decode( wp_strip_all_tags( get_the_title(), true ), ENT_COMPAT, 'UTF-8' ) ) . '&amp;body=' . get_permalink() . '" target="_blank"' . $nofollow . '>
				<i class="fontawesome-icon medium circle-yes fusion-icon-mail"></i>
				<div class="fusion-woo-social-share-text"><span>' . __( 'Mail This Product', 'Avada' ) . '</span></div>
			</a>
		</li>
	</ul>';

		echo $social;
	}
}

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
add_action( 'woocommerce_after_single_product_summary', 'avada_woocommerce_output_related_products', 15 );
function avada_woocommerce_output_related_products() {
	global $post;

	if ( get_post_meta( $post->ID, 'pyre_number_of_related_products', true ) == 'default' ||
	     get_post_meta( $post->ID, 'pyre_number_of_related_products', true ) == '' ||
	     ! get_post_meta( $post->ID, 'pyre_number_of_related_products', true )
	) {
		$number_of_columns = Avada()->settings->get( 'woocommerce_related_columns' );
	} else {
		$number_of_columns = get_post_meta( $post->ID, 'pyre_number_of_related_products', true );
	}

	$args = array(
		'posts_per_page' => $number_of_columns,
		'columns'        => $number_of_columns,
		'orderby'        => 'rand'
	);

	echo '<div class="fusion-clearfix"></div>';
	woocommerce_related_products( apply_filters( 'woocommerce_output_related_products_args', $args ) );
}


remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
add_action( 'woocommerce_after_single_product_summary', 'avada_woocommerce_upsell_display', 10 );
function avada_woocommerce_upsell_display() {
	global $product, $woocommerce_loop, $post;

	$upsells = $product->get_upsells();

	if ( sizeof( $upsells ) == 0 ) {
		return;
	}

	?>
	<div class="fusion-clearfix"></div>
	<?php

	if ( get_post_meta( $post->ID, 'pyre_number_of_related_products', true ) == 'default' ||
	     get_post_meta( $post->ID, 'pyre_number_of_related_products', true ) == '' ||
	     ! get_post_meta( $post->ID, 'pyre_number_of_related_products', true )
	) {
		$number_of_columns = Avada()->settings->get( 'woocommerce_related_columns' );
	} else {
		$number_of_columns = get_post_meta( $post->ID, 'pyre_number_of_related_products', true );
	}

	woocommerce_upsell_display( - 1, $number_of_columns );
}

/* variations hooks */


/* end variations hooks */

/* cart hooks */
add_action( 'woocommerce_before_cart_table', 'avada_woocommerce_before_cart_table', 20 );
function avada_woocommerce_before_cart_table( $args ) {
	global $woocommerce;

	$html = '<div class="woocommerce-content-box full-width clearfix">';

	if ( $woocommerce->cart->get_cart_contents_count() == 1 ) {
		$html .= '<h2>' . sprintf( __( 'You Have %d Item In Your Cart', 'Avada' ), $woocommerce->cart->get_cart_contents_count() ) . '</h2>';
	} else {
		$html .= '<h2>' . sprintf( __( 'You Have %d Items In Your Cart', 'Avada' ), $woocommerce->cart->get_cart_contents_count() ) . '</h2>';
	}

	echo $html;
}

add_action( 'woocommerce_after_cart_table', 'avada_woocommerce_after_cart_table', 20 );
function avada_woocommerce_after_cart_table( $args ) {
	$html = '</div>';

	echo $html;
}

function woocommerce_cross_sell_display( $posts_per_page = 3, $columns = 3, $orderby = 'rand' ) {
	wc_get_template( 'cart/cross-sells.php', array(
		'posts_per_page' => $posts_per_page,
		'orderby'        => $orderby,
		'columns'        => $columns
	) );
}

function cart_shipping_calc() {
	// Move this code to ~/woocommerce/cart/shipping-calculator.php and move the hook call accordingly.

	global $woocommerce;

	if ( get_option( 'woocommerce_enable_shipping_calc' ) === 'no' || ! WC()->cart->needs_shipping() ) {
		return;
	}
	?>

	<?php do_action( 'woocommerce_before_shipping_calculator' ); ?>

	<div class="woocommerce-shipping-calculator" action="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" method="post">

		<h2><span href="#" class="shipping-calculator-button"><?php _e( 'Calculate Shipping', 'woocommerce' ); ?></span>
		</h2>

		<div class="avada-shipping-calculator-form">

			<p class="form-row form-row-wide">
				<select name="calc_shipping_country" id="calc_shipping_country" class="country_to_state" rel="calc_shipping_state">
					<option value=""><?php _e( 'Select a country&hellip;', 'woocommerce' ); ?></option>
					<?php
						foreach( WC()->countries->get_shipping_countries() as $key => $value )
							echo '<option value="' . esc_attr( $key ) . '"' . selected( WC()->customer->get_shipping_country(), esc_attr( $key ), false ) . '>' . esc_html( $value ) . '</option>';
					?>
				</select>
			</p>

			<div class="<?php if ( Avada()->settings->get( 'avada_styles_dropdowns' ) ): ?>avada-select-parent fusion-layout-column fusion-one-half fusion-spacing-yes<?php endif; ?>">
				<?php
					$current_cc = WC()->customer->get_shipping_country();
					$current_r  = WC()->customer->get_shipping_state();
					$states     = WC()->countries->get_states( $current_cc );

					// Hidden Input
					if ( is_array( $states ) && empty( $states ) ) {

						?><input type="hidden" name="calc_shipping_state" id="calc_shipping_state" placeholder="<?php esc_attr_e( 'State / county', 'woocommerce' ); ?>" /><?php

						// Dropdown Input
					} elseif ( is_array( $states ) ) {

						?><span>
							<select name="calc_shipping_state" id="calc_shipping_state" placeholder="<?php esc_attr_e( 'State / county', 'woocommerce' ); ?>">
								<option value=""><?php _e( 'Select a state&hellip;', 'woocommerce' ); ?></option>
								<?php
									foreach ( $states as $ckey => $cvalue )
										echo '<option value="' . esc_attr( $ckey ) . '" ' . selected( $current_r, $ckey, false ) . '>' . __( esc_html( $cvalue ), 'woocommerce' ) .'</option>';
								?>
							</select>
						</span><?php

						// Standard Input
					} else {

						?><input type="text" class="input-text" value="<?php echo esc_attr( $current_r ); ?>" placeholder="<?php esc_attr_e( 'State / county', 'woocommerce' ); ?>" name="calc_shipping_state" id="calc_shipping_state" /><?php

					}
				?>
			</div>

			<?php if ( apply_filters( 'woocommerce_shipping_calculator_enable_city', false ) ) : ?>

				<p class="form-row form-row-wide">
					<input type="text" class="input-text" value="<?php echo esc_attr( WC()->customer->get_shipping_city() ); ?>" placeholder="<?php esc_attr_e( 'City', 'woocommerce' ); ?>" name="calc_shipping_city" id="calc_shipping_city" />
				</p>

			<?php endif; ?>

			<?php if ( apply_filters( 'woocommerce_shipping_calculator_enable_postcode', true ) ) : ?>

				<div class="form-row form-row-wide fusion-layout-column fusion-one-half fusion-spacing-yes fusion-column-last">
					<input type="text" class="input-text" value="<?php echo esc_attr( WC()->customer->get_shipping_postcode() ); ?>" placeholder="<?php esc_attr_e( 'Postcode / ZIP', 'woocommerce' ); ?>" name="calc_shipping_postcode" id="calc_shipping_postcode" />
				</div>

			<?php endif; ?>

			<p>
				<button type="submit" name="calc_shipping" value="1" class="fusion-button button-default button-small button small default"><?php _e( 'Update Totals', 'woocommerce' ); ?></button>
			</p>

			<?php wp_nonce_field( 'woocommerce-cart' ); ?>
		</div>
	</div>

	<?php do_action( 'woocommerce_after_shipping_calculator' ); ?>

<?php
}

function woocommerce_shipping_calculator() {
	if ( ! is_cart() ) {
		wc_get_template( 'cart/shipping-calculator.php' );
	}
}

add_action( 'woocommerce_cart_collaterals', 'avada_woocommerce_cart_collaterals' );
function avada_woocommerce_cart_collaterals( $args ) {
	global $woocommerce;
	?>

	<div class="shipping-coupon">

		<?php echo cart_shipping_calc();

			if ( WC()->cart->coupons_enabled() ) {
				?>
				<div class="coupon">

					<h2><?php _e( 'Have A Promotional Code?', 'Avada' ); ?></h2>

					<input name="coupon_code" class="input-text" id="coupon_code" value=""
					       placeholder="<?php _e( 'Coupon code', 'woocommerce' ); ?>"/>
					<input type="submit" class="fusion-button fusion-button-default fusion-button-small button default small"
					       name="apply_coupon" value="<?php _e( 'Apply', 'Avada' ); ?>"/>

					<?php do_action( 'woocommerce_cart_coupon' ); ?>

				</div>
			<?php
			}
		?>
	</div>
<?php
}

add_action( 'woocommerce_before_cart_totals', 'avada_woocommerce_before_cart_totals', 20 );
function avada_woocommerce_before_cart_totals( $args ) {
	global $woocommerce; ?>

	<form action="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" method="post">

<?php
}

add_action( 'woocommerce_after_cart', 'avada_woocommerce_after_cart' );
function avada_woocommerce_after_cart( $args ) {
	?>

	</form>

<?php
}

remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_cart_collaterals', 'avada_woocommerce_cross_sell_display', 5 );
function avada_woocommerce_cross_sell_display() {
	global $product, $woocommerce_loop, $post;

	$crosssells = WC()->cart->get_cross_sells();

	if ( sizeof( $crosssells ) == 0 ) {
		return;
	}

	$number_of_columns = Avada()->settings->get( 'woocommerce_related_columns' );

	woocommerce_cross_sell_display( apply_filters( 'woocommerce_cross_sells_total', - 1 ), $number_of_columns );
}

/* end cart hooks */

/* begin checkout hooks */
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
add_action( 'woocommerce_before_checkout_form', 'avada_woocommerce_checkout_coupon_form', 10 );
function avada_woocommerce_checkout_coupon_form( $args ) {
	global $woocommerce;

	if ( ! WC()->cart->coupons_enabled() ) {
		return;
	}
	?>

	<form class="woocommerce-content-box full-width checkout_coupon" method="post">

		<h2 class="promo-code-heading fusion-alignleft"><?php _e( 'Have A Promotional Code?', 'Avada' ); ?></h2>

		<div class="coupon-contents fusion-alignright">
			<div class="form-row form-row-first fusion-alignleft coupon-input">
				<input type="text" name="coupon_code" class="input-text"
				       placeholder="<?php _e( 'Coupon code', 'woocommerce' ); ?>" id="coupon_code" value=""/>
			</div>

			<div class="form-row form-row-last fusion-alignleft coupon-button">
				<input type="submit" class="fusion-button button-default button-small button default small"
				       name="apply_coupon" value="<?php _e( 'Apply Coupon', 'woocommerce' ); ?>"/>
			</div>

			<div class="clear"></div>
		</div>
	</form>
<?php
}

if ( ! Avada()->settings->get( 'woocommerce_one_page_checkout' ) ) {
	add_action( 'woocommerce_before_checkout_form', 'avada_woocommerce_before_checkout_form' );
}
function avada_woocommerce_before_checkout_form( $args ) {
	global $woocommerce;
	?>

	<ul class="woocommerce-side-nav woocommerce-checkout-nav">
		<li class="active">
			<a data-name="col-1" href="#">
				<?php _e( 'Billing Address', 'Avada' ); ?>
			</a>
		</li>
		<?php if ( WC()->cart->needs_shipping() && ! WC()->cart->ship_to_billing_address_only() ) : ?>
			<li>
				<a data-name="col-2" href="#">
					<?php _e( 'Shipping Address', 'Avada' ); ?>
				</a>
			</li>
		<?php
		elseif ( apply_filters( 'woocommerce_enable_order_notes_field', get_option( 'woocommerce_enable_order_comments', 'yes' ) === 'yes' ) ) :

			if ( ! WC()->cart->needs_shipping() || WC()->cart->ship_to_billing_address_only() ) : ?>

				<li>
					<a data-name="col-2" href="#">
						<?php _e( 'Additional Information', 'Avada' ); ?>
					</a>
				</li>
			<?php endif; ?>
		<?php endif; ?>

		<li>
			<a data-name="order_review" href="#">
				<?php _e( 'Review &amp; Payment', 'Avada' ); ?>
			</a>
		</li>
	</ul>

	<div class="woocommerce-content-box avada-checkout">

<?php

}

if ( ! Avada()->settings->get( 'woocommerce_one_page_checkout' ) ) {
	add_action( 'woocommerce_after_checkout_form', 'avada_woocommerce_after_checkout_form' );
}
function avada_woocommerce_after_checkout_form( $args ) {
	?>

	</div>

<?php

}

if ( Avada()->settings->get( 'woocommerce_one_page_checkout' ) ) {
	add_action( 'woocommerce_checkout_before_customer_details', 'avada_woocommerce_checkout_before_customer_details' );
}
function avada_woocommerce_checkout_before_customer_details( $args ) {
	global $woocommerce;

	if ( WC()->cart->needs_shipping() && ! WC()->cart->ship_to_billing_address_only() ||
	     apply_filters( 'woocommerce_enable_order_notes_field', get_option( 'woocommerce_enable_order_comments', 'yes' ) === 'yes' ) && ( ! WC()->cart->needs_shipping() || WC()->cart->ship_to_billing_address_only() )
	) {
		return;
	} else {
		?>

		<div class="avada-checkout-no-shipping">

	<?php
	}

}

if ( Avada()->settings->get( 'woocommerce_one_page_checkout' ) ) {
	add_action( 'woocommerce_checkout_after_customer_details', 'avada_woocommerce_checkout_after_customer_details' );
}
function avada_woocommerce_checkout_after_customer_details( $args ) {
	global $woocommerce;

	if ( WC()->cart->needs_shipping() && ! WC()->cart->ship_to_billing_address_only() ||
	     apply_filters( 'woocommerce_enable_order_notes_field', get_option( 'woocommerce_enable_order_comments', 'yes' ) === 'yes' ) && ( ! WC()->cart->needs_shipping() || WC()->cart->ship_to_billing_address_only() )
	) {
		?>

		<div class="clearboth"></div>

	<?php } else { ?>

		<div class="clearboth"></div>
		</div>

	<?php } ?>

	<div class="woocommerce-content-box full-width">

<?php
}


add_action( 'woocommerce_checkout_billing', 'avada_woocommerce_checkout_billing', 20 );
function avada_woocommerce_checkout_billing( $args ) {
	global $woocommerce;

	if ( WC()->cart->needs_shipping() && ! WC()->cart->ship_to_billing_address_only() ||
	     apply_filters( 'woocommerce_enable_order_notes_field', get_option( 'woocommerce_enable_order_comments', 'yes' ) === 'yes' ) && ( ! WC()->cart->needs_shipping() || WC()->cart->ship_to_billing_address_only() )
	) {
		$data_name = 'col-2';
	} else {
		$data_name = 'order_review';
	}

	if ( ! Avada()->settings->get( 'woocommerce_one_page_checkout' ) ) {
		?>

		<a data-name="<?php echo $data_name; ?>" href="#"
		   class="fusion-button button-default button-medium  button default medium continue-checkout"><?php _e( 'Continue', 'Avada' ); ?></a>
		<div class="clearboth"></div>

	<?php
	}

}

add_action( 'woocommerce_checkout_shipping', 'avada_woocommerce_checkout_shipping', 20 );
function avada_woocommerce_checkout_shipping( $args ) {

	if ( ! Avada()->settings->get( 'woocommerce_one_page_checkout' ) ) {
		?>

		<a data-name="order_review" href="#"
		   class="fusion-button button-default button-medium continue-checkout button default medium"><?php _e( 'Continue', 'Avada' ); ?></a>
		<div class="clearboth"></div>

	<?php
	}

}

add_filter( 'woocommerce_enable_order_notes_field', 'avada_enable_order_notes_field' );
function avada_enable_order_notes_field() {

	if ( ! Avada()->settings->get( 'woocommerce_enable_order_notes' ) ) {
		return 0;
	}

	return 1;

}

if ( Avada()->settings->get( 'woocommerce_one_page_checkout' ) ) {
	add_action( 'woocommerce_checkout_after_order_review', 'avada_woocommerce_checkout_after_order_review', 20 );
}
function avada_woocommerce_checkout_after_order_review() {
	?></div><?php
}

//function under myaccount hooks
remove_action( 'woocommerce_thankyou', 'woocommerce_order_details_table', 10 );
add_action( 'woocommerce_thankyou', 'avada_woocommerce_view_order', 10 );
/* end checkout hooks */

/* begin my-account hooks */
add_action( 'woocommerce_before_customer_login_form', 'avada_woocommerce_before_customer_login_form' );
function avada_woocommerce_before_customer_login_form() {

	global $woocommerce;

	if ( get_option( 'woocommerce_enable_myaccount_registration' ) !== 'yes' ) :
	?>

		<div id="customer_login" class="woocommerce-content-box full-width">

	<?php
	endif;
}

add_action( 'woocommerce_after_customer_login_form', 'avada_woocommerce_after_customer_login_form' );
function avada_woocommerce_after_customer_login_form() {

	global $woocommerce;

	if ( get_option( 'woocommerce_enable_myaccount_registration' ) !== 'yes' ) :
	?>

		</div>

	<?php
	endif;
}

function avada_top_user_container() {
	global $woocommerce, $current_user;

	echo '<div class="avada_myaccount_user">';
		echo '<span class="myaccount_user_container">';
			echo '<span class="username">';

				if ( $current_user->display_name ) {
					printf( '%s, %s:', __( 'Hello', 'Avada' ), $current_user->display_name );
				} else {
					_e( 'Hello', 'Avada' );
				}

			echo '</span>';

			if ( Avada()->settings->get( 'woo_acc_msg_1' ) ) {
				echo '<span class="msg">';
					echo Avada()->settings->get( 'woo_acc_msg_1' );
				echo '</span>';
			}

			if ( Avada()->settings->get( 'woo_acc_msg_2' ) ) {
				echo '<span class="msg">';
					echo Avada()->settings->get( 'woo_acc_msg_2' );
				echo '</span>';
			}
			echo '<span class="view-cart">';
				printf( '<a href="%s">%s</a>', get_permalink( get_option( 'woocommerce_cart_page_id' ) ), __( 'View Cart', 'Avada' ) );
			echo '</span>';
		echo '</span>';
	echo '</div>';
}

add_action( 'woocommerce_before_my_account', 'avada_woocommerce_before_my_account' );
function avada_woocommerce_before_my_account( $order_count, $edit_address = false )
{
	global $woocommerce;
	$edit_address = is_wc_endpoint_url( 'edit-address' );

	avada_top_user_container();
	?>

	<ul class="woocommerce-side-nav avada-myaccount-nav">
		<?php if ( $downloads = WC()->customer->get_downloadable_products() ) : ?>
			<li <?php if ( ! $edit_address ) {
				echo 'class="active"';
			} ?>>
				<a class="downloads" href="#">
					<?php _e( 'View Downloads', 'Avada' ); ?>
				</a>
			</li>
		<?php endif;

			if ( function_exists( 'wc_get_order_types' ) && function_exists( 'wc_get_order_statuses' ) ) {
				$customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
					'numberposts' => $order_count,
					'meta_key'    => '_customer_user',
					'meta_value'  => get_current_user_id(),
					'post_type'   => wc_get_order_types( 'view-orders' ),
					'post_status' => array_keys( wc_get_order_statuses() )
				) ) );
			} else {
				$customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
					'numberposts' => $order_count,
					'meta_key'    => '_customer_user',
					'meta_value'  => get_current_user_id(),
					'post_type'   => 'shop_order',
					'post_status' => 'publish'
				) ) );
			}

			if ( $customer_orders ) : ?>
				<li <?php if ( ! $edit_address && ! WC()->customer->get_downloadable_products() ) {
					echo 'class="active"';
				} ?>>
					<a class="orders" href="#">
						<?php _e( 'View Orders', 'Avada' ); ?>
					</a>
				</li>
			<?php endif; ?>
		<li <?php if ( $edit_address || ! WC()->customer->get_downloadable_products() && ! $customer_orders ) {
			echo 'class="active"';
		} ?>>
			<a class="address" href="#">
				<?php _e( 'Change Address', 'Avada' ); ?>
			</a>
		</li>
		<li>
			<a class="account" href="#">
				<?php _e( 'Edit Account', 'Avada' ); ?>
			</a>
		</li>
	</ul>

<div class="woocommerce-content-box avada-myaccount-data">

<?php
}

add_action( 'woocommerce_after_my_account', 'avada_woocommerce_after_my_account' );
function avada_woocommerce_after_my_account( $args )
{
	global $woocommerce, $wp;

	$user = wp_get_current_user();

	?>

	<h2 class="edit-account-heading"><?php _e( 'Edit Account', 'Avada' ); ?></h2>

	<form class="edit-account-form" action="" method="post">
		<p class="form-row form-row-first">
			<label for="account_first_name"><?php _e( 'First name', 'woocommerce' ); ?> <span class="required">*</span></label>
			<input type="text" class="input-text" name="account_first_name" id="account_first_name" value="<?php echo esc_attr( $user->first_name ); ?>" />
		</p>

		<p class="form-row form-row-last">
			<label for="account_last_name"><?php _e( 'Last name', 'woocommerce' ); ?> <span class="required">*</span></label>
			<input type="text" class="input-text" name="account_last_name" id="account_last_name" value="<?php echo esc_attr( $user->last_name ); ?>" />
		</p>

		<p class="form-row form-row-wide">
			<label for="account_email"><?php _e( 'Email address', 'woocommerce' ); ?> <span class="required">*</span></label>
			<input type="email" class="input-text" name="account_email" id="account_email" value="<?php echo esc_attr( $user->user_email ); ?>" />
		</p>

		<p class="form-row form-row-wide">
			<label for="password_current"><?php _e( 'Current Password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
			<input type="password" class="input-text" name="password_current" id="password_current" />
		</p>

		<p class="form-row form-row-wide">
			<label for="password_1"><?php _e( 'New Password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
			<input type="password" class="input-text" name="password_1" id="password_1" />
		</p>

		<p class="form-row form-row-wide">
			<label for="password_2"><?php _e( 'Confirm New Password', 'woocommerce' ); ?></label>
			<input type="password" class="input-text" name="password_2" id="password_2" />
		</p>
		
		<div class="clear"></div>
		
		<?php do_action( 'woocommerce_edit_account_form' ); ?>
		
		<p><input type="submit" class="fusion-button button-default button-medium button default medium alignright"
		          name="save_account_details" value="<?php _e( 'Save changes', 'woocommerce' ); ?>"/></p>

		<?php wp_nonce_field( 'save_account_details' ); ?>
		<input type="hidden" name="action" value="save_account_details"/>

		<div class="clearboth"></div>
	</form>

</div>

<?php

}
/* end my-account hooks */

/* begin order hooks */
remove_action( 'woocommerce_view_order', 'woocommerce_order_details_table', 10 );
add_action( 'woocommerce_view_order', 'avada_woocommerce_view_order', 10 );
function avada_woocommerce_view_order( $order_id ) {
	global $woocommerce;

	$order = new WC_Order( $order_id );

	?>
	<div class="avada-order-details woocommerce-content-box full-width">
		<h2><?php _e( 'Order Details', 'woocommerce' ); ?></h2>
		<table class="shop_table order_details">
			<thead>
			<tr>
				<th class="product-name"><?php _e( 'Product', 'woocommerce' ); ?></th>
				<th class="product-quantity"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
				<th class="product-total"><?php _e( 'Total', 'woocommerce' ); ?></th>
			</tr>
			</thead>
			<tfoot>
			<?php
				if ( $totals = $order->get_order_item_totals() ) {
					foreach ( $totals as $total ) :
						?>
						<tr>
							<td class="filler-td">&nbsp;</td>
							<th scope="row"><?php echo $total['label']; ?></th>
							<td class="product-total"><?php echo $total['value']; ?></td>
						</tr>
					<?php
					endforeach;
				}
			?>
			</tfoot>
			<tbody>
			<?php
				if ( sizeof( $order->get_items() ) > 0 ) {

					foreach ( $order->get_items() as $item ) {
						$_product  = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
						$item_meta = new WC_Order_Item_Meta( $item['item_meta'] );

						?>
						<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
							<td class="product-name">
						<span class="product-thumbnail">
							<?php
								$thumbnail = $_product->get_image();

								if ( ! $_product->is_visible() ) {
									echo $thumbnail;
								} else {
									printf( '<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail );
								}
							?>
						</span>

								<div class="product-info">
									<?php
										if ( $_product && ! $_product->is_visible() ) {
											echo apply_filters( 'woocommerce_order_item_name', $item['name'], $item );
										} else {
											echo apply_filters( 'woocommerce_order_item_name', sprintf( '<a href="%s">%s</a>', get_permalink( $item['product_id'] ), $item['name'] ), $item );
										}

										// Meta data
										do_action( 'woocommerce_order_item_meta_start', $item['product_id'], $item, $order );
										$order->display_item_meta( $item );
										$order->display_item_downloads( $item );
										do_action( 'woocommerce_order_item_meta_end', $item['product_id'], $item, $order );
									?>
								</div>
							</td>
							<td class="product-quantity">
								<?php echo apply_filters( 'woocommerce_order_item_quantity_html', $item['qty'], $item ); ?>
							</td>
							<td class="product-total">
								<?php echo $order->get_formatted_line_subtotal( $item ); ?>
							</td>
						</tr>
						<?php

						if ( in_array( $order->status, array(
									'processing',
									'completed'
								) ) && ( $purchase_note = get_post_meta( $_product->id, '_purchase_note', true ) )
						) {
							?>
							<tr class="product-purchase-note">
								<td colspan="3"><?php echo apply_filters( 'the_content', $purchase_note ); ?></td>
							</tr>
						<?php
						}
					}
				}

				do_action( 'woocommerce_order_items_table', $order );
			?>
			</tbody>
		</table>
		<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
	</div>

	<div class="avada-customer-details woocommerce-content-box full-width">
		<header>
			<h2><?php _e( 'Customer details', 'woocommerce' ); ?></h2>
		</header>
		<dl class="customer_details">
			<?php
				if ( $order->billing_email ) {
					echo '<dt>' . __( 'Email:', 'woocommerce' ) . '</dt> <dd>' . $order->billing_email . '</dd><br />';
				}
				if ( $order->billing_phone ) {
					echo '<dt>' . __( 'Telephone:', 'woocommerce' ) . '</dt> <dd>' . $order->billing_phone . '</dd>';
				}

				// Additional customer details hook
				do_action( 'woocommerce_order_details_after_customer_details', $order );
			?>
		</dl>

		<?php if (get_option( 'woocommerce_ship_to_billing_address_only' ) === 'no' && get_option( 'woocommerce_calc_shipping' ) !== 'no') : ?>

		<div class="col2-set addresses">

			<div class="col-1">

				<?php endif; ?>

				<header class="title">
					<h3><?php _e( 'Billing Address', 'woocommerce' ); ?></h3>
				</header>
				<address><p>
						<?php
							if ( ! $order->get_formatted_billing_address() ) {
								_e( 'N/A', 'woocommerce' );
							} else {
								echo $order->get_formatted_billing_address();
							}
						?>
					</p></address>

				<?php if (get_option( 'woocommerce_ship_to_billing_address_only' ) === 'no' && get_option( 'woocommerce_calc_shipping' ) !== 'no') : ?>

			</div>
			<!-- /.col-1 -->

			<div class="col-2">

				<header class="title">
					<h3><?php _e( 'Shipping Address', 'woocommerce' ); ?></h3>
				</header>
				<address><p>
						<?php
							if ( ! $order->get_formatted_shipping_address() ) {
								_e( 'N/A', 'woocommerce' );
							} else {
								echo $order->get_formatted_shipping_address();
							}
						?>
					</p></address>

			</div>
			<!-- /.col-2 -->

		</div>
		<!-- /.col2-set -->

	<?php endif; ?>

		<div class="clear"></div>

	</div>

<?php
}
/* end order hooks */
/**
* avada_change_product_class - Function to add 'product-list-view' class if the list view is being displayed
* @param  [array]  $classes      - An array containing class names for the particular post / product
* @return [array]  $classes      - An array containing additional class 'product-list-view' if the product view is set to list
*/
add_filter( 'post_class', 'avada_change_product_class' );
if (!function_exists( 'avada_change_product_class' )){
	function avada_change_product_class( $classes ){
		if ( isset( $_SERVER[ 'QUERY_STRING' ] ) ) {
			parse_str( $_SERVER[ 'QUERY_STRING' ], $params );
			if ( isset( $params[ 'product_view' ] ) ){
				$product_view = $params[ 'product_view' ];
				if ( $product_view == 'list' ){
					$classes[] = 'product-list-view';
				}
			}
		}
		return $classes;
	}
}

// Omit closing PHP tag to avoid "Headers already sent" issues.
