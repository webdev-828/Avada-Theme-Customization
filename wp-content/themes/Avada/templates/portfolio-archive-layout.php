<?php
global $wp_query;

// Set the portfolio main classes
$portfolio_classes[] = 'fusion-portfolio';

$portfolio_layout_setting = strtolower( Avada()->settings->get( 'portfolio_archive_layout' ) );
$portfolio_layout         = explode( ' ', $portfolio_layout_setting );
$portfolio_columns        = $portfolio_layout[1];
$portfolio_layout         = 'fusion-portfolio-' . $portfolio_columns;
$portfolio_classes[]      = $portfolio_layout;

/**
 * Get the number of columns
 */
$portfolio_columns_int = avada_get_portfolio_columns( $portfolio_columns );

// If one column text layout is used, add special class
if ( strpos( $portfolio_layout_setting, 'one' ) && ! strpos( $portfolio_layout_setting, 'text' ) ) {
	$portfolio_classes[] = ' fusion-portfolio-one-nontext';
}

// Add the text class, if a text layout is used
if ( strpos( $portfolio_layout_setting, 'text' ) || strpos( $portfolio_layout_setting, 'one' ) ) {
	$portfolio_classes[] = 'fusion-portfolio-text';
}

// For text layouts add the class for boxed/unboxed
$portfolio_text_layout = 'unboxed';
if ( strpos( $portfolio_layout_setting, 'text' ) ) {
	$portfolio_text_layout = Avada()->settings->get( 'portfolio_text_layout' );
	$portfolio_classes[]   = 'fusion-portfolio-' . $portfolio_text_layout;
}

// Set the correct image size
$portfolio_image_size = sprintf( 'portfolio-%s', $portfolio_columns );
if ( 'full' == Avada()->settings->get( 'portfolio_featured_image_size' ) || 'fusion-portfolio-grid' == $portfolio_layout ) {
	$portfolio_image_size = 'full';
}

$post_featured_image_size_dimensions = avada_get_image_size_dimensions( $portfolio_image_size );

// Get the column spacing
$column_spacing_class = $column_spacing = '';
if ( ! strpos( $portfolio_layout_setting, 'one' ) ) {
	$column_spacing_class = ' fusion-col-spacing';
	$column_spacing = sprintf( ' style="padding:%spx;"', str_replace( 'px', '', Avada()->settings->get( 'portfolio_column_spacing' ) ) / 2 );
}

// Get the correct ID of the archive
$archive_id = get_queried_object_id();
?>

<div class="<?php echo implode( ' ', $portfolio_classes ); ?>">

	<?php
	/**
	 * Render category description if it is set
	 */
	?>
	<?php if ( category_description() ) : ?>
		<div id="post-<?php echo get_the_ID(); ?>" <?php post_class( 'post' ); ?>>
			<div class="post-content">
				<?php echo category_description(); ?>
			</div>
		</div>
	<?php endif; ?>

	<div class="fusion-portfolio-wrapper" data-picturesize="<?php echo ( 'full' != $portfolio_image_size ) ? 'fixed' : 'auto'; ?>" data-pages="<?php echo $wp_query->max_num_pages; ?>">

		<?php while( have_posts() ): the_post(); ?>

			<?php if ( Avada()->settings->get( 'featured_image_placeholder' ) || has_post_thumbnail() ) : ?>

				<div class="fusion-portfolio-post post-<?php echo get_the_ID(); ?> <?php echo $column_spacing_class; ?>"<?php echo $column_spacing; ?>>

					<?php
					/**
					 * Open portfolio-item-wrapper for text layouts
					 */
					?>
					<?php if ( strpos( $portfolio_layout_setting, 'text' ) ) : ?>
						<div class="fusion-portfolio-content-wrapper">
					<?php endif; ?>

						<?php
						/**
						 * If no featured image is present,
						 * on one column layouts render the video set in page options.
						 */
						?>
						<?php if ( ! has_post_thumbnail() && fusion_get_page_option( 'video', $post->ID ) ) : ?>
							<?php
							/**
							 * For the portfolio one column layout we need a fixed max-width.
							 * For all other layouts get the calculated max-width from the image size
							 */
							?>
							<?php $video_max_width = ( 'fusion-portfolio-one' == $portfolio_layout && ! strpos( $portfolio_layout_setting, 'text' ) ) ? '540px' : $post_featured_image_size_dimensions['width']; ?>
							<div class="fusion-image-wrapper fusion-video" style="max-width:<?php echo $video_max_width; ?>;">
								<?php echo fusion_get_page_option( 'video', $post->ID ); ?>
							</div>

							<?php
							/**
							 * On every other other layout render the featured image
							 */
							?>
						<?php else : ?>
							<?php
							if ( $portfolio_image_size == 'full' ) {
								Avada()->images->set_grid_image_meta( array( 'layout' => 'portfolio_full', 'columns' => $portfolio_columns_int ) );
							}
							echo avada_render_first_featured_image_markup( $post->ID, $portfolio_image_size, get_permalink( $post->ID ), true );
							Avada()->images->set_grid_image_meta( array() );
							?>

						<?php endif; ?>

						<?php
						/**
						 * If we don't have a text layout and not a one column layout,
						 * then only render rich snippets.
						 */
						?>
						<?php if ( ! strpos( $portfolio_layout_setting, 'text' ) && ! strpos( $portfolio_layout_setting, 'one' ) ) : ?>
							<?php echo avada_render_rich_snippets_for_pages(); ?>
							<?php
							/**
							 * If we have a text layout render its contents
							 */
							?>
						<?php else : ?>
							<div class="fusion-portfolio-content">
								<?php
								/**
								 * Render the post title
								 */
								?>
								<?php echo avada_render_post_title( $post->ID ); ?>
								<?php
								/**
								 * Render the post categories
								 */
								?>
								<h4><?php echo get_the_term_list( $post->ID, 'portfolio_category', '', ', ', ''); ?></h4>
								<?php echo avada_render_rich_snippets_for_pages( false ); ?>

								<?php
								/**
								 * For boxed layouts add a content separator if there is a post content
								 */
								?>
								<?php if ( 'boxed' == $portfolio_text_layout && avada_get_portfolio_excerpt_length( $current_page_id ) !== '0' ) : ?>
									<div class="fusion-content-sep"></div>
								<?php endif; ?>

								<div class="fusion-post-content">
									<?php
									/**
									 * avada_portfolio_post_content hook
									 *
									 * @hooked avada_get_portfolio_content - 10 (outputs the post content)
									 */
									do_action( 'avada_portfolio_post_content', $archive_id );
									?>

									<?php
									/**
									 * On one column layouts render the "Learn More" and "View Project" buttons
									 */
									?>
									<?php if ( strpos( $portfolio_layout_setting, 'one' ) ) : ?>
										<div class="fusion-portfolio-buttons">
											<?php
											/**
											 * Render "Learn More" button
											 */
											?>
											<a href="<?php echo get_permalink( $post->ID ); ?>" class="fusion-button fusion-button-small fusion-button-default fusion-button-<?php echo strtolower( Avada()->settings->get( 'button_shape' ) ); ?> fusion-button-<?php echo strtolower( Avada()->settings->get( 'button_type' ) ); ?>">
												<?php esc_html_e( 'Learn More', 'Avada' ); ?>
											</a>
											<?php
											/**
											 * Render the "View Project" button only is a project url was set
											 */
											?>
											<?php if ( fusion_get_page_option( 'project_url', $post->ID ) ) : ?>
												<a href="<?php echo fusion_get_page_option( 'project_url', $post->ID ); ?>" class="fusion-button fusion-button-small fusion-button-default fusion-button-<?php echo strtolower( Avada()->settings->get( 'button_shape' ) ); ?> fusion-button-<?php echo strtolower( Avada()->settings->get( 'button_type' ) ); ?>">
													<?php esc_html_e( ' View Project', 'Avada' ); ?>
												</a>
											<?php endif; ?>
										</div>
									<?php endif; ?>

								</div><!-- end post-content -->

								<?php
								/**
								 * On unboxed one column layouts render a separator at the bottom of the post
								 */
								?>
								<?php if ( strpos( $portfolio_layout_setting, 'one' ) && 'unboxed' == $portfolio_text_layout ) : ?>
									<div class="fusion-clearfix"></div>
									<div class="fusion-separator sep-double"></div>
								<?php endif; ?>

							</div><!-- end portfolio-content -->

						<?php endif; // end template check ?>

					<?php
					/**
					 * Close portfolio-item-wrapper for text layouts
					 */
					?>
					<?php if ( strpos( $portfolio_layout_setting, 'text' ) ) : ?>
						</div>
					<?php endif; ?>

				</div><!-- end portfolio-post -->

			<?php endif; // placeholders or featured image ?>
		<?php endwhile; ?>

	</div><!-- end portfolio-wrapper -->

	<?php
	/**
	 * Render the pagination
	 */
	?>
	<?php fusion_pagination( '', 2 ); ?>
	<?php
	/**
	 * If infinite scroll with "load more" button is used
	 */
	?>
	<?php if ( 'load_more_button' == Avada()->settings->get( 'grid_pagination_type' ) ) : ?>
		<div class="fusion-load-more-button fusion-portfolio-button fusion-clearfix">
			<?php echo apply_filters( 'avada_load_more_posts_name', esc_html__( 'Load More Posts', 'Avada' ) ); ?>
		</div>
	<?php endif; ?>

	<?php wp_reset_query(); ?>
</div><!-- end fusion-portfolio -->
