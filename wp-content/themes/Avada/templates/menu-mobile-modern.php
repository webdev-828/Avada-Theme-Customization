<?php if ( 'modern' == Avada()->settings->get( 'mobile_menu_design' ) ) : ?>
	<?php $header_content_3 = Avada()->settings->get( 'header_v4_content' ); ?>
	<div class="fusion-mobile-menu-icons">
		<?php // Make sure mobile menu toggle is not loaded when ubermenu is used ?>
		<?php if ( ! function_exists( 'ubermenu_get_menu_instance_by_theme_location' ) || ( function_exists( 'ubermenu_get_menu_instance_by_theme_location' ) && ! ubermenu_get_menu_instance_by_theme_location( 'main_navigation' ) ) ) : ?>
			<a href="#" class="fusion-icon fusion-icon-bars"></a>
		<?php endif; ?>

		<?php if ( ( 'v4' == Avada()->settings->get( 'header_layout' ) || 'Top' != Avada()->settings->get( 'header_position' ) )  && ( 'Tagline And Search' == $header_content_3 || 'Search' == $header_content_3 ) ) : ?>
			<a href="#" class="fusion-icon fusion-icon-search"></a>
		<?php endif; ?>

		<?php if ( class_exists('WooCommerce') && Avada()->settings->get( 'woocommerce_cart_link_main_nav' ) ) : ?>
			<a href="<?php echo get_permalink( get_option( 'woocommerce_cart_page_id' ) ); ?>" class="fusion-icon fusion-icon-shopping-cart"></a>
		<?php endif; ?>
	</div>
<?php endif;

// Omit closing PHP tag to avoid "Headers already sent" issues.
