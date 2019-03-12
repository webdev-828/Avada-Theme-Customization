<?php
/**
 * Helper function.
 * Merge and combine the CSS elements
 */
function avada_implode( $elements = array() ) {

	if ( ! is_array( $elements ) ) {
		return $elements;
	}

	// Make sure our values are unique
	$elements = array_unique( $elements );
	// Sort elements alphabetically.
	// This way all duplicate items will be merged in the final CSS array.
	sort( $elements );

	// Implode items and return the value.
	return implode( ',', $elements );

}

/**
 * Maps elements from dynamic css to the selector
 */
function avada_map_selector( $elements, $selector ) {
	$array = array();

	foreach( $elements as $element ) {
		$array[] = $element . $selector;
	}

	return $array;
}

/**
 * Get the array of dynamically-generated CSS and convert it to a string.
 * Parses the array and adds quotation marks to font families and prefixes for browser-support.
 */
function avada_dynamic_css_parser( $css ) {
	/**
	 * Prefixes
	 */
	foreach ( $css as $media_query => $elements ) {
		foreach ( $elements as $element => $style_array ) {
			foreach ( $style_array as $property => $value ) {
				// font family
				if ( 'font-family' == $property ) {
					$css[$media_query][$element]['font-family'] = "'" . $value . "'";
				} 
				// border-radius
				elseif ( 'border-radius' == $property ) {
					$css[$media_query][$element]['-webkit-border-radius'] = $value;
				}
				// box-shadow
				elseif ( 'box-shadow' == $property ) {
					$css[$media_query][$element]['-webkit-box-shadow'] = $value;
					$css[$media_query][$element]['-moz-box-shadow']    = $value;
				}
				// box-sizing
				elseif ( 'box-sizing' == $property ) {
					$css[$media_query][$element]['-webkit-box-sizing'] = $value;
					$css[$media_query][$element]['-moz-box-sizing']    = $value;
				}
				// text-shadow
				elseif ( 'text-shadow' == $property ) {
					$css[$media_query][$element]['-webkit-text-shadow'] = $value;
					$css[$media_query][$element]['-moz-text-shadow']    = $value;
				}
				// transform
				elseif ( 'transform' == $property ) {
					$css[$media_query][$element]['-webkit-transform'] = $value;
					$css[$media_query][$element]['-moz-transform']    = $value;
					$css[$media_query][$element]['-ms-transform']     = $value;
					$css[$media_query][$element]['-o-transform']      = $value;
				}
				// background-size
				elseif ( 'background-size' == $property ) {
					$css[$media_query][$element]['-webkit-background-size'] = $value;
					$css[$media_query][$element]['-moz-background-size']    = $value;
					$css[$media_query][$element]['-ms-background-size']     = $value;
					$css[$media_query][$element]['-o-background-size']      = $value;
				}
				// transition
				elseif ( 'transition' == $property ) {
					$css[$media_query][$element]['-webkit-transition'] = $value;
					$css[$media_query][$element]['-moz-transition']    = $value;
					$css[$media_query][$element]['-ms-transition']     = $value;
					$css[$media_query][$element]['-o-transition']      = $value;
				}
				// transition-property
				elseif ( 'transition-property' == $property ) {
					$css[$media_query][$element]['-webkit-transition-property'] = $value;
					$css[$media_query][$element]['-moz-transition-property']    = $value;
					$css[$media_query][$element]['-ms-transition-property']     = $value;
					$css[$media_query][$element]['-o-transition-property']      = $value;
				}
				// linear-gradient
				elseif ( is_array( $value ) ) {
					foreach ( $value as $subvalue ) {
						if ( false !== strpos( $subvalue, 'linear-gradient' ) ) {
							$css[$media_query][$element][$property][] = '-webkit-' . $subvalue;
							$css[$media_query][$element][$property][] = '-moz-' . $subvalue;
							$css[$media_query][$element][$property][] = '-ms-' . $subvalue;
							$css[$media_query][$element][$property][] = '-o-' . $subvalue;
						}
						// calc
						elseif ( 0 === stripos( $subvalue, 'calc' ) ) {
							$css[$media_query][$element][$property][] = '-webkit-' . $subvalue;
							$css[$media_query][$element][$property][] = '-moz-' . $subvalue;
							$css[$media_query][$element][$property][] = '-ms-' . $subvalue;
							$css[$media_query][$element][$property][] = '-o-' . $subvalue;
						}
					}
				}
			}
		}
	}

	/**
	 * Process the array of CSS properties and produce the final CSS
	 */
	$final_css = '';
	foreach ( $css as $media_query => $styles ) {

		$final_css .= ( 'global' != $media_query ) ? $media_query . '{' : '';

		foreach ( $styles as $style => $style_array ) {
			$final_css .= $style . '{';
				foreach ( $style_array as $property => $value ) {
					if ( is_array( $value ) ) {
						foreach ( $value as $sub_value ) {
							$final_css .= $property . ':' . $sub_value . ';';
						}
					} else {
						$final_css .= $property . ':' . $value . ';';
					}
				}
			$final_css .= '}';
		}

		$final_css .= ( 'global' != $media_query ) ? '}' : '';

	}

	return apply_filters( 'avada_dynamic_css', $final_css );

}

/**
 * Returns the dynamic CSS.
 * If possible, it also caches the CSS using WordPress transients
 *
 * @return  string  the dynamically-generated CSS.
 */
function avada_dynamic_css_cached() {
	/**
	 * Get the page ID
	 */
	$c_pageID = Avada()->dynamic_css->page_id();

	/**
	 * do we have WP_DEBUG set to true?
	 * If yes, then do not cache.
	 */
	$cache = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? false : true;
	/**
	 * If the dynamic_css_db_caching option is not set
	 * or set to off, then do not cache.
	 */
	$cache = ( $cache && ( null == Avada()->settings->get( 'dynamic_css_db_caching' ) || ! Avada()->settings->get( 'dynamic_css_db_caching' ) ) ) ? false : $cache;
	/**
	 * If we're compiling to file, then do not use transients for caching.
	 */
	/**
	 * Check if we're using file mode or inline mode.
	 * This simply checks the dynamic_css_compiler options.
	 */
	$mode = Avada_Dynamic_CSS::$mode;

	/**
	 * ALWAYS use 'inline' mode when in the customizer.
	 */
	global $wp_customize;
	if ( $wp_customize ) {
		$mode = 'inline';
	}

	$cache = ( $cache && 'file' == $mode ) ? false : $cache;

	if ( $cache ) {
		/**
		 * Build the transient name
		 */
		$transient_name = ( $c_pageID ) ? 'avada_dynamic_css_' . $c_pageID : 'avada_dynamic_css_global';

		/**
		 * Check if the dynamic CSS needs updating
		 * If it does, then calculate the CSS and then update the transient.
		 */
		if ( Avada_Dynamic_CSS::needs_update() ) {
			/**
			 * Calculate the dynamic CSS
			 */
			$dynamic_css = avada_dynamic_css_parser( avada_dynamic_css_array() );
			/**
			 * Append the user-entered dynamic CSS
			 */
			$dynamic_css .= wp_strip_all_tags( Avada()->settings->get( 'custom_css' ) );
			/**
			 * Set the transient for an hour
			 */
			set_transient( $transient_name, $dynamic_css, 60 * 60 );
		} else {
			/**
			 * Check if the transient exists.
			 * If it does not exist, then generate the CSS and update the transient.
			 */
			if ( false === ( $dynamic_css = get_transient( $transient_name ) ) ) {
				/**
				 * Calculate the dynamic CSS
				 */
				$dynamic_css = avada_dynamic_css_parser( avada_dynamic_css_array() );
				/**
				 * Append the user-entered dynamic CSS
				 */
				$dynamic_css .= wp_strip_all_tags( Avada()->settings->get( 'custom_css' ) );
				/**
				 * Set the transient for an hour
				 */
				set_transient( $transient_name, $dynamic_css, 60 * 60 );
			}
		}

	} else {
		/**
		 * Calculate the dynamic CSS
		 */
		$dynamic_css = avada_dynamic_css_parser( avada_dynamic_css_array() );
		/**
		 * Append the user-entered dynamic CSS
		 */
		$dynamic_css .= wp_strip_all_tags( Avada()->settings->get( 'custom_css' ) );
	}

	return $dynamic_css;

}

/**
 * Takes care of adding custom fonts using @font-face
 */
function avada_custom_fonts_font_faces( $css = '' ) {
	// Get the options
	$options   = get_option( Avada::get_option_name(), array() );
	$font_face = '';
	// Make sure 'custom_fonts' are defined
	if ( isset( $options['custom_fonts'] ) ) {
		$custom_fonts = $options['custom_fonts'];
		// Make sure we have titles for our fonts
		if ( isset( $custom_fonts['name'] ) && is_array( $custom_fonts['name'] ) ) {
			foreach ( $custom_fonts['name'] as $key => $label ) {
				$label = trim( $label );
				// Make sure we have some files to work with
				if (
					( isset( $custom_fonts['woff'] ) && isset( $custom_fonts['woff'][ $key ] ) ) ||
					( isset( $custom_fonts['woff2'] ) && isset( $custom_fonts['woff2'][ $key ] ) ) ||
					( isset( $custom_fonts['ttf'] ) && isset( $custom_fonts['ttf'][ $key ] ) ) ||
					( isset( $custom_fonts['svg'] ) && isset( $custom_fonts['svg'][ $key ] ) ) ||
					( isset( $custom_fonts['eot'] ) && isset( $custom_fonts['eot'][ $key ] ) )
				) {
					$firstfile = true;
					$font_face .= '@font-face{';
						$font_face .= 'font-family:';
						// If font-name has a space, then it must be wrapped in double-quotes
						if ( false !== strpos( $label, ' ' ) ) {
							$font_face .= '"' . $label . '";';
						} else {
							$font_face .= $label . ';';
						}
						// Start adding sources
						$font_face .= 'src:';
						// Add .eot file
						if ( isset( $custom_fonts['eot'] ) && isset( $custom_fonts['eot'][ $key ] ) && $custom_fonts['eot'][ $key ]['url'] ) {
							$font_face .= 'url("' . str_replace( array( 'http://', 'https://' ), '//', $custom_fonts['eot'][ $key ]['url'] ) . '?#iefix") format("embedded-opentype")';
							$firstfile = false;
						}
						// Add .woff file
						if ( isset( $custom_fonts['woff'] ) && isset( $custom_fonts['woff'][ $key ] ) && $custom_fonts['woff'][ $key ]['url'] ) {
							$font_face .= ( $firstfile ) ? '' : ',';
							$font_face .= 'url("' . str_replace( array( 'http://', 'https://' ), '//', $custom_fonts['woff'][ $key ]['url'] ) . '") format("woff")';
							$firstfile = false;
						}
						// Add .woff2 file
						if ( isset( $custom_fonts['woff2'] ) && isset( $custom_fonts['woff2'][ $key ]['url'] ) && $custom_fonts['woff2'][ $key ]['url'] ) {
							$font_face .= ( $firstfile ) ? '' : ',';
							$font_face .= 'url("' . str_replace( array( 'http://', 'https://' ), '//', $custom_fonts['woff2'][ $key ]['url'] ) . '") format("woff2")';
							$firstfile = false;
						}
						// Add .ttf file
						if ( isset( $custom_fonts['ttf'] ) && isset( $custom_fonts['ttf'][ $key ] ) && $custom_fonts['ttf'][ $key ]['url'] ) {
							$font_face .= ( $firstfile ) ? '' : ',';
							$font_face .= 'url("' . str_replace( array( 'http://', 'https://' ), '//', $custom_fonts['ttf'][ $key ]['url'] ) . '") format("truetype")';
							$firstfile = false;
						}
						// Add .svg file
						if ( isset( $custom_fonts['svg'] ) && isset( $custom_fonts['svg'][ $key ] ) && $custom_fonts['svg'][ $key ]['url'] ) {
							$font_face .= ( $firstfile ) ? '' : ',';
							$font_face .= 'url("' . str_replace( array( 'http://', 'https://' ), '//', $custom_fonts['svg'][ $key ]['url'] ) . '") format("svg")';
							$firstfile = false;
						}
						$font_face .= ';font-weight: normal;font-style: normal;';
					$font_face .= '}';
				}
			}
		}
	}
	return $font_face . $css;
}
add_filter( 'avada_dynamic_css', 'avada_custom_fonts_font_faces' );


/**
 * Avada body, h1, h2, h3, h4, h5, h6 typography
 */

// CSS classes that inherit Avada's body typography settings
function avada_get_body_typography_elements() {
	$typography_elements = array();

	// css classes that inherit body font size
	$typography_elements['size'] = array(
		'body',
		'.sidebar .slide-excerpt h2',
		'.fusion-footer-widget-area .slide-excerpt h2',
		'#slidingbar-area .slide-excerpt h2',
		'.jtwt .jtwt_tweet',
		'.sidebar .jtwt .jtwt_tweet',
		'.project-content .project-info h4',
		'.gform_wrapper label',
		'.gform_wrapper .gfield_description',
		'.fusion-footer-widget-area ul',
		'#slidingbar-area ul',
		'.fusion-tabs-widget .tab-holder .news-list li .post-holder a',
		'.fusion-tabs-widget .tab-holder .news-list li .post-holder .meta',
		'.fusion-blog-layout-timeline .fusion-timeline-date',
		'.post-content blockquote',
		'.review blockquote q',

	);
	// css classes that inherit body font color
	$typography_elements['color'] = array(
		'body',
		'.post .post-content',
		'.post-content blockquote',
		'#wrapper .fusion-tabs-widget .tab-holder .news-list li .post-holder .meta',
		'.sidebar .jtwt',
		'#wrapper .meta',
		'.review blockquote div',
		'.search input',
		'.project-content .project-info h4',
		'.title-row',
		'.fusion-rollover .price .amount',
		'.fusion-blog-timeline-layout .fusion-timeline-date',
		'#reviews #comments > h2',
		'.sidebar .widget_nav_menu li',
		'.sidebar .widget_categories li',
		'.sidebar .widget_product_categories li',
		'.sidebar .widget_meta li',
		'.sidebar .widget .recentcomments',
		'.sidebar .widget_recent_entries li',
		'.sidebar .widget_archive li',
		'.sidebar .widget_pages li',
		'.sidebar .widget_links li',
		'.sidebar .widget_layered_nav li',
		'.sidebar .widget_product_categories li',
		'body .sidebar .fusion-tabs-widget .tab-holder .tabs li a',
		'.sidebar .fusion-tabs-widget .tab-holder .tabs li a',
		'.fusion-main-menu .fusion-custom-menu-item-contents',

	);
	// css classes that inherit body font
	$typography_elements['family'] = array(
		'body',
		'#nav ul li ul li a',
		'#sticky-nav ul li ul li a',
		'.more',
		'.avada-container h3',
		'.meta .fusion-date',
		'.review blockquote q',
		'.review blockquote div strong',
		'.post-content blockquote',
		'.fusion-load-more-button',
		'.ei-title h3',
		'.comment-form input[type="submit"]',
		'.fusion-page-title-bar h3',
		'#reviews #comments > h2',
		'.fusion-image-wrapper .fusion-rollover .fusion-rollover-content .fusion-rollover-title',
		'.fusion-image-wrapper .fusion-rollover .fusion-rollover-content .fusion-rollover-categories',
		'.fusion-image-wrapper .fusion-rollover .fusion-rollover-content a',
		'.fusion-image-wrapper .fusion-rollover .fusion-rollover-content .price',
		'#wrapper #nav ul li ul li > a',
		'#wrapper #sticky-nav ul li ul li > a',
		'.ticket-selector-submit-btn[type=submit]',
		'.gform_page_footer input[type=button]',
		'.fusion-main-menu .sub-menu',
		'.fusion-main-menu .sub-menu li a',
		'.fusion-megamenu-wrapper .fusion-megamenu-submenu > a:hover',
		'.fusion-megamenu-widgets-container',
	);

	// css classes that inherit body font
	$typography_elements['line-height'] = array(
		'body',
		'#nav ul li ul li a',
		'#sticky-nav ul li ul li a',
		'.more',
		'.avada-container h3',
		'.meta .fusion-date',
		'.review blockquote q',
		'.review blockquote div strong',
		'.post-content blockquote',
		'.ei-title h3',
		'.comment-form input[type="submit"]',
		'.fusion-page-title-bar h3',
		'#reviews #comments > h2',
		'.fusion-image-wrapper .fusion-rollover .fusion-rollover-content .fusion-rollover-title',
		'.fusion-image-wrapper .fusion-rollover .fusion-rollover-content .fusion-rollover-categories',
		'.fusion-image-wrapper .fusion-rollover .fusion-rollover-content a',
		'.fusion-image-wrapper .fusion-rollover .fusion-rollover-content .price',
		'#wrapper #nav ul li ul li > a',
		'#wrapper #sticky-nav ul li ul li > a',
		'.ticket-selector-submit-btn[type=submit]',
		'.gform_page_footer input[type=button]',
		'.fusion-main-menu .sub-menu',
		'.fusion-main-menu .sub-menu li a',
		'.fusion-megamenu-wrapper .fusion-megamenu-submenu > a:hover',
		'.fusion-megamenu-widgets-container',
		'.fusion-accordian .panel-body',
		'#side-header .fusion-contact-info',
		'#side-header .header-social .top-menu',

	);

	return $typography_elements;
}

// CSS classes that inherit Avada's H1 typography settings
function avada_get_h1_typography_elements() {
	$typography_elements = array();

	// css classes that inherit h1 size
	$typography_elements['size'] = array(
		//'h1',
		'.post-content h1',
		'.fusion-modal h1',
		'.fusion-widget-area h1',
	);
	// css classes that inherit h1 font family
	$typography_elements['family'] = array(
		//'h1',
		'.post-content h1',
		'.fusion-page-title-bar h1',
		'.fusion-modal h1',
		'.fusion-widget-area h1',
	);
	// css classes that inherit h1 color
	$typography_elements['color'] = array(
		//'h1',
		'.post-content h1',
		'.title h1',
		'.fusion-post-content h1',
		'.fusion-modal h1',
		'.fusion-widget-area h1',
	);

	return $typography_elements;
}

// CSS classes that inherit Avada's H2 typography settings
function avada_get_h2_typography_elements() {
	$typography_elements = array();

	// css classes that inherit h2 size
	$typography_elements['size'] = array(
		//'h2',
		'#wrapper .post-content h2',
		'#wrapper .fusion-title h2',
		'#wrapper #main .post-content .fusion-title h2',
		'#wrapper .title h2',
		'#wrapper #main .post-content .title h2',
		'#main .post h2',
		'#wrapper  #main .post h2',
		'#main .fusion-portfolio h2',
		'h2.entry-title',
		'.fusion-modal h2',
		'.fusion-widget-area h2',
	);
	// css classes that inherit h2 color
	$typography_elements['color'] = array(
		//'h2',
		'#main .post h2',
		'.post-content h2',
		'.fusion-title h2',
		'.title h2',
		'.search-page-search-form h2',
		'.fusion-post-content h2',
		'.fusion-modal h2',
		'.fusion-widget-area h2',
	);
	// css classes that inherit h2 font family
	$typography_elements['family'] = array(
		//'h2',
		'#main .post h2',
		'.post-content h2',
		'.fusion-title h2',
		'.title h2',
		'#main .reading-box h2',
		'#main h2',
		'.ei-title h2',
		'.main-flex .slide-content h2',
		'.fusion-modal h2',
		'.fusion-widget-area h2',
	);

	return $typography_elements;
}

// CSS classes that inherit Avada's H3 typography settings
function avada_get_h3_typography_elements() {
	$typography_elements = array();

	// css classes that inherit h3 font family
	$typography_elements['family'] = array(
		//'h3',
		'.post-content h3',
		'.project-content h3',
		'.sidebar .widget h3',
		'.main-flex .slide-content h3',
		'.fusion-author .fusion-author-title',
		'.fusion-header-tagline',
		'.fusion-modal h3',
		'.fusion-title h3',
		'.fusion-widget-area h3',
	);
	// css classes that inherit h3 size
	$typography_elements['size'] = array(
		//'h3',
		'.post-content h3',
		'.project-content h3',
		'.fusion-modal h3',
		'.fusion-widget-area h3',
	);

	// css classes that inherit h3 color
	$typography_elements['color'] = array(
		//'h3',
		'.post-content h3',
		'.sidebar .widget h3',
		'.project-content h3',
		'.fusion-title h3',
		'.title h3',
		'.fusion-post-content h3',
		'.fusion-modal h3',
		'.fusion-widget-area h3',
	);

	return $typography_elements;
}

// CSS classes that inherit Avada's H4 typography settings
function avada_get_h4_typography_elements() {
	$typography_elements = array();

	// css classes that inherit h4 size
	$typography_elements['size'] = array(
		//'h4',
		'.post-content h4',
		'.fusion-portfolio-post .fusion-portfolio-content h4',
		'.fusion-rollover .fusion-rollover-content .fusion-rollover-title',
		'.fusion-carousel-title',
		'#wrapper .fusion-tabs-widget .tab-holder .tabs li a',
		'#reviews #comments > h2',
		'.fusion-accordian .panel-title',
		'.fusion-sharing-box h4',
		'.fusion-tabs .nav-tabs > li .fusion-tab-heading',
		'.fusion-modal h4',
		'.fusion-widget-area h4',
	);
	// css classes that inherit h4 color
	$typography_elements['color'] = array(
		//'h4',
		'.post-content h4',
		'.project-content .project-info h4',
		'.share-box h4',
		'.fusion-title h4',
		'.title h4',
		'#wrapper .fusion-tabs-widget .tab-holder .tabs li a',
		'.fusion-accordian .panel-title a',
		'.fusion-carousel-title',
		'.fusion-tabs .nav-tabs > li .fusion-tab-heading',
		'.fusion-post-content h4',
		'.fusion-modal h4',
		'.fusion-widget-area h4',
	);
	// css classes that inherit h4 font family
	$typography_elements['family'] = array(
		//'h4',
		'.post-content h4',
		'table th',
		'.fusion-megamenu-title',
		'.fusion-accordian .panel-title',
		'.fusion-carousel-title',
		'#wrapper .fusion-tabs-widget .tab-holder .tabs li a',
		'.share-box h4',
		'.project-content .project-info h4',
		'.fusion-image-wrapper .fusion-rollover .fusion-rollover-content .fusion-rollover-title',
		'.fusion-image-wrapper .fusion-rollover .fusion-rollover-content .fusion-rollover-title a',
		'.fusion-modal h4',
		'.fusion-content-widget-area h4',
	);

	return $typography_elements;
}

// CSS classes that inherit Avada's H5 typography settings
function avada_get_h5_typography_elements() {
	$typography_elements = array();

	// css classes that inherit h5 size
	$typography_elements['size'] = array(
		//'h5',
		'.post-content h5',
		'.fusion-modal h5',
		'.fusion-widget-area h5',
	);
	// css classes that inherit h5 color
	$typography_elements['color'] = array(
		//'h5',
		'.post-content h5',
		'.fusion-title h5',
		'.title h5',
		'.fusion-post-content h5',
		'.fusion-modal h5',
		'.fusion-widget-area h5',
	);
	// css classes that inherit h5 font family
	$typography_elements['family'] = array(
		//'h5',
		'.post-content h5',
		'.fusion-modal h5',
		'.fusion-widget-area h5',
	);

	return $typography_elements;
}

// CSS classes that inherit Avada's H6 typography settings
function avada_get_h6_typography_elements() {
	$typography_elements = array();

	// css classes that inherit h6 size
	$typography_elements['size'] = array(
		//'h6',
		'.post-content h6',
		'.fusion-modal h6',
		'.fusion-widget-area h6',
	);
	// css classes that inherit h6 color
	$typography_elements['color'] = array(
		//'h6',
		'.post-content h6',
		'.fusion-title h6',
		'.title h6',
		'.fusion-post-content h6',
		'.fusion-modal h6',
		'.fusion-widget-area h6',
	);
	// css classes that inherit h6 font family
	$typography_elements['family'] = array(
		//'h6',
		'.post-content h6',
		'.fusion-modal h6',
		'.fusion-widget-area h6',
	);

	return $typography_elements;
}

// CSS classes that inherit Avada's button typography settings
function avada_get_button_typography_elements() {
	$typography_elements = array();

	// css classes that inherit h3 font family
	$typography_elements['family'] = array(
		'.fusion-button',
		'.fusion-load-more-button',
		'.comment-form input[type="submit"]',
		'.ticket-selector-submit-btn[type="submit"]',
	);

	return $typography_elements;
}
