<?php

global $product, $woocommerce;

// Retrieve the permalink if it is not set
$post_permalink = ( ! $post_permalink ) ? get_permalink( $post_id ) : $post_permalink;

// Check if theme options are used as base or if there is an override for post categories
if ( 'enable' == $display_post_categories ) {
	$display_post_categories = true;
} elseif ( 'disable' == $display_post_categories ) {
	$display_post_categories = false;
} else {
	$display_post_categories = Avada()->settings->get( 'cats_image_rollover' );
}

// Check if theme options are used as base or if there is an override for post title
if ( 'enable' == $display_post_title ) {
	$display_post_title = true;
} elseif ( 'disable' == $display_post_title ) {
	$display_post_title = false;
} else {
	$display_post_title = Avada()->settings->get( 'title_image_rollover' );
}

// Set the link on the link icon to a custom url if set in page options
$icon_permalink = ( fusion_get_page_option( 'link_icon_url', $post_id ) != null ) ? fusion_get_page_option( 'link_icon_url', $post_id ) : $post_permalink;

if ( '' == fusion_get_page_option( 'image_rollover_icons', $post_id ) || 'default' == fusion_get_page_option( 'image_rollover_icons', $post_id ) ) {
	if ( Avada()->settings->get( 'link_image_rollover' ) && Avada()->settings->get( 'zoom_image_rollover' ) ) { // link + zoom
		$image_rollover_icons = 'linkzoom';
	} elseif ( Avada()->settings->get( 'link_image_rollover' ) && ! Avada()->settings->get( 'zoom_image_rollover' ) ) { // link
		$image_rollover_icons = 'link';
	} elseif ( ! Avada()->settings->get( 'link_image_rollover' ) && Avada()->settings->get( 'zoom_image_rollover' ) ) { // zoom
		$image_rollover_icons = 'zoom';
	} elseif ( ! Avada()->settings->get( 'link_image_rollover' ) && ! Avada()->settings->get( 'zoom_image_rollover' ) ) { // link
		$image_rollover_icons = 'no';
	} else {
		$image_rollover_icons = 'linkzoom';
	}
} else {
	$image_rollover_icons = fusion_get_page_option( 'image_rollover_icons', $post_id );
}

// Set the link target to blank if the option is set
$link_target = ( 'yes' == fusion_get_page_option( 'link_icon_target', $post_id ) || 'yes' == fusion_get_page_option( 'post_links_target', $post_id ) || ( 'avada_portfolio' == get_post_type() &&  Avada()->settings->get( 'portfolio_link_icon_target' ) && 'default' == fusion_get_page_option( 'link_icon_target', $post_id ) ) ) ? ' target="_blank"' : '';
?>
<div class="fusion-rollover">
	<div class="fusion-rollover-content">

		<?php
		/**
		 * Check if rollover icons should be displayed
		 */
		?>
		<?php if ( 'no' != $image_rollover_icons && 'product' != get_post_type( $post_id ) ) : ?>
			<?php
			/**
			 * If set, render the rollover link icon
			 */
			?>
			<?php if ( 'zoom' != $image_rollover_icons ) : ?>
				<a class="fusion-rollover-link" href="<?php echo $icon_permalink; ?>"<?php echo $link_target; ?>><?php esc_html_e( 'Permalink', 'Avada' ); ?></a>
			<?php endif; ?>

			<?php
			/**
			 * If set, render the rollover zoom icon
			 */
			?>
			<?php if ( 'link' != $image_rollover_icons ) : ?>
				<?php $full_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' ); // Get the image data ?>
				<?php $full_image = ( ! is_array( $full_image ) ) ? array( 0 => '' ) : $full_image; ?>

				<?php
				/**
				 * If a video url is set in the post options, use it inside the lightbox
				 */
				?>
				<?php if ( fusion_get_page_option( 'video_url', $post_id ) ) : ?>
					<?php $full_image[0] = fusion_get_page_option( 'video_url', $post_id ); ?>
				<?php endif; ?>

				<?php
				/**
				 * If both icons will be shown, add a separator
				 */
				?>
				<?php if ( 'linkzoom' == $image_rollover_icons || '' === $image_rollover_icons ) : ?>
					<div class="fusion-rollover-sep"></div>
				<?php endif; ?>

				<?php
				/**
				 * Render the rollover zoom icon if we have an image
				 */
				?>
				<?php if ( $full_image[0] ) : ?>
					<?php
					/**
					 * Only show images of the clicked post
					 * Otherwise, show the first image of every post on the archive page
					 */
					$lightbox_content = ( 'individual' == Avada()->settings->get( 'lightbox_behavior' ) ) ? avada_featured_images_lightbox( $post_id ) : '';
					$data_rel         = ( 'individual' == Avada()->settings->get( 'lightbox_behavior' ) ) ? 'iLightbox[gallery' . $post_id . ']' : 'iLightbox[gallery' . $gallery_id . ']';
					?>
					<a class="fusion-rollover-gallery" href="<?php echo $full_image[0]; ?>" data-id="<?php echo $post_id; ?>" data-rel="<?php echo $data_rel; ?>" data-title="<?php echo get_post_field( 'post_title', get_post_thumbnail_id( $post_id ) ); ?>" data-caption="<?php echo get_post_field( 'post_excerpt', get_post_thumbnail_id( $post_id ) ); ?>">
						<?php esc_html_e( 'Gallery', 'Avada' ); ?>
					</a>
					<?php echo $lightbox_content; ?>
				<?php endif; ?>
			<?php endif; ?>
		<?php endif; ?>

		<?php
		/**
		 * Check if we should render the post title on the rollover
		 */
		?>
		<?php if ( $display_post_title ) : ?>
			<h4 class="fusion-rollover-title">
				<a href="<?php echo $icon_permalink; ?>"<?php echo $link_target; ?>>
					<?php echo get_the_title( $post_id ); ?>
				</a>
			</h4>
		<?php endif; ?>

		<?php
		/**
		 * Check if we should render the post categories on the rollover
		 */
		?>
		<?php if ( $display_post_categories ) : ?>
			<?php
			// Determine the correct taxonomy
			$post_taxonomy = '';
			if ( 'post' == get_post_type( $post_id ) ) {
				$post_taxonomy = 'category';
			} elseif ( 'avada_portfolio' == get_post_type( $post_id ) ) {
				$post_taxonomy = 'portfolio_category';
			} elseif ( 'product' == get_post_type( $post_id ) ) {
				$post_taxonomy = 'product_cat';
			}
			?>

			<?php echo get_the_term_list( $post_id, $post_taxonomy, '<div class="fusion-rollover-categories">', ', ', '</div>' ); ?>
		<?php endif; ?>

		<?php if ( class_exists( 'WooCommerce' ) && $woocommerce->cart ) : ?>
			<?php $items_in_cart = array(); ?>
			<?php if ( $woocommerce->cart->get_cart() && is_array( $woocommerce->cart->get_cart() ) ) : ?>
				<?php foreach ( $woocommerce->cart->get_cart() as $cart ) : ?>
					<?php $items_in_cart[] = $cart['product_id']; ?>
				<?php endforeach; ?>
			<?php endif; ?>

			<?php $id = get_the_ID(); ?>
			<?php $in_cart = in_array( $id, $items_in_cart ); ?>
			<?php if ( $in_cart ) : ?>
				<span class="cart-loading">
					<a href="<?php echo $woocommerce->cart->get_cart_url(); ?>">
						<i class="fusion-icon-check-square-o"></i>
						<span class="view-cart"><?php esc_html_e( 'View Cart', 'Avada' ); ?></span>
					</a>
				</span>
			<?php else : ?>
				<span class="cart-loading">
					<a href="<?php echo $woocommerce->cart->get_cart_url(); ?>">
						<i class="fusion-icon-spinner"></i>
						<span class="view-cart"><?php esc_html_e( 'View Cart', 'Avada' ); ?></span>
					</a>
				</span>
			<?php endif; ?>
		<?php endif; ?>

		<?php
		/**
		 * Check if we should render the woo product price
		 */
		?>
		<?php if ( $display_woo_rating ) : ?>
			<?php woocommerce_get_template( 'loop/rating.php' ); ?>
		<?php endif; ?>

		<?php
		/**
		 * Check if we should render the woo product price
		 */
		?>
		<?php if ( $display_woo_price ) : ?>
			<?php woocommerce_get_template( 'loop/price.php' ); ?>
		<?php endif; ?>

		<?php
		/**
		 * Check if we should render the woo "add to cart" and "details" buttons
		 */
		?>
		<?php if ( $display_woo_buttons ) : ?>
			<div class="fusion-product-buttons">
				<?php
				/**
				 * avada_woocommerce_buttons_on_rollover hook.
				 *
				 * @hooked FusionTemplateWoo::avada_woocommerce_template_loop_add_to_cart - 10 (outputs add to cart button)
				 * @hooked FusionTemplateWoo::avada_woocommerce_rollover_buttons_linebreak - 15 (outputs line break for the buttons, needed for clean version)
				 * @hooked FusionTemplateWoo::show_details_button - 20 (outputs the show details button)
				 */
				do_action( 'avada_woocommerce_buttons_on_rollover' );
				?>
			</div>
		<?php endif; ?>
	</div>
</div>
