<?php
/**
 * Render the blog layouts
 *
 * @author 		ThemeFusion
 * @package 	Avada/Templates
 * @version     1.0
 */

// Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) exit( 'Direct script access denied.' );

global $wp_query;

// Set the correct post container layout classes
$blog_layout = 	avada_get_blog_layout();
$post_class = sprintf( 'fusion-post-%s', $blog_layout );
if ( $blog_layout == 'grid' ) {
	$container_class = sprintf( 'fusion-blog-layout-%s fusion-blog-layout-%s-%s isotope ', $blog_layout, $blog_layout, Avada()->settings->get( 'blog_grid_columns' ) );
} else {
	$container_class = sprintf( 'fusion-blog-layout-%s ', $blog_layout );
}

// Set class for scrolling type
if ( Avada()->settings->get( 'blog_pagination_type' ) == 'Infinite Scroll' ||
	 Avada()->settings->get( 'blog_pagination_type' ) == 'load_more_button'
) {
	$container_class .= 'fusion-blog-infinite fusion-posts-container-infinite ';
} else {
	$container_class .= 'fusion-blog-pagination ';
}

if ( ! Avada()->settings->get( 'featured_images' ) ) {
	$container_class .= 'fusion-blog-no-images ';
}

// Add the timeline icon
if ( $blog_layout == 'timeline' ) {
	echo '<div class="fusion-timeline-icon"><i class="fusion-icon-bubbles"></i></div>';
}

if ( is_search() &&
	 Avada()->settings->get( 'search_results_per_page' )
) {
	$number_of_pages = ceil( $wp_query->found_posts / Avada()->settings->get( 'search_results_per_page' ) );
} else {
	$number_of_pages = $wp_query->max_num_pages;
}

printf( '<div id="posts-container" class="%sfusion-blog-archive fusion-clearfix" data-pages="%s">', $container_class, $number_of_pages );

	if ( $blog_layout == 'timeline' ) {
		// Initialize the time stamps for timeline month/year check
		$post_count = 1;
		$prev_post_timestamp = null;
		$prev_post_month = null;
		$prev_post_year = null;
		$first_timeline_loop = false;

		// Add the container that holds the actual timeline line
		echo '<div class="fusion-timeline-line"></div>';
	}

	// Start the main loop
	while ( have_posts() ): the_post();
		// Set the time stamps for timeline month/year check
		$alignment_class = '';
		if ( $blog_layout == 'timeline' ) {
			$post_timestamp = get_the_time( 'U' );
			$post_month = date( 'n', $post_timestamp );
			$post_year = get_the_date( 'Y' );
			$current_date = get_the_date( 'Y-n' );

			// Set the correct column class for every post
			if ( $post_count % 2 ) {
				$alignment_class = 'fusion-left-column';
			} else {
				$alignment_class = 'fusion-right-column';
			}

			// Set the timeline month label
			if ( $prev_post_month != $post_month ||
				 $prev_post_year != $post_year
			) {

				if ( $post_count > 1 ) {
					echo '</div>';
				}
				printf( '<h3 class="fusion-timeline-date">%s</h3>', get_the_date( Avada()->settings->get( 'timeline_date_format' ) ) );
				echo '<div class="fusion-collapse-month">';
			}
		}

		// Set the has-post-thumbnail if a video is used. This is needed if no featured image is present.
		$thumb_class = '';
		if ( get_post_meta( get_the_ID(), 'pyre_video', true ) ) {
			$thumb_class = ' has-post-thumbnail';
		}

		$post_classes = sprintf( '%s %s %s post fusion-clearfix', $post_class, $alignment_class, $thumb_class );
		ob_start();
		post_class( $post_classes );
		$post_classes = ob_get_clean();

		printf( '<div id="post-%s" %s>', get_the_ID(), $post_classes );
			// Add an additional wrapper for grid layout border
			if ( $blog_layout == 'grid' ) {
				echo '<div class="fusion-post-wrapper">';
			}

				// Get featured images for all but large-alternate layout
				if ( ( ( is_search() && Avada()->settings->get( 'search_featured_images' ) ) || ( ! is_search() && Avada()->settings->get( 'featured_images' ) ) ) &&
					 $blog_layout == 'large-alternate'
				) {
					get_template_part( 'new-slideshow' );
				}

				// Get the post date and format box for alternate layouts
				if ( $blog_layout == 'large-alternate' ||
					 $blog_layout == 'medium-alternate'
				) {
					echo '<div class="fusion-date-and-formats">';

						/**
						 * avada_blog_post_date_adn_format hook
						 *
						 * @hooked avada_render_blog_post_date - 10 (outputs the HTML for the date box)
						 * @hooked avada_render_blog_post_format - 15 (outputs the HTML for the post format box)
						 */
						do_action( 'avada_blog_post_date_and_format' );

					echo '</div>';
				}

				// Get featured images for all but large-alternate layout
				if ( ( ( is_search() && Avada()->settings->get( 'search_featured_images' ) ) || ( ! is_search() && Avada()->settings->get( 'featured_images' ) ) ) &&
					 $blog_layout != 'large-alternate'
				) {
					get_template_part( 'new-slideshow' );
				}

				// post-content-wrapper only needed for grid and timeline
				if ( $blog_layout == 'grid' ||
					 $blog_layout == 'timeline'
				) {
					echo '<div class="fusion-post-content-wrapper">';
				}

					// Add the circles for timeline layout
					if ( $blog_layout == 'timeline' ) {
						echo '<div class="fusion-timeline-circle"></div>';
						echo '<div class="fusion-timeline-arrow"></div>';
					}

					echo '<div class="fusion-post-content post-content">';

						// Render the post title
						echo avada_render_post_title( get_the_ID() );

						// Render post meta for grid and timeline layouts
						if ( $blog_layout == 'grid' ||
							 $blog_layout == 'timeline'
						) {
							echo avada_render_post_metadata( 'grid_timeline' );

							if ( ( Avada()->settings->get( 'post_meta' ) && ( Avada()->settings->get( 'post_meta_author' ) || Avada()->settings->get( 'post_meta_date' ) || Avada()->settings->get( 'post_meta_cats' ) || Avada()->settings->get( 'post_meta_tags' ) || Avada()->settings->get( 'post_meta_comments' ) || Avada()->settings->get( 'post_meta_read' ) ) ) &&
								 Avada()->settings->get( 'excerpt_length_blog' ) > 0
							) {
								echo '<div class="fusion-content-sep"></div>';
							}
						// Render post meta for alternate layouts
						} elseif ( $blog_layout == 'large-alternate' ||
								  $blog_layout == 'medium-alternate'
						) {
							echo avada_render_post_metadata( 'alternate' );
						}

						echo '<div class="fusion-post-content-container">';

							/**
							 * avada_blog_post_content hook
							 *
							 * @hooked avada_render_blog_post_content - 10 (outputs the post content wrapped with a container)
							 */
							do_action( 'avada_blog_post_content' );

						echo '</div>';

					echo '</div>'; // end post-content

					if ( $blog_layout == 'medium' ||
						$blog_layout == 'medium-alternate'
					) {
						echo '<div class="fusion-clearfix"></div>';
					}

					// Render post meta data according to layout
					if ( ( Avada()->settings->get( 'post_meta' ) && ( Avada()->settings->get( 'post_meta_author' ) || Avada()->settings->get( 'post_meta_date' ) || Avada()->settings->get( 'post_meta_cats' ) || Avada()->settings->get( 'post_meta_tags' ) || Avada()->settings->get( 'post_meta_comments' ) || Avada()->settings->get( 'post_meta_read' ) ) ) ) {
						echo '<div class="fusion-meta-info">';
							if ( $blog_layout == 'grid' ||
								 $blog_layout == 'timeline'
							) {
								// Render read more for grid/timeline layouts
								echo '<div class="fusion-alignleft">';
									if ( Avada()->settings->get( 'post_meta_read' ) ) {
										$link_target = '';
										if ( fusion_get_page_option( 'link_icon_target', get_the_ID() ) == 'yes' ||
											fusion_get_page_option( 'post_links_target', get_the_ID() ) == 'yes' ) {
											$link_target = ' target="_blank"';
										}
										printf( '<a href="%s" class="fusion-read-more"%s>%s</a>', get_permalink(), $link_target, apply_filters( 'avada_blog_read_more_link', __( 'Read More', 'Avada' ) ) );
									}
								echo '</div>';

								// Render comments for grid/timeline layouts
								echo '<div class="fusion-alignright">';
									if ( Avada()->settings->get( 'post_meta_comments' ) ) {
										if ( ! post_password_required( get_the_ID() ) ) {
											comments_popup_link('<i class="fusion-icon-bubbles"></i>&nbsp;' . __( '0', 'Avada' ), '<i class="fusion-icon-bubbles"></i>&nbsp;' . __( '1', 'Avada' ), '<i class="fusion-icon-bubbles"></i>&nbsp;' . '%' );
										} else {
											printf( '<i class="fusion-icon-bubbles"></i>&nbsp;%s', __( 'Protected', 'Avada' ) );
										}
									}
								echo '</div>';
							} else {
								// Render all meta data for medium and large layouts
								if ( $blog_layout == 'large' || $blog_layout == 'medium' ) {
									echo avada_render_post_metadata( 'standard' );
								}

								// Render read more for medium/large and medium/large alternate layouts
								echo '<div class="fusion-alignright">';
									if ( Avada()->settings->get( 'post_meta_read' ) ) {
										$link_target = '';
										if ( fusion_get_page_option( 'link_icon_target', get_the_ID() ) == 'yes' ||
											fusion_get_page_option( 'post_links_target', get_the_ID() ) == 'yes' ) {
											$link_target = ' target="_blank"';
										}
										printf( '<a href="%s" class="fusion-read-more"%s>%s</a>', get_permalink(), $link_target, apply_filters( 'avada_read_more_name', __( 'Read More', 'Avada' ) ) );
									}
								echo '</div>';
							}
						echo '</div>'; // end meta-info
					}
				if ( $blog_layout == 'grid' ||
					 $blog_layout == 'timeline'
				) {
					echo '</div>'; // end post-content-wrapper
				}
			if ( $blog_layout == 'grid' ) {
				echo '</div>'; // end post-wrapper
			}
		echo '</div>'; // end post

		// Adjust the timestamp settings for next loop
		if ( $blog_layout == 'timeline' ) {
			$prev_post_timestamp = $post_timestamp;
			$prev_post_month = $post_month;
			$prev_post_year = $post_year;
			$post_count++;
		}
	endwhile; // end have_posts()

	if ( $blog_layout == 'timeline' &&
		 $post_count > 1
	) {
		echo '</div>';
	}
echo '</div>'; // end posts-container

// If infinite scroll with "load more" button is used
if ( Avada()->settings->get( 'blog_pagination_type' ) == 'load_more_button' ) {
	printf( '<div class="fusion-load-more-button fusion-blog-button fusion-clearfix">%s</div>', apply_filters( 'avada_load_more_posts_name', __( 'Load More Posts', 'Avada' ) ) );
}

// Get the pagination
fusion_pagination( $pages = '', $range = 2 );

wp_reset_query();

// Omit closing PHP tag to avoid "Headers already sent" issues.
