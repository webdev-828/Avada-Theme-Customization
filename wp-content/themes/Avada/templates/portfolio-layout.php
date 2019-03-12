<?php while ( have_posts() ): the_post(); ?>

	<div id="post-<?php echo get_the_ID(); ?>" <?php post_class( 'fusion-portfolio-page-content' ); ?>>
		<?php
		/**
		 * Render the rich snippets
		 */
		echo avada_render_rich_snippets_for_pages();
		?>

		<?php
		/**
		 * Render the featured images
		 */
		echo avada_featured_images_for_pages();
		?>

		<?php
		/**
		 * Portfolio page content
		 */
		?>
		<div class="post-content">
			<?php the_content(); ?>
			<?php avada_link_pages(); ?>
		</div>
	</div>

	<?php
	/**
	 * Set the ID of the portfolio page as variable to have it in the posts loop
	 */
	$current_page_id = $post->ID;
	?>

	<?php
	/**
	 * Get the page template slug for later check for text layouts
	 */
	$current_page_template = str_replace( '.php', '', get_page_template_slug( $current_page_id ) );
	?>

	<?php
	/**
	 * Get the number of columns
	 */
	$current_page_columns = avada_get_portfolio_columns( $current_page_template );
	?>

	<?php
	/**
	 * Get the boxed/unboxed setting for text layouts
	 */
	$current_page_text_layout = ( strpos( $current_page_template, 'text' ) ) ? fusion_get_option( 'portfolio_text_layout', 'portfolio_text_layout', $current_page_id ) : 'unboxed';
	?>

<?php endwhile; ?>

<?php
/**
 * Check if we have paged content
 */
if (  is_front_page() ) {
	$paged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
} else {
	$paged = ( get_query_var( 'paged') ) ? get_query_var( 'paged') : 1;
}
?>

<?php
// If TO setting is set to 0, all items should show
$number_of_portfolio_items = Avada()->settings->get( 'portfolio_items' );
if ( '0' == $number_of_portfolio_items ) {
	$number_of_portfolio_items = -1;
}

/**
 * Initialize the args that will be needed for the portfolio posts query
 */
$args = array(
	'post_type'      => 'avada_portfolio',
	'paged'          => $paged,
	'posts_per_page' => $number_of_portfolio_items,
);
?>

<?php
/**
 * If placeholder images are disabled,
 * add the _thumbnail_id meta key to the query to only retrieve posts with featured images.
 */
if ( ! Avada()->settings->get( 'featured_image_placeholder' ) ) {
	$args['meta_key'] = '_thumbnail_id';
}
?>

<?php
/**
 * Get the categories set by user to be included.
 */
$categories_to_display_ids = fusion_get_page_option( 'portfolio_category', get_the_ID() );
?>

<?php
/**
 * If "All categories" was selected in page options, clear that array entry.
 */
if ( is_array( $categories_to_display_ids ) && 0 == $categories_to_display_ids[0] ) {
	unset( $categories_to_display_ids[0] );
	$categories_to_display_ids = array_values( $categories_to_display_ids );
}
?>

<?php
/**
 * If no categories are chosen or "All categories",
 * we need to load all available categories.
 */
$show_all_categories = false;
if ( ! is_array( $categories_to_display_ids ) || 0 == count( $categories_to_display_ids ) ) {
	$show_all_categories = true;
	$terms = get_terms( 'portfolio_category' );

	if ( ! is_array( $categories_to_display_ids ) ) {
		$categories_to_display_ids = array();
	}

	foreach ( $terms as $term ) {
		$categories_to_display_ids[] = $term->term_id;
	}
}
?>

<?php
/**
 * Get the category slugs and names.
 */
$categories_to_display_slugs_names = array();
if ( is_array( $categories_to_display_ids ) && 0 < count( $categories_to_display_ids ) ) {
	foreach ( $categories_to_display_ids as $category_id ) {
		$category_object = get_term( $category_id, 'portfolio_category' );
		// Only add the category to the slugs and names array if they have posts assigned to them
		if ( 0 < $category_object->count ) {
			$categories_to_display_slugs_names[$category_object->slug] = $category_object->name;
		}
	}
}
?>

<?php
// Sort the category slugs alphabetically.
if ( is_array( $categories_to_display_slugs_names ) && ! function_exists( 'TO_activated' ) ) {
	asort( $categories_to_display_slugs_names );
// Sort them according to custom taxonomy order plugin, if it is installed
} else if ( is_array( $categories_to_display_slugs_names ) && function_exists( 'TO_activated' ) ) {
	$term_names = array();
	$terms = get_terms( 'portfolio_category' );

	foreach ( $terms as $term ) {
		$term_names[$term->slug] = $term->name;
	}

	$categories_to_display_slugs_names = Avada_Sanitize::order_array_like_array( $categories_to_display_slugs_names, $term_names );
}
?>

<?php
/**
 * Add the correct term ids to the args array.
 */
if ( ! empty( $categories_to_display_ids ) ) {
	$args['tax_query'][] = array(
		'taxonomy' => 'portfolio_category',
		'field'    => 'id',
		'terms'    => $categories_to_display_ids
	);
}
?>

<?php
/**
 * Retrieve the portfolio posts that fit the arguments
 */
$portfolio_posts_to_display = new WP_Query( $args );
?>

<?php
/**
 * Check if the page is passowrd protected.
 */
?>
<?php if ( ! post_password_required( $current_page_id ) ) : ?>
	<?php
	/**
	 * Check if we can display filters
	 */
	?>
	<?php if ( is_array( $categories_to_display_slugs_names ) && ! empty( $categories_to_display_slugs_names ) && 'no' != fusion_get_page_option( 'portfolio_filters', $current_page_id ) ) : ?>
		<?php
		/**
		 * First add the "All" filter then loop through all chosen categories
		 */
		?>
		<ul class="fusion-filters clearfix">
			<?php
			/**
			 * Check if the "All" filter should be displayed.
			 */
			?>
			<?php if ( 'yes' == fusion_get_page_option( 'portfolio_filters', $current_page_id ) ) : ?>
				<li class="fusion-filter fusion-filter-all fusion-active">
					<a data-filter="*" href="#"><?php echo apply_filters( 'avada_portfolio_all_filter_name', esc_html__( 'All', 'Avada' ) ); ?></a>
				</li>
				<?php $first_filter = false; ?>
			<?php else : ?>
				<?php $first_filter = true; ?>
			<?php endif; ?>

			<?php foreach ( $categories_to_display_slugs_names as $category_tax_slug => $category_tax_name ) : ?>
				<?php
				/**
				 * Set the first category filter to active, if the all filter isn't shown
				 */
				$active_class = '';
				if ( $first_filter ) {
					$active_class = ' fusion-active';
					$first_filter = false;
				}
				?>
				<li class="fusion-filter fusion-hidden<?php echo $active_class; ?>">
					<a data-filter=".<?php echo urldecode( $category_tax_slug ); ?>" href="#"><?php echo $category_tax_name; ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<?php
	/**
	 * Get the correct featured image size
	 */
	$post_featured_image_size = avada_get_portfolio_image_size( $current_page_id );
	$post_featured_image_size_dimensions = avada_get_image_size_dimensions( $post_featured_image_size );
	?>

	<?php
	/**
	 * Set picture size as data attribute; needed for resizing placeholders
	 */
	$data_picture_size = ( 'full' != $post_featured_image_size ) ? 'fixed' : 'auto';
	?>

	<div class="fusion-portfolio-wrapper" data-picturesize="<?php echo $data_picture_size; ?>" data-pages="<?php echo $portfolio_posts_to_display->max_num_pages; ?>">
		<?php
		/**
		 * For non one column layouts check if column spacing is used, and if, how big it is.
		 */
		$custom_colulmn_spacing = false;
		if ( ! strpos( $current_page_template, 'one' ) ) {
			// Page option set
			if ( fusion_get_page_option( 'portfolio_column_spacing', $current_page_id ) != null ) {
				$custom_colulmn_spacing = true;
				$column_spacing = fusion_get_page_option( 'portfolio_column_spacing', $current_page_id ) / 2;
			// Page option not set, but theme option
			} else if ( Avada()->settings->get( 'portfolio_column_spacing' ) ) {
				$custom_colulmn_spacing = true;
				$column_spacing = Avada()->settings->get( 'portfolio_column_spacing' ) / 2;
			}
			?>
			<style type="text/css">.fusion-portfolio-wrapper{margin: 0 <?php echo ( -1 ) * $column_spacing; ?>px;}.fusion-portfolio-wrapper .fusion-col-spacing{padding:<?php echo $column_spacing; ?>px;}</style>
			<?php
		}
		?>

		<?php
		/**
		 * Loop through all the posts retrieved through our query based on chosen categories
		 */
		?>
		<?php while ( $portfolio_posts_to_display->have_posts() ) : $portfolio_posts_to_display->the_post(); ?>
			<?php
			/**
			 * Set the post permalink correctly.
			 * this is important for prev/next navigation on single portfolio pages
			 */
			$post_permalink = ( ! empty( $categories_to_display_ids ) && ! $show_all_categories ) ? fusion_add_url_parameter( get_permalink(), 'portfolioID', $current_page_id ) : get_permalink();
			?>

			<?php
			/**
			 * Include the post categories as css classes for later useage with filters
			 */
			$post_classes = '';
			$post_categories = get_the_terms( $post->ID, 'portfolio_category' );

			if ( $post_categories ) {
				foreach ( $post_categories as $post_category ) {
					$post_classes .= urldecode( $post_category->slug ) . ' ';
				}
			}
			?>

			<?php
			/**
			 * Add the col-spacing class if needed
			 */
			if ( $custom_colulmn_spacing ) {
				$post_classes .= 'fusion-col-spacing';
			}
			?>

			<?php
			/**
			 * Add correct post class for image orientation
			 */
			if ( 'full' == $post_featured_image_size ) {
				$featured_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
				$post_classes .= ' ' . avada_get_image_orientation_class( $featured_image );
			}
			?>

			<!-- the portfolio post -->
			<div class="fusion-portfolio-post post-<?php echo get_the_ID(); ?> <?php echo $post_classes; ?>">
				<?php
				/**
				 * Open fusion-portfolio-content-wrapper for text layouts
				 */
				?>
				<?php if ( strpos( $current_page_template, 'text' ) ) : ?>
					<div class="fusion-portfolio-content-wrapper">
				<?php endif; ?>

					<?php
					/**
					 * Render the video set in page options if no featured image is present
					 */
					?>
					<?php if ( ! has_post_thumbnail() && fusion_get_page_option( 'video', $post->ID ) ) : ?>
						<?php
						/**
						 * For the portfolio one column layout we need a fixed max-width.
						 * For all other layouts get the calculated max-width from the image size.
						 */
						$video_max_width = ( $current_page_template == 'portfolio-one-column' ) ? '540px' : $post_featured_image_size_dimensions['width'];
						?>

						<div class="fusion-image-wrapper fusion-video" style="max-width:<?php echo $video_max_width; ?>;">
							<?php echo fusion_get_page_option( 'video', $post->ID ); ?>
						</div>
					<?php else : // On every other other layout render the featured image. ?>

						<?php
						if ( $post_featured_image_size == 'full' ) {
							Avada()->images->set_grid_image_meta( array( 'layout' => 'portfolio_full', 'columns' => $current_page_columns ) );
						}
						$featured_image_markup = avada_render_first_featured_image_markup( $post->ID, $post_featured_image_size, $post_permalink, true );
						Avada()->images->set_grid_image_meta( array() );
						?>

						<?php echo $featured_image_markup; ?>
					<?php endif; ?>

					<?php
					/**
					 * If we don't have a text layout and not a one column layout only render rich snippets.
					 * If we have a text layout render its contents
					 */
					?>
					<?php if ( ! strpos( $current_page_template, 'text' ) && ! strpos( $current_page_template, 'one' ) ) : ?>
						<?php echo avada_render_rich_snippets_for_pages(); ?>
					<?php else : ?>
						<div class="fusion-portfolio-content">
							<?php echo avada_render_post_title( $post->ID ); ?>
							<?php
							/**
							 * Render the post categories
							 */
							?>
							<h4><?php echo get_the_term_list( $post->ID, 'portfolio_category', '', ', ', '' ); ?></h4>
							<?php echo avada_render_rich_snippets_for_pages( false ); ?>

							<?php
							/**
							 * For boxed layouts add a content separator if there is a post content
							 */
							?>
							<?php if ( 'boxed' == $current_page_text_layout && avada_get_portfolio_excerpt_length( $current_page_id ) !== '0' ) : ?>
								<div class="fusion-content-sep"></div>
							<?php endif; ?>

							<div class="fusion-post-content">
								<?php
								/**
								 * avada_portfolio_post_content hook
								 *
								 * @hooked avada_get_portfolio_content - 10 (outputs the post content)
								 */
								do_action( 'avada_portfolio_post_content', $current_page_id );
								?>

								<?php
								/**
								 * On one column layouts render the "Learn More" and "View Project" buttons
								 */
								?>
								<?php if ( strpos( $current_page_template, 'one' ) ) : ?>
									<div class="fusion-portfolio-buttons">
										<a href="<?php echo $post_permalink; ?>" class="fusion-button fusion-button-small fusion-button-default fusion-button-<?php echo strtolower( Avada()->settings->get( 'button_shape' ) ); ?> fusion-button-<?php echo strtolower( Avada()->settings->get( 'button_type' ) ); ?>">
											<?php esc_html_e( 'Learn More', 'Avada' ); ?>
										</a>
										<?php if ( fusion_get_page_option( 'project_url', $post->ID ) ) : ?>
											<a href="<?php echo fusion_get_page_option( 'project_url', $post->ID ); ?>" class="fusion-button fusion-button-small fusion-button-default fusion-button-<?php echo strtolower( Avada()->settings->get( 'button_shape' ) ); ?> fusion-button-<?php echo strtolower( Avada()->settings->get( 'button_type' ) ); ?>">
												<?php esc_html_e( 'View Project', 'Avada' ); ?>
											</a>
										<?php endif; ?>
									</div>
								<?php endif; ?>
							</div><!-- end post-content -->

							<?php
							/**
							 * On unboxed one column layouts render a separator at the bottom of the post.
							 */
							?>
							<?php if ( strpos( $current_page_template, 'one' ) && 'unboxed' == $current_page_text_layout ) : ?>
								<div class="fusion-clearfix"></div>
								<div class="fusion-separator sep-double"></div>
							<?php endif; ?>

						</div><!-- end portfolio-content -->

					<?php endif; ?>

				<?php
				/**
				 * Close fusion-portfolio-content-wrapper for text layouts.
				 */
				?>
				<?php if ( strpos( $current_page_template, 'text' ) ) : ?>
					</div>
				<?php endif; ?>

			</div><!-- end portfolio-post -->

		<?php endwhile; ?>

	</div><!-- end portfolio-wrapper -->

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

	<?php
	/**
	 * Render the pagination
	 */
	fusion_pagination( $portfolio_posts_to_display->max_num_pages, 2, $portfolio_posts_to_display );
	?>

	<?php wp_reset_query(); ?>

<?php endif; // password check

// Omit closing PHP tag to avoid "Headers already sent" issues.
