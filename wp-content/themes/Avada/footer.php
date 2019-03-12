					<?php do_action( 'avada_after_main_content' ); ?>
				</div>  <!-- fusion-row -->
			</div>  <!-- #main -->
			<?php do_action( 'avada_after_main_container' ); ?>

			<?php global $social_icons; ?>

			<?php if ( false !== strpos( Avada()->settings->get( 'footer_special_effects' ), 'footer_sticky' ) ) : ?>
				</div>
			<?php endif; ?>

			<?php
			/**
			 * Get the correct page ID
			 */
			$c_pageID = Avada::c_pageID();
			?>

			<?php
			/**
			 * Only include the footer
			 */
			?>
			<?php if ( ! is_page_template( 'blank.php' ) ) : ?>
				<?php $footer_parallax_class = ( 'footer_parallax_effect' == Avada()->settings->get( 'footer_special_effects' ) ) ? ' fusion-footer-parallax' : ''; ?>

				<div class="fusion-footer<?php echo $footer_parallax_class; ?>">

					<?php
					/**
					 * Check if the footer widget area should be displayed
					 */
					?>
					<?php if ( ( Avada()->settings->get( 'footer_widgets' ) && 'no' != get_post_meta( $c_pageID, 'pyre_display_footer', true ) ) || ( ! Avada()->settings->get( 'footer_widgets' ) && 'yes' == get_post_meta( $c_pageID, 'pyre_display_footer', true ) ) ) : ?>
						<?php $footer_widget_area_center_class = ( Avada()->settings->get( 'footer_widgets_center_content' ) ) ? ' fusion-footer-widget-area-center' : ''; ?>

						<footer class="fusion-footer-widget-area fusion-widget-area<?php echo $footer_widget_area_center_class; ?>">
							<div class="fusion-row">
								<div class="fusion-columns fusion-columns-<?php echo Avada()->settings->get( 'footer_widgets_columns' ); ?> fusion-widget-area">
									<?php
									/**
									 * Check the column width based on the amount of columns chosen in Theme Options
									 */
									$column_width = ( '5' == Avada()->settings->get( 'footer_widgets_columns' ) ) ? 2 : 12 / Avada()->settings->get( 'footer_widgets_columns' );
									?>

									<?php
									/**
									 * Render as many widget columns as have been chosen in Theme Options
									 */
									?>
									<?php for ( $i = 1; $i < 7; $i++ ) : ?>
										<?php if ( $i <= Avada()->settings->get( 'footer_widgets_columns' ) ) : ?>
											<div class="fusion-column<?php echo ( $i == Avada()->settings->get( 'footer_widgets_columns' ) ) ? ' fusion-column-last' : ''; ?> col-lg-<?php echo $column_width; ?> col-md-<?php echo $column_width; ?> col-sm-<?php echo $column_width; ?>">
												<?php if ( function_exists( 'dynamic_sidebar' ) && dynamic_sidebar( 'avada-footer-widget-' . $i ) ) : ?>
													<?php
													/**
													 * All is good, dynamic_sidebar() already called the rendering
													 */
													?>
												<?php endif; ?>
											</div>
										<?php endif; ?>
									<?php endfor; ?>

									<div class="fusion-clearfix"></div>
								</div> <!-- fusion-columns -->
							</div> <!-- fusion-row -->
						</footer> <!-- fusion-footer-widget-area -->
					<?php endif; // end footer wigets check ?>

					<?php
					/**
					 * Check if the footer copyright area should be displayed
					 */
					?>
					<?php if ( ( Avada()->settings->get( 'footer_copyright' ) && 'no' != get_post_meta( $c_pageID, 'pyre_display_copyright', true ) ) || ( ! Avada()->settings->get( 'footer_copyright' ) && 'yes' == get_post_meta( $c_pageID, 'pyre_display_copyright', true ) ) ) : ?>
						<?php $footer_copyright_center_class = ( Avada()->settings->get( 'footer_copyright_center_content' ) ) ? ' fusion-footer-copyright-center' : ''; ?>

						<footer id="footer" class="fusion-footer-copyright-area<?php echo $footer_copyright_center_class; ?>">
							<div class="fusion-row">
								<div class="fusion-copyright-content">

									<?php
									/**
									 * avada_footer_copyright_content hook
									 *
									 * @hooked avada_render_footer_copyright_notice - 10 (outputs the HTML for the Theme Options footer copyright text)
									 * @hooked avada_render_footer_social_icons - 15 (outputs the HTML for the footer social icons)
									 */
									do_action( 'avada_footer_copyright_content' );
									?>

								</div> <!-- fusion-fusion-copyright-content -->
							</div> <!-- fusion-row -->
						</footer> <!-- #footer -->
					<?php endif; // end footer copyright area check ?>
				</div> <!-- fusion-footer -->
			<?php endif; // end is not blank page check ?>
		</div> <!-- wrapper -->

		<?php
		/**
		 * Check if boxed side header layout is used; if so close the #boxed-wrapper container
		 */
		?>
		<?php if ( ( ( 'Boxed' == Avada()->settings->get( 'layout' ) && 'default' == get_post_meta( $c_pageID, 'pyre_page_bg_layout', true ) ) || 'boxed' == get_post_meta( $c_pageID, 'pyre_page_bg_layout', true ) ) && 'Top' != Avada()->settings->get( 'header_position' ) ) : ?>
			</div> <!-- #boxed-wrapper -->
		<?php endif; ?>

		<a class="fusion-one-page-text-link fusion-page-load-link"></a>

		<!-- W3TC-include-js-head -->

		<?php wp_footer(); ?>

		<?php
		/**
		 * Echo the scripts added to the "before </body>" field in Theme Options
		 */
		echo Avada()->settings->get( 'space_body' );
		?>

		<!--[if lte IE 8]>
			<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/respond.js"></script>
		<![endif]-->
	</body>
</html>
