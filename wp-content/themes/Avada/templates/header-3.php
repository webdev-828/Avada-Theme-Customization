<div class="fusion-header-sticky-height"></div>
<div class="fusion-header">
	<div class="fusion-row">
		<div class="fusion-header-v6-content">
			<?php 
			avada_logo();
			$menu = avada_main_menu( true );
			?>
			
			<div class="fusion-flyout-menu-icons">
				<?php if ( class_exists('WooCommerce') && Avada()->settings->get( 'woocommerce_cart_link_main_nav' ) ) :
					global $woocommerce;
				
					if ( Avada()->settings->get( 'woocommerce_cart_counter') && $woocommerce->cart->get_cart_contents_count() ) {
						$cart_link_text = '<span class="fusion-widget-cart-number">' . $woocommerce->cart->get_cart_contents_count() . '</span>';
						$cart_link_class = ' fusion-widget-cart-counter';
					} else {
						$cart_link_text = '';
						$cart_link_class  = '';
					}
				?>
					<div class="fusion-flyout-cart-wrapper">
						<a href="<?php echo get_permalink( get_option( 'woocommerce_cart_page_id' ) ); ?>" class="fusion-icon fusion-icon-shopping-cart<?php echo $cart_link_class; ?>"><?php echo $cart_link_text; ?></a>
					</div>
				<?php endif; ?>

				<?php if (  Avada()->settings->get( 'main_nav_search_icon' ) ) : ?>
					<div class="fusion-flyout-search-toggle">
						<div class="fusion-toggle-icon">
							<div class="fusion-toggle-icon-line"></div>
							<div class="fusion-toggle-icon-line"></div>
							<div class="fusion-toggle-icon-line"></div>
						</div>
						<a class="fusion-icon fusion-icon-search"></a>
					</div>
				<?php endif; ?>

				<div class="fusion-flyout-menu-toggle">
					<div class="fusion-toggle-icon-line"></div>
					<div class="fusion-toggle-icon-line"></div>
					<div class="fusion-toggle-icon-line"></div>
				</div>
			</div>
		</div>

		<div class="fusion-main-menu fusion-flyout-menu">
			<?php echo $menu; ?>
		</div>

		<?php if (  Avada()->settings->get( 'main_nav_search_icon' ) ) : ?>
			<div class="fusion-flyout-search">
				<?php get_search_form(); ?>
			</div>
		<?php endif; ?>

		<div class="fusion-flyout-menu-bg"></div>		
	</div>
</div>
