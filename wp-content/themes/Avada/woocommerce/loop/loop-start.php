<?php
/**
 * Product Loop Start
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce_loop;


// Reset according to sidebar or fullwidth pages
if ( empty( $woocommerce_loop['columns'] ) ) {
	if ( is_shop() || is_product_category() || is_product_tag() || is_tax( 'product_brand' ) || is_tax( 'images_collections' ) ) {

		if ( is_shop() ) {
			$woocommerce_loop['columns'] = Avada()->settings->get( 'woocommerce_shop_page_columns' );
		}

		if ( is_product_category() ||
			is_product_tag() ||
			is_tax( 'product_brand' ) ||
			is_tax( 'images_collections' )
		) {
			$woocommerce_loop['columns'] = Avada()->settings->get( 'woocommerce_archive_page_columns' );
			$columns = Avada()->settings->get( 'woocommerce_archive_page_columns' );
		}

	}
}
?>
<ul class="products clearfix products-<?php echo $woocommerce_loop['columns']; ?>">
