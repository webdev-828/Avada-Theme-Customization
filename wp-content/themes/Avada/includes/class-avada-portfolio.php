<?php

class Avada_Portfolio {

	/**
	 * The class constructor
	 */
	public function __construct() {
		add_filter( 'fusion_content_class', array( $this, 'set_portfolio_single_width' ) );
		add_filter( 'fusion_content_class', array( $this, 'set_portfolio_page_template_classes' ) );
		add_filter( 'pre_get_posts', array( $this, 'set_post_filters' ) );
	}

	/**
	 * Modify the query (using the 'pre_get_posts' filter)
	 */
	public function set_post_filters( $query ) {

		if ( ! is_admin() && $query->is_main_query() && ( is_tax( 'portfolio_category' ) || is_tax( 'portfolio_skills' ) || is_tax( 'portfolio_tags' ) ) ) {
			// If TO setting is set to 0, all items should show
			$number_of_portfolio_items = Avada()->settings->get( 'portfolio_items' );
			if ( '0' == $number_of_portfolio_items ) {
				$number_of_portfolio_items = -1;
			}
			
			$query->set( 'posts_per_page', $number_of_portfolio_items );
		}

		return $query;

	}

	/**
	 * Set portfolio width and assign a class to the content div
	 */
	public function set_portfolio_single_width( $classes ) {
		if ( is_singular( 'avada_portfolio') ) {
			if ( fusion_get_option( 'portfolio_featured_image_width', 'width', Avada::c_pageID() ) == 'half' ) {
				$portfolio_width = 'half';
			} else {
				$portfolio_width = 'full';
			}
			if ( ! Avada()->settings->get( 'portfolio_featured_images' ) &&
				$portfolio_width == 'half'
			) {
				$portfolio_width = 'full';
			}

			$classes[] = 'portfolio-' . $portfolio_width;
		}

		return $classes;
	}

	/**
	 * Set portfolio page template classes
	 */
	public function set_portfolio_page_template_classes( $classes ) {
		if (
			is_page_template( 'portfolio-one-column.php') ||
			is_page_template( 'portfolio-two-column.php') ||
			is_page_template( 'portfolio-three-column.php') ||
			is_page_template( 'portfolio-four-column.php') ||
			is_page_template( 'portfolio-five-column.php') ||
			is_page_template( 'portfolio-six-column.php') ||
			is_page_template( 'portfolio-one-column-text.php') ||
			is_page_template( 'portfolio-two-column-text.php') ||
			is_page_template( 'portfolio-three-column-text.php') ||
			is_page_template( 'portfolio-four-column-text.php') ||
			is_page_template( 'portfolio-five-column-text.php') ||
			is_page_template( 'portfolio-six-column-text.php') ||
			is_page_template( 'portfolio-grid.php')
		) {
			$classes[] = avada_get_portfolio_classes( Avada::c_pageID() );
		}

		return $classes;
	}

}

// Omit closing PHP tag to avoid "Headers already sent" issues.
