<?php get_header(); ?>
<div id="content" class="full-width">
	<div id="post-404page">
		<div class="post-content">
			<?php
			// Render the page titles
			$subtitle = esc_html__( 'Oops, This Page Could Not Be Found!', 'Avada' );
			echo Avada()->template->title_template( $subtitle );
			?>
			<div class="fusion-clearfix"></div>
			<div class="error-page">
				<div class="fusion-columns fusion-columns-3">
					<div class="fusion-column col-lg-4 col-md-4 col-sm-4">
						<div class="error-message">404</div>
					</div>
					<div class="fusion-column col-lg-4 col-md-4 col-sm-4 useful-links">
						<h3><?php esc_html_e( 'Helpful Links', 'Avada' ); ?></h3>
						<?php $circle_class = ( Avada()->settings->get( 'checklist_circle' ) ) ? 'circle-yes' : 'circle-no'; ?>
						<?php wp_nav_menu( array(
							'theme_location' => '404_pages',
							'depth'          => 1,
							'container'      => false,
							'menu_id'        => 'checklist-1',
							'menu_class'     => 'error-menu list-icon list-icon-arrow ' . $circle_class,
							'echo'           => 1,
						) ); ?>
					</div>
					<div class="fusion-column col-lg-4 col-md-4 col-sm-4">
						<h3><?php esc_html_e( 'Search Our Website', 'Avada' ); ?></h3>
						<p><?php esc_html_e( 'Can\'t find what you need? Take a moment and do a search below!', 'Avada' ); ?></p>
						<div class="search-page-search-form">
							<?php echo get_search_form( false ); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer();

// Omit closing PHP tag to avoid "Headers already sent" issues.
