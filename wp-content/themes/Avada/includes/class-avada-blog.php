<?php

class Avada_Blog {

	public function __construct() {

		add_filter( 'excerpt_length', array( $this, 'excerpt_length' ), 999 );
		add_action( 'pre_get_posts', array( $this, 'alter_search_loop' ), 1 );

		if ( ! is_admin() ) {
			add_filter( 'pre_get_posts', array( $this, 'search_filter' ) );
			add_filter( 'pre_get_posts', array( $this, 'empty_search_filter' ) );
		}

	}

	/**
	 * Modify the default excerpt length
	 */
	public function excerpt_length( $length ) {

		// Normal blog posts excerpt length
		if ( ! is_null( Avada()->settings->get( 'excerpt_length_blog' ) ) ) {
			$length = Avada()->settings->get( 'excerpt_length_blog' );
		}

		// Search results excerpt length
		if ( is_search() ) {
			$length = Avada()->settings->get( 'excerpt_length_blog' );
		}

		return $length;

	}

	/**
	 * Apply post per page on search pages
	 */
	public function alter_search_loop( $query ) {
		if ( ! is_admin() && $query->is_main_query() && $query->is_search() && Avada()->settings->get( 'search_results_per_page' ) ) {
			$query->set( 'posts_per_page', Avada()->settings->get( 'search_results_per_page' ) );
		}
	}

	/**
	 * Apply filters to the search query.
	 * Determines if we only want to display posts/pages and changes the query accordingly
	 */
	public function search_filter( $query ) {

		if ( is_search() && $query->is_search ) {

			// Show only posts in search results
			if ( 'Only Posts' == Avada()->settings->get( 'search_content' ) ) {
				$query->set('post_type', 'post');
			}
			// Show only pages in search results
			elseif ( 'Only Pages' == Avada()->settings->get( 'search_content' ) ) {
				$query->set( 'post_type', 'page' );
			}

		}

		return $query;

	}

	/**
	 * make wordpress respect the search template on an empty search
	 */
	public function empty_search_filter( $query ) {

		if ( isset( $_GET['s'] ) && empty( $_GET['s'] ) && $query->is_main_query() ) {
			$query->is_search = true;
			$query->is_home   = false;
		}

		return $query;

	}

	/**
	 * get the content of the post
	 * strip it and apply any changes required to the excerpt first.
	 */
	public function get_content_stripped_and_excerpted( $excerpt_length, $content ) {
		$pattern = get_shortcode_regex();
		$content = preg_replace_callback( "/$pattern/s", 'avada_extract_shortcode_contents', $content );
		$content = explode( ' ', $content, $excerpt_length + 1 );

		if ( $excerpt_length < count( $content ) ) {
			array_pop( $content );
		}

		$content = implode( ' ',$content );
		$content = preg_replace( '~(?:\[/?)[^/\]]+/?\]~s', '', $content ); // strip shortcodes and keep the content
		$content = str_replace( ']]>', ']]&gt;', $content );
		$content = strip_tags( $content );
		$content = str_replace( array( '"', "'" ), array( '&quot;', '&#39;' ), $content );
		$content = trim( $content );

		return $content;

	}

	/**
	 * Retrieve the content and apply and read-more modifications needed.
	 */
	public function content( $limit, $strip_html ) {

		global $more;

		$content = '';

		// Sanitizing the limit value
		$limit = ( ! $limit && $limit != 0 ) ? 285 : intval( $limit );

		$test_strip_html = ( $strip_html == "true" || $strip_html == true ) ? true : false;

		$custom_excerpt = false;

		$post = get_post( get_the_ID() );

		$pos = strpos( $post->post_content, '<!--more-->' );

		$readmore = ( Avada()->settings->get( 'link_read_more' ) ) ? ' <a href="' . get_permalink( get_the_ID() ) . '">&#91;...&#93;</a>' : ' &#91;...&#93;';
		$readmore = ( ! Avada()->settings->get( 'disable_excerpts' ) ) ? '' : $readmore;

		if ( $test_strip_html ) {

			$more = 0;
			$raw_content = wp_strip_all_tags( get_the_content( '{{read_more_placeholder}}' ), '<p>' );

			// Strip out all attributes
			$raw_content = preg_replace('/<(\w+)[^>]*>/', '<$1>', $raw_content);

			$raw_content = str_replace( '{{read_more_placeholder}}', $readmore, $raw_content );

			if ( $post->post_excerpt || false !== $pos ) {
				$raw_content    = ( ! $pos ) ? wp_strip_all_tags( rtrim( get_the_excerpt(), '[&hellip;]' ), '<p>' ) . $readmore : $raw_content;
				$custom_excerpt = true;
			}

		} else {

			$more = 0;
			$raw_content = get_the_content( $readmore );
			if ( $post->post_excerpt || false !== $pos ) {
				$raw_content    = ( ! $pos ) ? rtrim( get_the_excerpt(), '[&hellip;]' ) . $readmore : $raw_content;
				$custom_excerpt = true;
			}

		}

		if ( $raw_content && ! $custom_excerpt ) {

			$pattern = get_shortcode_regex();
			$content = preg_replace_callback( "/$pattern/s", 'avada_extract_shortcode_contents', $raw_content );

			if ( 'Characters' == Avada()->settings->get( 'excerpt_base' ) ) {

				$content  = mb_substr( $content, 0, $limit );
				$content .= ( $limit != 0 && Avada()->settings->get( 'disable_excerpts' ) ) ? $readmore : '';

			} else {

				$content = explode( ' ', $content, $limit + 1 );

				if ( $limit < count( $content ) ) {

					array_pop( $content );
					$content = implode( ' ',$content );
					if ( Avada()->settings->get( 'disable_excerpts' ) ) {
						$content .= ( $limit != 0 ) ? $readmore : '';
					}

				} else {

					$content = implode( ' ',$content );

				}

			}

			if ( $limit != 0 && ! $test_strip_html ) {

				$content = apply_filters( 'the_content', $content );
				$content = str_replace( ']]>', ']]&gt;', $content );

			} else {
				$content = sprintf( '<p>%s</p>', $content );
			}

			$strip_html_class = ( $test_strip_html ) ? 'strip-html' : '';
			$content = sprintf( '<div class="excerpt-container %s">%s</div>', $strip_html_class, do_shortcode( $content ) );

			return $content;

		}

		if ( true == $custom_excerpt ) {

			$pattern = get_shortcode_regex();
			$content = preg_replace_callback( "/$pattern/s", 'avada_extract_shortcode_contents', $raw_content );

			if ( true == $test_strip_html ) {

				$content = apply_filters( 'the_content', $content );
				$content = str_replace( ']]>', ']]&gt;', $content );
				$content = '<div class="excerpt-container strip-html">' . do_shortcode( $content ) . '</div>';

			} else {

				$content = apply_filters( 'the_content', $content );
				$content = str_replace( ']]>', ']]&gt;', $content );

			}

		}

		if ( has_excerpt() ) {

			$content = do_shortcode( get_the_excerpt() );
			$content = '<p>' . $content . '</p>';

		}

		return $content;

	}

}

// Omit closing PHP tag to avoid "Headers already sent" issues.
