<?php

class Avada_Compatibility {
	public $settings;
	public function __construct() {
		$this->settings = new Avada_Compatibility_Settings();

		require_once( 'class-avada-sanitize.php' );
		require_once( 'class-avada-color.php' );
	}
}

class Avada_Compatibility_Settings {
	public function get( $setting, $subsetting = false ) {
		if ( class_exists( 'Avada' ) ) {
			if ( function_exists( 'property_exists' ) && property_exists( 'Avada', 'option_name' ) ) { // PHP >= 5.3
				$settings = get_option( Avada::$option_name );
			} elseif ( class_exists( 'ReflectionClass' ) ) { // PHP 5.2
				$reflectionClass = new ReflectionClass( 'Avada' );
				if ( $reflectionClass->hasProperty( 'option_name' ) && $reflectionClass->getProperty( 'option_name' )->isStatic() ) {
					$settings = get_option( Avada::$option_name );
				}
			}
		}
		// Fallback to using the 'avada_theme_options' or 'Avada_options' option name.
		if ( ! isset( $settings ) ) {
			$settings = get_option( 'avada_theme_options', array() );
			if ( empty( $settings ) ) {
				$settings = get_option( 'Avada_options', array() );
			}
		}

		if ( isset( $settings[ $setting ] ) ) {
			if ( $subsetting ) {
				if ( isset( $settings[ $setting ][ $subsetting ] ) ) {
					return $settings[ $setting ][ $subsetting ];
				}
			} else {
				return $settings[ $setting ];
			}
		}
		return null;
	}
}

if ( ! function_exists( 'Avada' ) ) {
	function Avada() {
		$avada = new Avada_Compatibility();
		return $avada;
	}

	$avada = Avada();
}

/**
 * Contains all framework specific functions that are not part od a separate class
 *
 * @author      ThemeFusion
 * @package     FusionFramework
 * @since       Version 1.0
 */


if ( ! function_exists( 'fusion_get_related_posts' ) ) {
	/**
	 * Get related posts by category
	 * @param  integer  $post_id       current post id
	 * @param  integer  $number_posts  number of posts to fetch
	 * @return object                  object with posts info
	 */
	function fusion_get_related_posts( $post_id, $number_posts = -1 ) {
		$query = new WP_Query();

		$args = '';

		if ( $number_posts == 0 ) {
			return $query;
		}

		$args = wp_parse_args( $args, array(
			'category__in'        => wp_get_post_categories( $post_id ),
			'ignore_sticky_posts' => 0,
			'posts_per_page'      => $number_posts,
			'post__not_in'        => array( $post_id ),
		) );

		// If placeholder images are disabled,
		// add the _thumbnail_id meta key to the query to only retrieve posts with featured images
		if ( ! Avada()->settings->get( 'featured_image_placeholder' ) ) {
			$args['meta_key'] = '_thumbnail_id';
		}

		$query = new WP_Query( $args );

		return $query;
	}
}

if ( ! function_exists( 'fusion_get_custom_posttype_related_posts' ) ) {
	/**
	 * Get related posts by a custom post type category taxonomy.
	 *
	 * @param  integer  $post_id       current post id
	 * @param  integer  $number_posts  number of posts to fetch
	 * @param  string	$post_type     The custom post type that should be used
	 * @return object                  object with posts info
	 */
	function fusion_get_custom_posttype_related_posts( $post_id, $number_posts = 8, $post_type = 'avada_portfolio' ) {
		$query = new WP_Query();

		$args = '';

		if ( $number_posts == 0 ) {
			return $query;
		}

		$post_type = str_replace( 'avada_', '', $post_type );
		
		$item_cats = get_the_terms( $post_id, $post_type . '_category' );

		$item_array = array();
		if ( $item_cats ) {
			foreach( $item_cats as $item_cat ) {
				$item_array[] = $item_cat->term_id;
			}
		}

		if ( ! empty( $item_array ) ) {
			$args = wp_parse_args( $args, array(
				'ignore_sticky_posts' => 0,
				'posts_per_page'      => $number_posts,
				'post__not_in'        => array( $post_id ),
				'post_type'           => 'avada_' . $post_type,
				'tax_query'           => array(
					array(
						'field'    => 'id',
						'taxonomy' => $post_type . '_category',
						'terms'    => $item_array,
					)
				)
			) );

			// If placeholder images are disabled, add the _thumbnail_id meta key to the query to only retrieve posts with featured images
			if ( ! Avada()->settings->get( 'featured_image_placeholder' ) ) {
				$args['meta_key'] = '_thumbnail_id';
			}

			$query = new WP_Query( $args );
		}

		return $query;
	}
}

/**
 * Function to apply attributes to HTML tags.
 * Devs can override attr in a child theme by using the correct slug
 *
 *
 * @param  string $slug         Slug to refer to the HTML tag
 * @param  array  $attributes   Attributes for HTML tag
 * @return string               Attributes in attr='value' format
 */
if ( ! function_exists( 'fusion_attr' ) ) {
	function fusion_attr( $slug, $attributes = array() ) {

		$out  = '';
		$attr = apply_filters( "fusion_attr_{$slug}", $attributes );

		if ( empty( $attr ) ) {
			$attr['class'] = $slug;
		}

		foreach ( $attr as $name => $value ) {
			$out .= ' ' . esc_html( $name );
			if ( ! empty( $value ) ) {
				$out .= '="' . esc_attr( $value ) . '"';
			}
		}

		return trim( $out );

	}
}

if ( ! function_exists( 'fusion_pagination' ) ) {
	/**
	 * Number based pagination
	 * @param  string  $pages         Maximum number of pages
	 * @param  integer $range
	 * @param  string  $current_query
	 * @return void
	 */
	function fusion_pagination( $pages = '', $range = 2, $current_query = '' ) {
		$showitems = ( $range * 2 ) + 1;

		if ( '' == $current_query ) {
			global $paged;
			if ( empty( $paged ) ) {
				$paged = 1;
			}
		} else {
			$paged = $current_query->query_vars['paged'];
		}

		if ( '' == $pages ) {
			if ( '' == $current_query ) {
				global $wp_query;
				$pages = $wp_query->max_num_pages;
				if ( ! $pages ) {
					$pages = 1;
				}
			} else {
				$pages = $current_query->max_num_pages;
			}
		}
		?>

		<?php if ( 1 != $pages ) : ?>
			<?php if ( ( 'Pagination' != Avada()->settings->get( 'blog_pagination_type' ) && ( is_home() || is_search() || ( 'post' == get_post_type() && ( is_author() || is_archive() ) ) ) ) || ( 'Pagination' != Avada()->settings->get( 'grid_pagination_type' ) && ( avada_is_portfolio_template() || is_post_type_archive( 'avada_portfolio' ) || is_tax( 'portfolio_category' ) || is_tax( 'portfolio_skills' )  || is_tax( 'portfolio_tags' ) ) ) ) : ?>
				<div class='pagination infinite-scroll clearfix'>
			<?php else : ?>
				<div class='pagination clearfix'>
			<?php endif; ?>

			<?php if ( 1 < $paged ) : ?>
				<a class="pagination-prev" href="<?php echo get_pagenum_link( $paged - 1 ); ?>">
					<span class="page-prev"></span>
					<span class="page-text"><?php esc_html_e( 'Previous', 'Avada' ); ?></span>
				</a>
			<?php endif; ?>

			<?php for ( $i=1; $i <= $pages; $i++ ) : ?>
				<?php if ( 1 != $pages && ( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) : ?>
					<?php if ( $paged == $i ) : ?>
						<span class="current"><?php echo $i; ?></span>
					<?php else : ?>
						<a href="<?php echo get_pagenum_link( $i ); ?>" class="inactive"><?php echo $i; ?></a>
					<?php endif; ?>
				<?php endif; ?>
			<?php endfor; ?>

			<?php if ( $paged < $pages ) : ?>
				<a class="pagination-next" href="<?php echo get_pagenum_link( $paged + 1 ); ?>">
					<span class="page-text"><?php esc_html_e( 'Next', 'Avada' ); ?></span>
					<span class="page-next"></span>
				</a>
			<?php endif; ?>

			</div>
			<?php
			// Needed for Theme check
			ob_start();
			posts_nav_link();
			ob_get_clean();
			?>
		<?php endif;

	}
}

if ( ! function_exists( 'fusion_breadcrumbs' ) ) {
	/**
	 * Render the breadcrumbs with help of class-breadcrumbs.php
	 *
	 * @return void
	 */
	function fusion_breadcrumbs() {
		$breadcrumbs = Fusion_Breadcrumbs::get_instance();
		$breadcrumbs->get_breadcrumbs();
	}
}

if ( ! function_exists( 'fusion_strip_unit' ) ) {
	/**
	 * Strips the unit from a given value
	 * @param  string	$value The value with or without unit
	 * @param  string	$unit_to_strip The unit to be stripped
	 *
	 * @return string	the value without a unit
	 */
	function fusion_strip_unit( $value, $unit_to_strip = 'px' ) {
		$value_length = strlen( $value );
		$unit_length = strlen( $unit_to_strip );

		if ( $value_length > $unit_length &&
			 substr_compare( $value, $unit_to_strip, $unit_length * (-1), $unit_length ) === 0
		) {
			return substr( $value, 0, $value_length - $unit_length );
		} else {
			return $value;
		}
	}
}

add_filter( 'feed_link', 'fusion_feed_link', 1, 2 );
/**
 * Replace default WP RSS feed link with theme option RSS feed link
 * @param  string $output Feed link
 * @param  string $feed   Feed type
 * @return string         Return modified feed link
 */
if ( ! function_exists( 'fusion_feed_link' ) ) {
	function fusion_feed_link( $output, $feed ) {
		if ( Avada()->settings->get( 'rss_link' ) ) {
			$feed_url = Avada()->settings->get( 'rss_link' );

			$feed_array = array('rss' => $feed_url, 'rss2' => $feed_url, 'atom' => $feed_url, 'rdf' => $feed_url, 'comments_rss2' => '');
			$feed_array[ $feed ] = $feed_url;
			$output = $feed_array[ $feed ];
		}

		return $output;
	}
}

/**
 * Add paramater to current url
 * @param  string $url         URL to add param to
 * @param  string $param_name  Param name
 * @param  string $param_value Param value
 * @return array               params added to url data
 */
if ( ! function_exists( 'fusion_add_url_parameter' ) ) {
	function fusion_add_url_parameter( $url, $param_name, $param_value ) {
		 $url_data = parse_url($url);
		 if (!isset($url_data["query"]))
			 $url_data["query"]="";

		 $params = array();
		 parse_str($url_data['query'], $params);

		 if ( is_array( $param_value ) ) {
			$param_value = $param_value[0];
		 }

		 $params[$param_name] = $param_value;

		 if ( $param_name == 'product_count' ) {
			$params['paged'] = '1';
		 }

		 $url_data['query'] = http_build_query($params);
		 return fusion_build_url($url_data);
	}
}

/**
 * Build final URL form $url_data returned from fusion_add_url_paramtere
 *
 * @param  array $url_data  url data with custom params
 * @return string           fully formed url with custom params
 */
if ( ! function_exists( 'fusion_build_url' ) ) {
	function fusion_build_url( $url_data ) {
		$url = '';
		if ( isset( $url_data['host'] ) ) {
			$url .= $url_data['scheme'] . '://';
			if ( isset ( $url_data['user'] ) ) {
				$url .= $url_data['user'];
				if ( isset( $url_data['pass'] ) ) {
					$url .= ':' . $url_data['pass'];
				}
				$url .= '@';
			}
			$url .= $url_data['host'];
			if ( isset ( $url_data['port'] ) ) {
				$url .= ':' . $url_data['port'];
			}
		}

		if ( isset( $url_data['path'] ) ) {
			$url .= $url_data['path'];
		}

		if ( isset( $url_data['query'] ) ) {
			$url .= '?' . $url_data['query'];
		}

		if ( isset( $url_data['fragment'] ) ) {
			$url .= '#' . $url_data['fragment'];
		}

		return $url;
	}
}

/**
 * Lightens/darkens a given colour (hex format), returning the altered colour in hex format.7
 * @param str $hex Colour as hexadecimal (with or without hash);
 * @percent float $percent Decimal ( 0.2 = lighten by 20%(), -0.4 = darken by 40%() )
 * @return str Lightened/Darkend colour as hexadecimal (with hash);
 */
 if ( ! function_exists( 'fusion_color_luminance' ) ) {
	function fusion_color_luminance( $hex, $percent ) {
		// validate hex string

		$hex = preg_replace( '/[^0-9a-f]/i', '', $hex );
		$new_hex = '#';

		if ( strlen( $hex ) < 6 ) {
			$hex = $hex[0] + $hex[0] + $hex[1] + $hex[1] + $hex[2] + $hex[2];
		}

		// convert to decimal and change luminosity
		for ($i = 0; $i < 3; $i++) {
			$dec = hexdec( substr( $hex, $i*2, 2 ) );
			$dec = min( max( 0, $dec + $dec * $percent ), 255 );
			$new_hex .= str_pad( dechex( $dec ) , 2, 0, STR_PAD_LEFT );
		}

		return $new_hex;
	}
}

/**
 * Adjusts brightness of the $hex and rgba colors.
 *
 * @var     string      The hex or rgba value of a color
 * @var     int         a value between -255 (darken) and 255 (lighten)
 * @return  string      returns hex color or rgba, depending on input
 */
if ( ! function_exists( 'fusion_adjust_brightness' ) ) {
	function fusion_adjust_brightness( $color, $steps ) {
		// Get the hex value, and if using rgba colors the opacity as well.
		$opacity = '1';
		if ( false !== strpos( $color, 'rgba' ) ) {
			$hex     = Avada_Color::rgba2hex( $color, false );
			$opacity = Avada_Color::get_alpha_from_rgba( $color );
			$is_rgba = true;
		} else {
			$hex     = $color;
			$is_rgba = false;
		}
		// Change the brightness
		// Internally this also sanitizes the hex value
		$hex = Avada_Color::adjust_brightness( $hex, $steps );

		return ( $is_rgba ) ? Avada_Color::get_rgba( $hex, $opacity ) : $hex;
	}
}

/**
 * Convert Calculate the brightness of a color
 * @param  string $color Color (Hex) Code
 * @return integer brightness level
 */
if ( ! function_exists( 'fusion_calc_color_brightness' ) ) {
	function fusion_calc_color_brightness( $color ) {

		if ( strtolower( $color ) == 'black' ||
			strtolower( $color ) == 'navy' ||
			strtolower( $color ) == 'purple' ||
			strtolower( $color ) == 'maroon' ||
			strtolower( $color ) == 'indigo' ||
			strtolower( $color ) == 'darkslategray' ||
			strtolower( $color ) == 'darkslateblue' ||
			strtolower( $color ) == 'darkolivegreen' ||
			strtolower( $color ) == 'darkgreen' ||
			strtolower( $color ) == 'darkblue'
		) {
			$brightness_level = 0;
		} elseif ( strpos( $color, '#' ) === 0 ) {
			$color = fusion_hex2rgb( $color );

			$brightness_level = sqrt( pow( $color[0], 2) * 0.299 + pow( $color[1], 2) * 0.587 + pow( $color[2], 2) * 0.114 );
		} else {
			$brightness_level = 150;
		}

		return $brightness_level;
	}
}

/**
 * Convert Hex Code to RGB
 * @param  string $hex Color Hex Code
 * @return array       RGB values
 */
if ( ! function_exists( 'fusion_hex2rgb' ) ) {
	function fusion_hex2rgb( $hex ) {
		if ( strpos( $hex,'rgb' ) !== FALSE ) {

			$rgb_part = strstr( $hex, '(' );
			$rgb_part = trim($rgb_part, '(' );
			$rgb_part = rtrim($rgb_part, ')' );
			$rgb_part = explode( ',', $rgb_part );

			$rgb = array($rgb_part[0], $rgb_part[1], $rgb_part[2], $rgb_part[3]);

		} elseif ( $hex == 'transparent' ) {
			$rgb = array( '255', '255', '255', '0' );
		} else {

			$hex = str_replace( '#', '', $hex );

			if ( strlen( $hex ) == 3 ) {
				$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
				$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
				$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
			} else {
				$r = hexdec( substr( $hex, 0, 2 ) );
				$g = hexdec( substr( $hex, 2, 2 ) );
				$b = hexdec( substr( $hex, 4, 2 ) );
			}
			$rgb = array( $r, $g, $b );
		}

		return $rgb; // returns an array with the rgb values
	}
}

/**
 * Convert RGB to HSL color model
 * @param  string $hex Color Hex Code of RGB color
 * @return array       HSL values
 */
if ( ! function_exists( 'fusion_rgb2hsl' ) ) {
	function fusion_rgb2hsl( $hex_color ) {

		$hex_color  = str_replace( '#', '', $hex_color );

		if ( strlen( $hex_color ) < 3 ) {
			str_pad( $hex_color, 3 - strlen( $hex_color ), '0' );
		}

		$add         = strlen( $hex_color ) == 6 ? 2 : 1;
		$aa       = 0;
		$add_on   = $add == 1 ? ( $aa = 16 - 1 ) + 1 : 1;

		$red         = round( ( hexdec( substr( $hex_color, 0, $add ) ) * $add_on + $aa ) / 255, 6 );
		$green     = round( ( hexdec( substr( $hex_color, $add, $add ) ) * $add_on + $aa ) / 255, 6 );
		$blue       = round( ( hexdec( substr( $hex_color, ( $add + $add ) , $add ) ) * $add_on + $aa ) / 255, 6 );

		$hsl_color  = array( 'hue' => 0, 'sat' => 0, 'lum' => 0 );

		$minimum     = min( $red, $green, $blue );
		$maximum     = max( $red, $green, $blue );

		$chroma   = $maximum - $minimum;

		$hsl_color['lum'] = ( $minimum + $maximum ) / 2;

		if ( $chroma == 0 ) {
			$hsl_color['lum'] = round( $hsl_color['lum'] * 100, 0 );

			return $hsl_color;
		}

		$range = $chroma * 6;

		$hsl_color['sat'] = $hsl_color['lum'] <= 0.5 ? $chroma / ( $hsl_color['lum'] * 2 ) : $chroma / ( 2 - ( $hsl_color['lum'] * 2 ) );

		if ( $red <= 0.004 ||
			$green <= 0.004 ||
			$blue <= 0.004
		) {
			$hsl_color['sat'] = 1;
		}

		if ( $maximum == $red ) {
			$hsl_color['hue'] = round( ( $blue > $green ? 1 - ( abs( $green - $blue ) / $range ) : ( $green - $blue ) / $range ) * 255, 0 );
		} else if ( $maximum == $green ) {
			$hsl_color['hue'] = round( ( $red > $blue ? abs( 1 - ( 4 / 3 ) + ( abs ( $blue - $red ) / $range ) ) : ( 1 / 3 ) + ( $blue - $red ) / $range ) * 255, 0 );
		} else {
			$hsl_color['hue'] = round( ( $green < $red ? 1 - 2 / 3 + abs( $red - $green ) / $range : 2 / 3 + ( $red - $green ) / $range ) * 255, 0 );
		}

		$hsl_color['sat'] = round( $hsl_color['sat'] * 100, 0 );
		$hsl_color['lum']  = round( $hsl_color['lum'] * 100, 0 );

		return $hsl_color;
	}
}

/**
 * Get theme option value
 * @param  string $theme_option ID of theme option
 * @return string               Value of theme option
 */
if ( ! function_exists( 'fusion_get_theme_option' ) ) {
	function fusion_get_theme_option( $theme_option ) {

		if ( $theme_option && null !== Avada()->settings->get( $theme_option ) ) {
			return Avada()->settings->get( $theme_option );
		}

		return FALSE;
	}
}


/**
 * Get page option value
 * @param  string  $page_option ID of page option
 * @param  integer $post_id     Post/Page ID
 * @return string               Value of page option
 */
if ( ! function_exists( 'fusion_get_page_option' ) ) {
	function fusion_get_page_option( $page_option, $post_id ) {
		if ( $page_option &&
			$post_id
		) {
			return get_post_meta( $post_id, 'pyre_' . $page_option, true );
		}

		return FALSE;
	}
}

/**
 * Get theme option or page option
 * @param  string  $theme_option Theme option ID
 * @param  string  $page_option  Page option ID
 * @param  integer $post_id      Post/Page ID
 * @return string                Theme option or page option value
 */
if ( ! function_exists( 'fusion_get_option' ) ) {
	function fusion_get_option( $theme_option, $page_option, $post_id ) {
		if ( $theme_option &&
			 $page_option &&
			 ( $post_id || $post_id == '0' )
		) {
			$page_option = strtolower( fusion_get_page_option( $page_option, $post_id ) );			
			$theme_option = strtolower( Avada()->settings->get( $theme_option ) );

			if ( $page_option != 'default' &&
				 ! empty ( $page_option )
			) {
				return $page_option;
			} else {
				return $theme_option;
			}
		}

		return FALSE;
	}
}

/**
 * Get theme option or page option when mismatched
 * @param  string  $theme_option Theme option ID
 * @param  string  $page_option  Page option ID
 * @param  integer $post_id      Post/Page ID
 * @since  4.0
 * @return string                Theme option or page option value
 */
if ( ! function_exists( 'fusion_get_mismatch_option' ) ) {
	function fusion_get_mismatch_option( $theme_option, $page_option, $post_id ) {
		if ( $theme_option &&
			 $page_option &&
			 $post_id
		) {
			$page_option = strtolower( fusion_get_page_option( $page_option, $post_id ) );
			$theme_option = strtolower( Avada()->settings->get( $theme_option ) );
			if( $theme_option == 1 ){ 
				$theme_option = 0;
			}else{
				$theme_option = 1;
			}

			if ( $page_option != 'default' &&
				 ! empty ( $page_option )
			) {
				return $page_option;
			} else {
				return $theme_option;
			}
		}

		return FALSE;
	}
}

/**
 * Compress CSS
 * @param  string $minify CSS to compress
 * @return string         Compressed CSS
 */
if ( ! function_exists( 'fusion_compress_css' ) ) {
	function fusion_compress_css( $minify ) {
		/* remove comments */
		$minify = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $minify );

		/* remove tabs, spaces, newlines, etc. */
		$minify = str_replace( array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $minify );

		return $minify;
	}
}

/**
 * Returns the excerpt length for portfolio posts.
 *
 * @since 4.0.0
 *
 * @param  string	$page_id		The id of the current page or post
 *
 * @return string/boolean The excerpt length for the post; false if full content should be shown
 **/
function avada_get_portfolio_excerpt_length( $page_id = '' ) {
	$excerpt_length = false;

	if ( fusion_get_option( 'portfolio_content_length', 'portfolio_content_length', $page_id ) == 'excerpt' ) {
		// Determine the correct excerpt length
		if ( fusion_get_page_option( 'portfolio_excerpt', $page_id ) ) {
			$excerpt_length = fusion_get_page_option( 'portfolio_excerpt', $page_id );
		} else {
			$excerpt_length =  Avada()->settings->get( 'excerpt_length_portfolio' );
		}
	} else if ( ! $page_id &&
				Avada()->settings->get( 'portfolio_content_length' ) == 'Excerpt'
	) {
		$excerpt_length =  Avada()->settings->get( 'excerpt_length_portfolio' );
	}
	
	return $excerpt_length;

}

if ( ! function_exists( 'fusion_get_post_content' ) ) {
	/**
	 * Return the post content, either excerpted or in full length
	 * @param  string	$page_id		The id of the current page or post
	 * @param  string 	$excerpt		Can be either 'blog' (for main blog page), 'portfolio' (for portfolio page template) or 'yes' (for shortcodes)
	 * @param  integer	$excerpt_length Length of the excerpts
	 * @param  boolean	$strip_html		Can be used by shortcodes for a custom strip html setting
	 *
	 * @return string Post content
	 **/
	function fusion_get_post_content( $page_id = '', $excerpt = 'blog', $excerpt_length = 55, $strip_html = FALSE ) {

		$content_excerpted = FALSE;

		// Main blog page
		if ( $excerpt == 'blog' ) {

			// Check if the content should be excerpted
			if ( strtolower( Avada()->settings->get( 'content_length' ) ) == 'excerpt' ) {
				$content_excerpted = TRUE;

				// Get the excerpt length
				$excerpt_length = Avada()->settings->get( 'excerpt_length_blog' );
			}

			// Check if HTML should be stripped from contant
			if ( Avada()->settings->get( 'strip_html_excerpt' ) ) {
				$strip_html = TRUE;
			}

		// Portfolio page templates
		} elseif ( $excerpt == 'portfolio' ) {
			// Check if the content should be excerpted
			$portfolio_excerpt_length = avada_get_portfolio_excerpt_length( $page_id );
			if ( $portfolio_excerpt_length !== false ) {
				$excerpt_length = $portfolio_excerpt_length;
				$content_excerpted = TRUE;
			}

			// Check if HTML should be stripped from contant
			if ( Avada()->settings->get( 'portfolio_strip_html_excerpt' ) ) {
				$strip_html = TRUE;
			}
		// Shortcodes
		} elseif ( $excerpt == 'yes' ) {
			$content_excerpted = TRUE;
		}

		// Sermon specific additional content
		if ( 'wpfc_sermon' == get_post_type( get_the_ID() ) ) {
			$sermon_content = '';
			$sermon_content .= avada_get_sermon_content( true );

			return $sermon_content;
		}

		// Return excerpted content
		if ( $content_excerpted ) {

			$stripped_content = fusion_get_post_content_excerpt( $excerpt_length, $strip_html );

			return $stripped_content;

		// Return full content
		} else {
			ob_start();
			the_content();

			return ob_get_clean();
		}
	}
}

if ( ! function_exists( 'fusion_get_post_content_excerpt' ) ) {
	/**
	 * Do the actual custom excerpting for of post/page content
	 * @param  string 	$limit 		Maximum number of words or chars to be displayed in excerpt
	 * @param  boolean 	$strip_html Set to TRUE to strip HTML tags from excerpt
	 *
	 * @return string 				The custom excerpt
	 **/
	function fusion_get_post_content_excerpt( $limit, $strip_html ) {
		global $more;

		$content = '';

		$limit = intval( $limit );

		// If excerpt length is set to 0, return empty
		if ( $limit === 0 ) {
			return $content;
		}

		// Set a default excerpt limit if none is set
		if ( ! $limit &&
			$limit != 0
		) {
			$limit = 285;
		}

		// Make sure $strip_html is a boolean
		if ( $strip_html == "true" ||
			$strip_html == TRUE
		) {
			$strip_html = TRUE;
		} else {
			$strip_html = FALSE;
		}

		$custom_excerpt = FALSE;

		$post = get_post( get_the_ID() );

		// Check if the more tag is used in the post
		$pos = strpos( $post->post_content, '<!--more-->' );

		// Check if the read more [...] should link to single post
		$read_more_text = apply_filters( 'avada_blog_read_more_excerpt', '&#91;...&#93;' );

		if ( Avada()->settings->get( 'link_read_more' ) ) {
			$read_more = sprintf( ' <a href="%s">%s</a>', get_permalink( get_the_ID() ), $read_more_text );
		} else {
			$read_more = ' ' . $read_more_text;
		}

		if ( ! Avada()->settings->get( 'disable_excerpts' ) ) {
			$read_more = '';
		}

		// HTML tags should be stripped
		if ( $strip_html ) {
			$more = 0;
			$raw_content = wp_strip_all_tags( get_the_content( '{{read_more_placeholder}}' ), '<p>' );

			// Strip out all attributes
			$raw_content = preg_replace('/<(\w+)[^>]*>/', '<$1>', $raw_content);

			$raw_content = str_replace( '{{read_more_placeholder}}', $read_more, $raw_content );

			if ( $post->post_excerpt ||
				$pos !== FALSE
			) {
				$more = 0;
				if ( ! $pos ) {
					$raw_content = wp_strip_all_tags( rtrim( get_the_excerpt(), '[&hellip;]' ), '<p>' ) . $read_more;
				}
				$custom_excerpt = TRUE;
			}
		// HTML tags remain in excerpt
		} else {
			$more = 0;
			$raw_content = get_the_content( $read_more );
			if ( $post->post_excerpt ||
				$pos !== FALSE
			) {
				$more = 0;
				if ( ! $pos ) {
					$raw_content = rtrim( get_the_excerpt(), '[&hellip;]' ) . $read_more;
				}
				$custom_excerpt = TRUE;
			}
		}

		// We have our raw post content and need to cut it down to the excerpt limit
		if ( ( $raw_content && $custom_excerpt == FALSE )
			 || $post->post_type == 'product'
		) {
			$pattern = get_shortcode_regex();
			$content = preg_replace_callback( "/$pattern/s", 'avada_extract_shortcode_contents', $raw_content );

			// Check if the excerpting should be char or word based
			if ( Avada()->settings->get( 'excerpt_base' ) == 'Characters' ) {
				$content = mb_substr($content, 0, $limit);
				if ( $limit != 0 &&
					Avada()->settings->get( 'disable_excerpts' )
				) {
					$content .= $read_more;
				}
			// Excerpting is word based
			} else {
				$content = explode( ' ', $content, $limit + 1 );
				if ( count( $content ) > $limit ) {
					array_pop( $content );
					if ( ! Avada()->settings->get( 'disable_excerpts' ) ) {
						$content = implode( ' ', $content );
					} else {
						$content = implode( ' ', $content);
						if ( $limit != 0 ) {
							if ( Avada()->settings->get( 'link_read_more' ) ) {
								$content .= $read_more;
							} else {
								$content .= $read_more;
							}
						}
					}
				} else {
					$content = implode( ' ', $content );
				}
			}

			if ( $limit != 0 && ! $strip_html ) {
				$content = apply_filters( 'the_content', $content );
				$content = str_replace( ']]>', ']]&gt;', $content );
			} else {
				$content = sprintf( '<p>%s</p>', $content );
			}

			$content = do_shortcode( $content );

			return $content;
		}

		// If we have a custom excerpt, e.g. using the <!--more--> tag
		if ( $custom_excerpt == TRUE ) {
			$pattern = get_shortcode_regex();
			$content = preg_replace_callback( "/$pattern/s", 'avada_extract_shortcode_contents', $raw_content );
			if ( $strip_html == TRUE ) {
				$content = apply_filters( 'the_content', $content );
				$content = str_replace( ']]>', ']]&gt;', $content );
				$content = do_shortcode( $content );
			} else {
				$content = apply_filters( 'the_content', $content );
				$content = str_replace( ']]>', ']]&gt;', $content );
			}
		}

		// If the custom excerpt field is used, just use that contents
		if ( has_excerpt() && $post->post_type != 'product' ) {
			$content = '<p>' . do_shortcode( get_the_excerpt() ) . '</p>';
		}

		return $content;
	}
}

/**
 * Get attachment data by URL
 * @param  string 	$image_url 		The Image URL
 *
 * @return array 					Image Details
 **/
if ( ! function_exists( 'fusion_get_attachment_data_by_url' ) ) {
	function fusion_get_attachment_data_by_url( $image_url, $logo_field = '' ) {
		global $wpdb;

		$attachment = $wpdb->get_col( $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ) );

		if ( $attachment ) {
			return wp_get_attachment_metadata( $attachment[0] );
		} else { // import the image to media library
			$import_image = fusion_import_to_media_library( $image_url, $logo_field );
			if ( $import_image ) {
				return wp_get_attachment_metadata( $import_image );
			} else {
				return false;
			}
		}
	}
}

if ( ! function_exists( 'fusion_import_to_media_library' ) ) {
	function fusion_import_to_media_library( $url, $theme_option = '' ) {

		// gives us access to the download_url() and wp_handle_sideload() functions
		require_once(ABSPATH . 'wp-admin/includes/file.php');

		$timeout_seconds = 30;

		// download file to temp dir
		$temp_file = download_url( $url, $timeout_seconds );

		if ( ! is_wp_error( $temp_file ) ) {
			// array based on $_FILE as seen in PHP file uploads
			$file = array(
				'name' => basename( $url ), // ex: wp-header-logo.png
				'type' => 'image/png',
				'tmp_name' => $temp_file,
				'error' => 0,
				'size' => filesize( $temp_file ),
			);

			$overrides = array(
				// tells WordPress to not look for the POST form
				// fields that would normally be present, default is true,
				// we downloaded the file from a remote server, so there
				// will be no form fields
				'test_form' => false,

				// setting this to false lets WordPress allow empty files, not recommended
				'test_size' => true,

				// A properly uploaded file will pass this test.
				// There should be no reason to override this one.
				'test_upload' => true,
			);

			// move the temporary file into the uploads directory
			$results = wp_handle_sideload( $file, $overrides );

			if ( ! empty( $results['error'] ) ) {
				return false;
			} else {
				$attachment = array(
					'guid'           => $results['url'],
					'post_mime_type' => $results['type'],
					'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $results['file'] ) ),
					'post_content'   => '',
					'post_status'    => 'inherit'
				);

				// Insert the attachment.
				$attach_id = wp_insert_attachment( $attachment, $results['file'] );

				// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
				require_once( ABSPATH . 'wp-admin/includes/image.php' );

				// Generate the metadata for the attachment, and update the database record.
				$attach_data = wp_generate_attachment_metadata( $attach_id, $results['file'] );
				wp_update_attachment_metadata( $attach_id, $attach_data );

				if ( $theme_option ) {
					Avada()->settings->set( $theme_option, $results['url'] );
				}

				return $attach_id;
			}
		} else {
			return false;
		}
	}
}
// Omit closing PHP tag to avoid "Headers already sent" issues.

/**
 * Contains all theme specific functions
 *
 * @author  ThemeFusion
 * @package Avada
 * @since   Version 3.8
 */

// Do not allow directly accessing this file
if ( ! defined( 'ABSPATH' ) ) exit( 'Direct script access denied.' );

/**
 * Get the post (excerpt)
 *
 * @return void Content is directly echoed
 **/
if ( ! function_exists( 'avada_render_blog_post_content' ) ) {
	function avada_render_blog_post_content() {
		if ( is_search() && ! Avada()->settings->get( 'search_excerpt' ) ) {
			return;
		}
		echo fusion_get_post_content();
	}
}
add_action( 'avada_blog_post_content', 'avada_render_blog_post_content', 10 );

/**
 * Get the portfolio post (excerpt)
 *
 * @return void Content is directly echoed
 **/
if ( ! function_exists( 'avada_render_portfolio_post_content' ) ) {
	function avada_render_portfolio_post_content( $page_id ) {
		echo fusion_get_post_content( $page_id, 'portfolio' );
	}
}
add_action( 'avada_portfolio_post_content', 'avada_render_portfolio_post_content', 10 );

/**
 * Render the HTML for the date box for large/medium alternate blog layouts
 *
 * @return void directly echoed HTML markup to display the date box
 **/
if ( ! function_exists( 'avada_render_blog_post_date' ) ) {
	function avada_render_blog_post_date() {
		get_template_part( 'templates/blog-post-date' );
	}
}
add_action( 'avada_blog_post_date_and_format', 'avada_render_blog_post_date', 10 );

/**
 * Render the HTML for the format box for large/medium alternate blog layouts
 *
 * @return void directly echoed HTML markup to display the format box
 **/
if ( ! function_exists( 'avada_render_blog_post_format' ) ) {
	function avada_render_blog_post_format() {
		get_template_part( 'templates/post-format-box' );
	}
}
add_action( 'avada_blog_post_date_and_format', 'avada_render_blog_post_format', 15 );

/**
 * Output author information on the author archive page
 *
 * @return void directly echos the author info HTML markup
 **/
if ( ! function_exists( 'avada_render_author_info' ) ) {
	function avada_render_author_info() {
		get_template_part( 'templates/author-info' );
	}
}
add_action( 'avada_author_info', 'avada_render_author_info', 10 );

/**
 * Output the footer copyright notice
 *
 * @return void directly echos the footer copyright notice HTML markup
 **/
if ( ! function_exists( 'avada_render_footer_copyright_notice' ) ) {
	function avada_render_footer_copyright_notice() { ?>
		<div class="fusion-copyright-notice">
			<div><?php echo html_entity_decode( do_shortcode( Avada()->settings->get( 'footer_text' ) ) ); ?></div>
		</div>
		<?php
	}
}
add_action( 'avada_footer_copyright_content', 'avada_render_footer_copyright_notice', 10 );

/**
 * Output the footer social icons
 *
 * @return void directly echos the footer footer social icons HTML markup
 **/
if ( ! function_exists( 'avada_render_footer_social_icons' ) ) {
	function avada_render_footer_social_icons() {
		global $social_icons;

		// Render the social icons
		if ( Avada()->settings->get( 'icons_footer' ) ) : ?>
			<div class="fusion-social-links-footer">
				<?php

				$footer_soical_icon_options = array (
					'position'          => 'footer',
					'icon_colors'       => Avada()->settings->get( 'footer_social_links_icon_color' ),
					'box_colors'        => Avada()->settings->get( 'footer_social_links_box_color' ),
					'icon_boxed'        => Avada()->settings->get( 'footer_social_links_boxed' ),
					'icon_boxed_radius' => Avada_Sanitize::size( Avada()->settings->get( 'footer_social_links_boxed_radius' ) ),
					'tooltip_placement' => Avada()->settings->get( 'footer_social_links_tooltip_placement' ),
					'linktarget'        => Avada()->settings->get( 'social_icons_new' ),
				);

				echo $social_icons->render_social_icons( $footer_soical_icon_options ); ?>
			</div>
		<?php endif;
	}
}
add_action( 'avada_footer_copyright_content', 'avada_render_footer_social_icons', 15 );

/**
 * Output the image rollover
 * @param  string    $post_id                    ID of the current post
 * @param  string    $permalink                  Permalink of current post
 * @param  boolean   $display_woo_price          Set to yes to show´woocommerce price tag for woo sliders
 * @param  boolean   $display_woo_buttons        Set to yes to show the woocommerce "add to cart" and "show details" buttons
 * @param  string    $display_post_categories    Controls if the post categories will be shown; "deafult": theme option setting; enable/disable otheriwse
 * @param  string    $display_post_title         Controls if the post title will be shown; "deafult": theme option setting; enable/disable otheriwse
 * @param  string    $gallery_id                 ID of a special gallery the rollover "zoom" link should be connected to for lightbox
 *
 * @return void     Directly echos the placeholder image HTML markup
 **/
if ( ! function_exists( 'avada_render_rollover' ) ) {
	function avada_render_rollover( $post_id, $post_permalink = '', $display_woo_price = false, $display_woo_buttons = false, $display_post_categories = 'default', $display_post_title = 'default', $gallery_id = '', $display_woo_rating = false ) {
		include( locate_template( 'templates/rollover.php' ) );
	}
}
add_action( 'avada_rollover', 'avada_render_rollover', 10, 8 );

/**
 * Action to output a placeholder image
 * @param  string $featured_image_size     Size of the featured image that should be emulated
 *
 * @return void                            Directly echos the placeholder image HTML markup
 **/
if ( ! function_exists( 'avada_render_placeholder_image' ) ) {
	function avada_render_placeholder_image( $featured_image_size = 'full' ) {
		global $_wp_additional_image_sizes;

		if ( in_array( $featured_image_size, array( 'full', 'fixed' ) ) ) {
			$height = apply_filters( 'avada_set_placeholder_image_height', '150' );
			$width  = '1500px';
		} else {
			@$height = $_wp_additional_image_sizes[ $featured_image_size ]['height'];
			@$width  = $_wp_additional_image_sizes[ $featured_image_size ]['width'] . 'px';
		 }
		 ?>
		 <div class="fusion-placeholder-image" data-origheight="<?php echo $height; ?>" data-origwidth="<?php echo $width; ?>" style="height:<?php echo $height; ?>px;width:<?php echo $width; ?>;"></div>
		 <?php
	}
}
add_action( 'avada_placeholder_image', 'avada_render_placeholder_image', 10 );

if ( ! function_exists( 'avada_render_first_featured_image_markup' ) ) {
	/**
	 * Render the full markup of the first featured image, incl. image wrapper and rollover
	 * @param  string    $post_id                   ID of the current post
	 * @param  string    $post_featured_image_size  Size of the featured image
	 * @param  string    $post_permalink            Permalink of current post
	 * @param  boolean   $display_placeholder_image Set to true to show an image placeholder
	 * @param  boolean   $display_woo_price         Set to true to show WooCommerce prices
	 * @param  boolean   $display_woo_buttons       Set to true to show WooCommerce buttons
	 * @param  boolean   $display_post_categories   Set to yes to show post categories on rollover
	 * @param  string    $display_post_title        Controls if the post title will be shown; "default": theme option setting; enable/disable otheriwse
	 * @param  string    $type                      Type of element the featured image is for. "Related" for related posts is the only type in use so far
	 * @param  string    $gallery_id                ID of a special gallery the rollover "zoom" link should be connected to for lightbox
	 * @param  string    $display_rollover          yes|no|force_yes: no disables rollover; force_yes will force rollover even if the Theme Option is set to no
	 *
	 * @return string Full HTML markup of the first featured image
	 **/
	function avada_render_first_featured_image_markup( $post_id, $post_featured_image_size = '', $post_permalink = '', $display_placeholder_image = false, $display_woo_price = false, $display_woo_buttons = false, $display_post_categories = 'default', $display_post_title = 'default', $type = '', $gallery_id = '', $display_rollover = 'yes', $display_woo_rating = false ) {
		// Add a class for fixed image size, to restrict the image rollovers to the image width
		$image_size_class = ( 'full' != $post_featured_image_size ) ? ' fusion-image-size-fixed' : '';
		$image_size_class = ( ( ! has_post_thumbnail( $post_id ) && get_post_meta( $post_id, 'pyre_video', true ) ) || ( is_home() && 'blog-large' == $post_featured_image_size ) ) ? '' : $image_size_class;

		ob_start();
		include( locate_template( 'templates/featured-image-first.php' ) );
		return ob_get_clean();
	}
}

if ( ! function_exists( 'avada_get_image_orientation_class' ) ) {
	/**
	 * Returns the image class according to aspect ratio
	 *
	 * @return string The image class
	 **/
	function avada_get_image_orientation_class( $attachment ) {

		$sixteen_to_nine_ratio = 1.77;
		$imgage_class = 'fusion-image-grid';

		if ( ! empty( $attachment[1] ) && ! empty( $attachment[2] ) ) {
			// Landscape
			if ( $attachment[1] / $attachment[2] > $sixteen_to_nine_ratio ) {
				$imgage_class = 'fusion-image-landscape';
			// Portrait
			} elseif ( $attachment[2] / $attachment[1] > $sixteen_to_nine_ratio ) {
				$imgage_class = 'fusion-image-portrait';
			}
		}

		return $imgage_class;
	}
}

if ( ! function_exists( 'avada_render_post_title' ) ) {
	/**
	 * Render the post title as linked h1 tag
	 *
	 * @return string The post title as linked h1 tag
	 **/
	function avada_render_post_title( $post_id = '', $linked = true, $custom_title = '', $custom_size = '2' ) {

		$entry_title_class = '';

		// Add the entry title class if rich snippets are enabled
		if ( Avada()->settings->get( 'disable_date_rich_snippet_pages' ) ) {
			$entry_title_class = ' class="entry-title"';
		}

		// If we have a custom title, use it otherwise get post title
		$title = ( $custom_title ) ? $custom_title : get_the_title( $post_id );

		// If the post title should be linked at the markup
		if ( $linked ) {
			$link_target = '';
			if ( 'yes' == fusion_get_page_option( 'link_icon_target', $post_id ) || 'yes' == fusion_get_page_option( 'post_links_target', $post_id ) ) {
				$link_target = ' target="_blank"';
			}

			$title = '<a href="' . get_permalink( $post_id ) . '"' . $link_target . '>' . $title . '</a>';
		}

		// return the HTML markup of the post title
		return '<h' . $custom_size . $entry_title_class . '>' . $title . '</h' . $custom_size . '>';

	}
}

if ( ! function_exists( 'avada_get_portfolio_classes' ) ) {
	/**
	 * Determine the css classes need for portfolio page content container
	 *
	 * @return string The classes separated with space
	 **/
	function avada_get_portfolio_classes( $post_id = '' ) {

		$classes = 'fusion-portfolio';

		// Get the page template slug without .php suffix
		$page_template = str_replace( '.php', '', get_page_template_slug( $post_id ) );

		// Add the text class, if a text layout is used
		if ( strpos( $page_template, 'text' ) || strpos( $page_template, 'one' ) ) {
			$classes .= ' fusion-portfolio-text';
		}

		// If one column text layout is used, add special class
		if ( strpos( $page_template, 'one' ) && ! strpos( $page_template, 'text' ) ) {
			$classes .= ' fusion-portfolio-one-nontext';
		}

		// For text layouts add the class for boxed/unboxed
		if ( strpos( $page_template, 'text' ) ) {
			$classes .= ' fusion-portfolio-' . fusion_get_option( 'portfolio_text_layout', 'portfolio_text_layout', $post_id  ) . ' ';
			$page_template = str_replace( '-text', '', $page_template );
		}

		// Add the column class
		$page_template = str_replace( '-column', '', $page_template );
		return $classes . ' fusion-' . $page_template;

	}
}

if ( ! function_exists( 'avada_is_portfolio_template' ) ) {
	function avada_is_portfolio_template() {
		if ( is_page_template( 'portfolio-one-column-text.php' ) ||
			is_page_template( 'portfolio-one-column.php' ) ||
			is_page_template( 'portfolio-two-column.php' ) ||
			is_page_template( 'portfolio-two-column-text.php' ) ||
			is_page_template( 'portfolio-three-column.php' ) ||
			is_page_template( 'portfolio-three-column-text.php' ) ||
			is_page_template( 'portfolio-four-column.php' ) ||
			is_page_template( 'portfolio-four-column-text.php' ) ||
			is_page_template( 'portfolio-five-column.php' ) ||
			is_page_template( 'portfolio-five-column-text.php' ) ||
			is_page_template( 'portfolio-six-column.php' ) ||
			is_page_template( 'portfolio-six-column-text.php' ) ||
			is_page_template( 'portfolio-grid.php' )
		) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'avada_get_image_size_dimensions' ) ) {
	function avada_get_image_size_dimensions( $image_size = 'full' ) {
		global $_wp_additional_image_sizes;
		return ( 'full' == $image_size ) ? array( 'height' => 'auto', 'width' => '100%' ) : array( 'height' => $_wp_additional_image_sizes[ $image_size ]['height'] . 'px', 'width' => $_wp_additional_image_sizes[ $image_size ]['width'] . 'px' );
	}
}

if ( ! function_exists( 'avada_get_portfolio_image_size' ) ) {
	function avada_get_portfolio_image_size( $current_page_id ) {

		$custom_image_size = 'full';
		if (  is_page_template( 'portfolio-one-column-text.php' ) ) {
			$custom_image_size = 'portfolio-full';
		} else if ( is_page_template( 'portfolio-one-column.php' ) ) {
			$custom_image_size = 'portfolio-one';
		} else if ( is_page_template( 'portfolio-two-column.php' ) || is_page_template( 'portfolio-two-column-text.php' ) ) {
			$custom_image_size = 'portfolio-two';
		} else if ( is_page_template( 'portfolio-three-column.php' ) || is_page_template( 'portfolio-three-column-text.php' ) ) {
			$custom_image_size = 'portfolio-three';
		} else if ( is_page_template( 'portfolio-four-column.php' ) || is_page_template( 'portfolio-four-column-text.php' ) ) {
			$custom_image_size = 'portfolio-three';
		} else if ( is_page_template( 'portfolio-five-column.php' ) || is_page_template( 'portfolio-five-column-text.php' ) ) {
			$custom_image_size = 'portfolio-five';
		} else if ( is_page_template( 'portfolio-six-column.php' ) || is_page_template( 'portfolio-six-column-text.php' ) ) {
			$custom_image_size = 'portfolio-five';
		}

		if ( 'default' == get_post_meta( $current_page_id, 'pyre_portfolio_featured_image_size', true ) || ! get_post_meta( $current_page_id, 'pyre_portfolio_featured_image_size', true ) ) {
			$featured_image_size = ( 'full' == Avada()->settings->get( 'portfolio_featured_image_size' ) ) ? 'full' : $custom_image_size;
		} else if ( 'full' == get_post_meta( $current_page_id, 'pyre_portfolio_featured_image_size', true ) ) {
			$featured_image_size = 'full';
		} else {
			$featured_image_size = $custom_image_size;
		}

		if ( is_page_template( 'portfolio-grid.php' ) ) {
			$featured_image_size = 'full';
		}

		return $featured_image_size;
	}
}

/**
 * Returns the number of columns for a given portfolio layout.
 *
 * @since 4.0.0
 *
 * @param string $layout The proftlio layout.
 *
 * @return string The number of columns.
 */
function avada_get_portfolio_columns( $layout = '' ) {
	if ( strpos( $layout, 'six' ) !== false ) {
		$columns = '6';
	} else if ( strpos( $layout, 'five' ) !== false ) {
		$columns = '5';
	} else if ( strpos( $layout, 'four' ) !== false ) {
		$columns = '4';
	} else if ( strpos( $layout, 'three' ) !== false ) {
		$columns = '3';
	} else if ( strpos( $layout, 'two' ) !== false ) {
		$columns = '2';
	} else {
		$columns = '1';
	}

	return $columns;
}

if ( ! function_exists( 'avada_get_blog_layout' ) ) {
	/**
	 * Get the blog layout for the current page template
	 *
	 * @return string The correct layout name for the blog post class
	 **/
	function avada_get_blog_layout() {
		$theme_options_blog_var = '';

		if ( is_home() ) {
			$theme_options_blog_var = 'blog_layout';
		} elseif ( is_archive() || is_author() ) {
			$theme_options_blog_var = 'blog_archive_layout';
		} elseif ( is_search() ) {
			$theme_options_blog_var = 'search_layout';
		}

		return str_replace( ' ', '-', strtolower( Avada()->settings->get( $theme_options_blog_var ) ) );
	}
}

if ( ! function_exists( 'avada_render_post_metadata' ) ) {
	/**
	 * Render the full meta data for blog archive and single layouts
	 * @param     string $layout     The blog layout (either single, standard, alternate or grid_timeline)
	 *
	 * @return    string             HTML markup to display the date and post format box
	 **/
	function avada_render_post_metadata( $layout, $settings = array() ) {

		$html = $author = $date = $metadata = '';

		$settings = ( is_array( $settings ) ) ? $settings : array();

		$default_settings = array(
			'post_meta'          => Avada()->settings->get( 'post_meta' ),
			'post_meta_author'   => Avada()->settings->get( 'post_meta_author' ),
			'post_meta_date'     => Avada()->settings->get( 'post_meta_date' ),
			'post_meta_cats'     => Avada()->settings->get( 'post_meta_cats' ),
			'post_meta_tags'     => Avada()->settings->get( 'post_meta_tags' ),
			'post_meta_comments' => Avada()->settings->get( 'post_meta_comments' ),
		);

		$settings = wp_parse_args( $settings, $default_settings );

		// Check if meta data is enabled
		if ( ( $settings['post_meta'] && 'no' != get_post_meta( get_queried_object_id(), 'pyre_post_meta', true ) ) || ( ! $settings['post_meta'] && 'yes' == get_post_meta( get_queried_object_id(), 'pyre_post_meta', true ) ) ) {
			// For alternate, grid and timeline layouts return empty single-line-meta if all meta data for that position is disabled
			if ( in_array( $layout, array( 'alternate', 'grid_timeline' ) ) && ! $settings['post_meta_author'] && ! $settings['post_meta_date'] && ! $settings['post_meta_cats'] && ! $settings['post_meta_tags'] && ! $settings['post_meta_comments'] ) {
				return '';
			}

			// Render author meta data
			if ( $settings['post_meta_author'] ) {
				ob_start();
				the_author_posts_link();
				$author_post_link = ob_get_clean();

				// Check if rich snippets are enabled
				if ( ! Avada()->settings->get( 'disable_date_rich_snippet_pages' ) ) {
					$metadata .= sprintf( esc_html__( 'By %s', 'Avada' ), '<span>' . $author_post_link . '</span>' );
				} else {
					$metadata .= sprintf( esc_html__( 'By %s', 'Avada' ), '<span class="vcard"><span class="fn">' . $author_post_link . '</span></span>' );
				}
				$metadata .= '<span class="fusion-inline-sep">|</span>';
			// If author meta data won't be visible, render just the invisible author rich snippet
			} else {
				$author .= avada_render_rich_snippets_for_pages( false, true, false );
			}

			// Render the updated meta data or at least the rich snippet if enabled
			if ( $settings['post_meta_date'] ) {
				$metadata .= avada_render_rich_snippets_for_pages( false, false, true );
				$metadata .= '<span>' . get_the_time( Avada()->settings->get( 'date_format' ) ) . '</span><span class="fusion-inline-sep">|</span>';
			} else {
				$date .= avada_render_rich_snippets_for_pages( false, false, true );
			}

			// Render rest of meta data
			// Render categories
			if ( $settings['post_meta_cats'] ) {
				ob_start();
				the_category( ', ' );
				$categories = ob_get_clean();

				if ( $categories ) {
					$metadata .= ( $settings['post_meta_tags'] ) ? sprintf( esc_html__( 'Categories: %s', 'Avada' ), $categories ) : $categories;
					$metadata .= '<span class="fusion-inline-sep">|</span>';
				}
			}

			// Render tags
			if ( $settings['post_meta_tags'] ) {
				ob_start();
				the_tags( '' );
				$tags = ob_get_clean();

				if ( $tags ) {
					$metadata .= '<span class="meta-tags">' . sprintf( esc_html__( 'Tags: %s', 'Avada' ), $tags ) . '</span><span class="fusion-inline-sep">|</span>';
				}
			}

			// Render comments
			if ( $settings['post_meta_comments'] && $layout != 'grid_timeline' ) {
				ob_start();
				comments_popup_link( esc_html__( '0 Comments', 'Avada' ), esc_html__( '1 Comment', 'Avada' ), esc_html__( '% Comments', 'Avada' ) );
				$comments = ob_get_clean();
				$metadata .= '<span class="fusion-comments">' . $comments . '</span>';
			}

			// Render the HTML wrappers for the different layouts
			if ( $metadata ) {
				$metadata = $author . $date . $metadata;

				if ( 'single' == $layout ) {
					$html .= '<div class="fusion-meta-info"><div class="fusion-meta-info-wrapper">' . $metadata . '</div></div>';
				} elseif ( in_array( $layout, array( 'alternate', 'grid_timeline' ) ) ) {
					$html .= '<p class="fusion-single-line-meta">' . $metadata . '</p>';
				} else {
					$html .= '<div class="fusion-alignleft">' . $metadata . '</div>';
				}
			} else {
				$html .= $author . $date;
			}
		// Render author and updated rich snippets for grid and timeline layouts
		} else {
			if ( Avada()->settings->get( 'disable_date_rich_snippet_pages' ) ) {
				$html .= avada_render_rich_snippets_for_pages( false );
			}
		}

		return $html;
	}
}

if ( ! function_exists( 'avada_render_social_sharing' ) ) {
	function avada_render_social_sharing( $post_type = 'post' ) {
		global $social_icons;

		$setting_name = ( 'post' == $post_type ) ? 'social_sharing_box' : $post_type . '_social_sharing_box';

		if ( ( Avada()->settings->get( $setting_name ) && 'no' != get_post_meta( get_the_ID(), 'pyre_share_box', true ) ) || ( ! Avada()->settings->get( $setting_name ) && 'yes' == get_post_meta( get_the_ID(), 'pyre_share_box', true ) ) ) {

			$full_image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );

			$sharingbox_soical_icon_options = array (
				'sharingbox'        => 'yes',
				'icon_colors'       => Avada()->settings->get( 'sharing_social_links_icon_color' ),
				'box_colors'        => Avada()->settings->get( 'sharing_social_links_box_color' ),
				'icon_boxed'        => Avada()->settings->get( 'sharing_social_links_boxed' ),
				'icon_boxed_radius' => Avada_Sanitize::size( Avada()->settings->get( 'sharing_social_links_boxed_radius' ) ),
				'tooltip_placement' => Avada()->settings->get( 'sharing_social_links_tooltip_placement' ),
				'linktarget'        => Avada()->settings->get( 'social_icons_new' ),
				'title'             => wp_strip_all_tags( get_the_title( get_the_ID() ), true ),
				'description'       => Avada()->blog->get_content_stripped_and_excerpted( 55, get_the_content() ),
				'link'              => get_permalink( get_the_ID() ),
				'pinterest_image'   => ( $full_image ) ? $full_image[0] : '',
			);
			?>
			<div class="fusion-sharing-box fusion-single-sharing-box share-box">
				<h4><?php echo apply_filters( 'fusion_sharing_box_tagline', Avada()->settings->get( 'sharing_social_tagline' ) ); ?></h4>
				<?php echo Avada()->social_sharing->render_social_icons( $sharingbox_soical_icon_options ); ?>
			</div>
			<?php
		}
	}
}

if ( ! function_exists( 'avada_render_related_posts' ) ) {
	/**
	 * Render related posts carousel
	 * @param  string $post_type         The post type to determine correct related posts and headings
	 *
	 * @return string                    HTML markup to display related posts
	 **/
	function avada_render_related_posts( $post_type = 'post' ) {

		$html = '';

		// Set the needed variables according to post type
		if ( $post_type == 'post' ) {
			$theme_option_name = 'related_posts';
			$main_heading      =  esc_html__( 'Related Posts', 'Avada' );
		} elseif ( $post_type == 'avada_portfolio' ) {
			$theme_option_name = 'portfolio_related_posts';
			$main_heading      =  esc_html__( 'Related Projects', 'Avada' );
		} elseif ( $post_type == 'avada_faq' ) {
			$theme_option_name = 'faq_related_posts';
			$main_heading      =  esc_html__( 'Related Faqs', 'Avada' );
		}

		// Check if related posts should be shown
		if ( ( isset( $theme_option_name ) && ( 'yes' == fusion_get_option( $theme_option_name, 'related_posts', get_the_ID() ) || '1' == fusion_get_option( $theme_option_name, 'related_posts', get_the_ID() ) ) ) ||
			 'faq_related_posts' == $theme_option_name
		) {
			$number_related_posts = Avada()->settings->get( 'number_related_posts' );
			$number_related_posts = ( '0' == $number_related_posts ) ? '-1' : $number_related_posts;
			if ( 'post' == $post_type ) {
				$related_posts = fusion_get_related_posts( get_the_ID(), $number_related_posts );
			} else {
				$related_posts = fusion_get_custom_posttype_related_posts( get_the_ID(), $number_related_posts, $post_type );
			}

			// If there are related posts, display them
			if ( isset( $related_posts ) && $related_posts->have_posts() ) {
				ob_start();
				include( locate_template( 'templates/related-posts.php' ) );
				$html .= ob_get_clean();
			}
		}

		return $html;
	}
}


if ( ! function_exists( 'avada_render_rich_snippets_for_pages' ) ) {
	/**
	 * Render the full meta data for blog archive and single layouts
	 * @param  boolean $title_tag       Set to true to render title rich snippet
	 * @param  boolean $author_tag      Set to true to render author rich snippet
	 * @param  boolean $updated_tag     Set to true to render updated rich snippet
	 *
	 * @return string                   HTML markup to display rich snippets
	 **/
	function avada_render_rich_snippets_for_pages( $title_tag = true, $author_tag = true, $updated_tag = true ) {
		ob_start();
		include( locate_template( 'templates/pages-rich-snippets.php' ) );
		return ob_get_clean();
	}
}

if ( ! function_exists( 'avada_extract_shortcode_contents' ) ) {
	/**
	 * Extract text contents from all shortcodes for usage in excerpts
	 *
	 * @return string The shortcode contents
	 **/
	function avada_extract_shortcode_contents( $m ) {

		global $shortcode_tags;

		// Setup the array of all registered shortcodes
		$shortcodes = array_keys( $shortcode_tags );
		$no_space_shortcodes = array( 'dropcap' );
		$omitted_shortcodes  = array( 'fusion_code', 'slide' );

		// Extract contents from all shortcodes recursively
		if ( in_array( $m[2], $shortcodes ) && ! in_array( $m[2], $omitted_shortcodes ) ) {
			$pattern = get_shortcode_regex();
			// Add space the excerpt by shortcode, except for those who should stick together, like dropcap
			$space = ' ' ;
			if ( in_array( $m[2], $no_space_shortcodes ) ) {
				$space = '' ;
			}
			$content = preg_replace_callback( "/$pattern/s", 'avada_extract_shortcode_contents', rtrim( $m[5] ) . $space );
			return $content;
		}

		// allow [[foo]] syntax for escaping a tag
		if ( $m[1] == '[' && $m[6] == ']' ) {
			return substr( $m[0], 1, -1 );
		}

	   return $m[1] . $m[6];
	}
}

if ( ! function_exists( 'avada_page_title_bar' ) ) {
	/**
	 * Render the HTML markup of the page title bar
	 * @param  string $title                Main title; page/post title or custom title set by user
	 * @param  string $subtitle             Subtitle as custom user setting
	 * @param  string $secondary_content    HTML markup of the secondary content; breadcrumbs or search field
	 *
	 * @return void                         Content is directly echoed
	 **/
	function avada_page_title_bar( $title, $subtitle, $secondary_content ) {
		$post_id = get_queried_object_id();

		// Check for the secondary content
		$content_type = 'none';
		if ( false !== strpos( $secondary_content, 'searchform' ) ) {
			$content_type = 'search';
		} elseif ( '' != $secondary_content ) {
			$content_type = 'breadcrumbs';
		}

		// Check the position of page title
		if ( metadata_exists( 'post', $post_id, 'pyre_page_title_text_alignment' ) && 'default' != get_post_meta( get_queried_object_id(), 'pyre_page_title_text_alignment', true ) ) {
			$alignment = get_post_meta( $post_id, 'pyre_page_title_text_alignment', true );
		} elseif ( Avada()->settings->get( 'page_title_alignment' ) ) {
			$alignment = Avada()->settings->get( 'page_title_alignment' );
		}

		/**
		 * Render the page title bar
		 */
		include( locate_template( 'templates/title-bar.php' ) );
	}
}

/**
 * Add woocommerce cart to main navigation or top navigation
 * @param  string HTML for the main menu items
 * @param  args   Arguments for the WP menu
 * @return string
 */
if ( ! function_exists( 'avada_add_login_box_to_nav' ) ) {
	function avada_add_login_box_to_nav( $items, $args ) {

		$ubermenu = ( function_exists( 'ubermenu_get_menu_instance_by_theme_location' ) && ubermenu_get_menu_instance_by_theme_location( $args->theme_location ) ) ? true : false; // disable woo cart on ubermenu navigations

		if ( ! $ubermenu ) {
			if ( in_array( $args->theme_location, array( 'main_navigation', 'top_navigation', 'sticky_navigation' ) ) ) {
				$is_enabled = ( $args->theme_location == 'top_navigation' ) ? Avada()->settings->get( 'woocommerce_acc_link_top_nav' ) : Avada()->settings->get( 'woocommerce_acc_link_main_nav' );

				if ( class_exists( 'WooCommerce' ) && $is_enabled ) {
					$woo_account_page_link = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
					$logout_link = wp_logout_url( get_permalink( woocommerce_get_page_id( 'myaccount' ) ) );

					if ( $woo_account_page_link ) {
						$items .= '<li class="fusion-custom-menu-item fusion-menu-login-box">';
							// If chosen in Theme Options, display the caret icon, as the my account item alyways has a dropdown
							$caret_icon = '';
							if ( Avada()->settings->get( 'menu_display_dropdown_indicator' ) && 'v6' != Avada()->settings->get( 'header_layout' ) ) {
								$caret_icon = '<span class="fusion-caret"><i class="fusion-dropdown-indicator"></i></span>';
							}

							$my_account_link_contents = ( 'Right' == Avada()->settings->get( 'header_position' ) ) ? $caret_icon . esc_html__( 'My Account', 'Avada' ) : esc_html__( 'My Account', 'Avada' ) . $caret_icon;

							$items .= '<a href="' . $woo_account_page_link . '"><span class="menu-text">' . $my_account_link_contents . '</span></a>';

							if ( ! is_user_logged_in() ) {
								$items .= '<div class="fusion-custom-menu-item-contents">';
									if ( isset( $_GET['login'] ) && 'failed' == $_GET['login'] ) {
										$items .= '<p class="fusion-menu-login-box-error">' . esc_html__( 'Login failed, please try again.', 'Avada' ) . '</p>';
									}
									$items .= '<form action="' . wp_login_url() . '" name="loginform" method="post">';
										$items .= '<p><input type="text" class="input-text" name="log" id="username" value="" placeholder="' . esc_html__( 'Username', 'Avada' ) . '" /></p>';
										$items .= '<p><input type="password" class="input-text" name="pwd" id="password" value="" placeholder="' . esc_html__( 'Password', 'Avada' ) . '" /></p>';
										$items .= '<p class="fusion-remember-checkbox"><label for="fusion-menu-login-box-rememberme"><input name="rememberme" type="checkbox" id="fusion-menu-login-box-rememberme" value="forever"> ' . esc_html__( 'Remember Me', 'Avada' ) . '</label></p>';
										$items .= '<input type="hidden" name="fusion_woo_login_box" value="true" />';
										$items .= '<p class="fusion-login-box-submit">';
											$items .= '<input type="submit" name="wp-submit" id="wp-submit" class="button small default comment-submit" value="' . esc_html__( 'Log In', 'Avada' ) . '">';
											$items .= '<input type="hidden" name="redirect" value="' . esc_url( ( isset( $_SERVER['HTTP_REFERER'] ) ) ? $_SERVER['HTTP_REFERER'] : $_SERVER['REQUEST_URI'] ) . '">';
										$items .= '</p>';
									$items .= '</form>';
								$items .= '</div>';
							} else {
								$items .= '<ul class="sub-menu">';
									$items .= '<li><a href="' . $logout_link . '">' . esc_html__( 'Logout', 'Avada' ) . '</a></li>';
								$items .= '</ul>';
							}
						$items .= '</li>';
					}
				}
			}
		}

		return $items;
	}
}
add_filter( 'wp_nav_menu_items', 'avada_add_login_box_to_nav', 10, 3 );

if ( ! function_exists( 'avada_nav_woo_cart' ) ) {
	/**
	 * Woo Cart Dropdown for Main Nav or Top Nav
	 *
	 * @return string HTML of Dropdown
	 */
	function avada_nav_woo_cart( $position = 'main' ) {
		$items = '';

		if ( class_exists( 'WooCommerce' ) ) {
			global $woocommerce;
			$woo_cart_page_link = get_permalink( get_option( 'woocommerce_cart_page_id' ) );

			$cart_link_active_class   = '';
			$cart_link_active_text    = '';
			$is_enabled               = false;
			$main_cart_class          = '';
			$cart_link_inactive_class = '';
			$cart_link_inactive_text  = '';
			if ( 'main' == $position ) {
				$is_enabled             = Avada()->settings->get( 'woocommerce_cart_link_main_nav' );
				$main_cart_class        = 'fusion-main-menu-cart';
				$cart_link_active_class = 'fusion-main-menu-icon fusion-main-menu-icon-active';

				if ( Avada()->settings->get( 'woocommerce_cart_counter') ) {
					$cart_link_active_text = '<span class="fusion-widget-cart-number">' . $woocommerce->cart->get_cart_contents_count() . '</span>';
					$main_cart_class      .= ' fusion-widget-cart-counter';
				}

				if ( ! Avada()->settings->get( 'woocommerce_cart_counter') && $woocommerce->cart->get_cart_contents_count() ) {
					$main_cart_class .= ' fusion-active-cart-icons';
				}

				$cart_link_inactive_class = 'fusion-main-menu-icon';
				$cart_link_inactive_text  = '';

			} else if ( 'secondary' == $position ) {
				$is_enabled               = Avada()->settings->get( 'woocommerce_cart_link_top_nav' );
				$main_cart_class          = 'fusion-secondary-menu-cart';
				$cart_link_active_class   = 'fusion-secondary-menu-icon';
				$cart_link_active_text    = sprintf( esc_html__( '%s Item(s)', 'Avada' ), $woocommerce->cart->get_cart_contents_count() ) . ' <span class="fusion-woo-cart-separator">-</span> ' . wc_price( $woocommerce->cart->subtotal );
				$cart_link_inactive_class = $cart_link_active_class;
				$cart_link_inactive_text  = esc_html__( 'Cart', 'Avada' );
			}

			$cart_link_markup = '<a class="' . $cart_link_active_class . '" href="' . $woo_cart_page_link . '"><span class="menu-text">' . $cart_link_active_text . '</span></a>';

			if (  $is_enabled ) {

				$items = '<li class="fusion-custom-menu-item fusion-menu-cart ' . $main_cart_class . '">';
					if ( $woocommerce->cart->get_cart_contents_count() ) {
						$checkout_link = get_permalink( get_option('woocommerce_checkout_page_id') );

						$items .= $cart_link_markup;

						$items .= '<div class="fusion-custom-menu-item-contents fusion-menu-cart-items">';
							foreach( $woocommerce->cart->cart_contents as $cart_item ) {
								$product_link = get_permalink( $cart_item['product_id'] );
								$thumbnail_id = ( $cart_item['variation_id'] && has_post_thumbnail( $cart_item['variation_id'] ) ) ? $cart_item['variation_id'] : $cart_item['product_id'];
								$items .= '<div class="fusion-menu-cart-item">';
									$items .= '<a href="' . $product_link . '">';
										$items .= get_the_post_thumbnail( $thumbnail_id, 'recent-works-thumbnail' );
										$items .= '<div class="fusion-menu-cart-item-details">';
											$items .= '<span class="fusion-menu-cart-item-title">' . $cart_item['data']->post->post_title . '</span>';
											$items .= '<span class="fusion-menu-cart-item-quantity">' . $cart_item['quantity'] . ' x ' . $woocommerce->cart->get_product_subtotal( $cart_item['data'], 1 ) . '</span>';
										$items .= '</div>';
									$items .= '</a>';
								$items .= '</div>';
							}
							$items .= '<div class="fusion-menu-cart-checkout">';
								$items .= '<div class="fusion-menu-cart-link"><a href="' . $woo_cart_page_link . '">' . esc_html__( 'View Cart', 'Avada' ) . '</a></div>';
								$items .= '<div class="fusion-menu-cart-checkout-link"><a href="' . $checkout_link . '">' . esc_html__('Checkout', 'Avada') . '</a></div>';
							$items .= '</div>';
						$items .= '</div>';
					} else {
						$items .= '<a class="' . $cart_link_inactive_class . '" href="' . $woo_cart_page_link . '"><span class="menu-text">' . $cart_link_inactive_text . '</span></a>';
					}
				$items .= '</li>';
			}
		}

		return $items;
	}
}

if ( ! function_exists( 'fusion_add_woo_cart_to_widget_html' ) ) {
	function fusion_add_woo_cart_to_widget_html() {
		global $woocommerce;
		$items = '';

		if ( class_exists( 'WooCommerce') ) {
			$counter = '';
			$class   = '';
			$items   = '';

			if ( Avada()->settings->get( 'woocommerce_cart_counter') ) {
				$counter = '<span class="fusion-widget-cart-number">' . $woocommerce->cart->get_cart_contents_count() . '</span>';
				$class   = 'fusion-widget-cart-counter';
			}

			if ( ! Avada()->settings->get( 'woocommerce_cart_counter') && $woocommerce->cart->get_cart_contents_count() ) {
				$class .= ' fusion-active-cart-icon';
			}

			$items .= '<li class="fusion-widget-cart ' . $class .'"><a href="' . get_permalink( get_option( 'woocommerce_cart_page_id' ) ) . '" class=""><span class="fusion-widget-cart-icon"></span>' . $counter . '</a></li>';
		}

		return $items;
	}
}

/**
 * Add woocommerce cart to main navigation or top navigation
 * @param  string HTML for the main menu items
 * @param  args   Arguments for the WP menu
 * @return string
 */
if ( ! function_exists( 'avada_add_woo_cart_to_nav' ) ) {
	function avada_add_woo_cart_to_nav( $items, $args ) {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return $items;
		}
		global $woocommerce;

		$ubermenu = false;

		if ( function_exists( 'ubermenu_get_menu_instance_by_theme_location' ) && ubermenu_get_menu_instance_by_theme_location( $args->theme_location ) ) {
			// disable woo cart on ubermenu navigations
			$ubermenu = true;
		}

		if ( Avada()->settings->get( 'header_layout' ) != 'v6' ) {
			if ( $ubermenu == false && $args->theme_location == 'main_navigation' || $args->theme_location == 'sticky_navigation' ) {
				$items .= avada_nav_woo_cart( 'main' );
			} else if ( $ubermenu == false && $args->theme_location == 'top_navigation' ) {
				$items .= avada_nav_woo_cart( 'secondary' );
			}
		}

		return $items;
	}
}
add_filter( 'wp_nav_menu_items', 'avada_add_woo_cart_to_nav', 10, 3 );

/**
 * Add search to the main navigation
 * @param  string HTML for the main menu items
 * @param  args   Arguments for the WP menu
 * @return string
 */
if ( ! function_exists( 'avada_add_search_to_main_nav' ) ) {
	function avada_add_search_to_main_nav( $items, $args ) {
		$ubermenu = false;

		if ( function_exists( 'ubermenu_get_menu_instance_by_theme_location' ) && ubermenu_get_menu_instance_by_theme_location( $args->theme_location ) ) {
			// disable woo cart on ubermenu navigations
			$ubermenu = true;
		}

		if ( Avada()->settings->get( 'header_layout' ) != 'v6' && false == $ubermenu ) {
			if ( 'main_navigation' == $args->theme_location || 'sticky_navigation' == $args->theme_location ) {
				if ( Avada()->settings->get( 'main_nav_search_icon' ) ) {
					$items .= '<li class="fusion-custom-menu-item fusion-main-menu-search">';
						$items .= '<a class="fusion-main-menu-icon"></a>';
						$items .= '<div class="fusion-custom-menu-item-contents">';
							$items .= get_search_form( false );
						$items .= '</div>';
					$items .= '</li>';
				}
			}
		}

		return $items;
	}
}
add_filter( 'wp_nav_menu_items', 'avada_add_search_to_main_nav', 20, 4 );

if ( ! function_exists( 'avada_update_featured_content_for_split_terms' ) ) {
	function avada_update_featured_content_for_split_terms( $old_term_id, $new_term_id, $term_taxonomy_id, $taxonomy ) {
		if ( 'portfolio_category' == $taxonomy ) {
			$pages = get_pages();

			if ( $pages ) {
				foreach( $pages as $page ) {
					$page_id        = $page->ID;
					$categories     = get_post_meta( $page_id, 'pyre_portfolio_category', true );
					$new_categories = array();
					if ( $categories ) {
						foreach( $categories as $category ) {
							if ( '0' != $category ) {
								$new_categories[] = ( isset( $category ) && $old_term_id == $category ) ? $new_term_id : $category;
							} else {
								$new_categories[] = '0';
							}
						}

						update_post_meta( $page_id, 'pyre_portfolio_category', $new_categories );
					}
				}
			}
		}
	}
}
add_action( 'split_shared_term', 'avada_update_featured_content_for_split_terms', 10, 4 );

/**
 * Perform a HTTP HEAD or GET request.
 *
 * If $file_path is a writable filename, this will do a GET request and write
 * the file to that path.
 *
 * This is a re-implementation of the deprecated wp_get_http() function from WP Core,
 * but this time using the recommended WP_Http() class and the WordPress filesystem.
 *
 * @param string      $url       URL to fetch.
 * @param string|bool $file_path Optional. File path to write request to. Default false.
 * @return bool|string False on failure and string of headers if HEAD request.
 */
function avada_wp_get_http( $url = false, $file_path = false ) {

	// No need to proceed if we don't have a $url or a $file_path
	if ( ! $url || ! $file_path ) {
		return false;
	}
	// Make sure we normalize $file_path
	$file_path = wp_normalize_path( $file_path );
	// Include the WP_Http class if it doesn't already exist
	if ( ! class_exists( 'WP_Http' ) ) {
		include_once( wp_normalize_path( ABSPATH . WPINC. '/class-http.php' ) );
	}
	// Instantiate the WP_Http object
	$wp_http = new WP_Http();
	// Get the body of our requested URL.
	$body = false;
	$request = $wp_http->request( $url );
	if ( $request && isset( $request['response'] ) ) {
		if ( isset( $request['response']['body'] ) ) {
			$body = $request['response']['body'];
		} elseif ( isset( $request['body'] ) ) {
			$body = $request['body'];
		}
	}
	// If the body of our request was not found, then return false.
	if ( false === $body ) {
		return false;
	}
	// Initialize the Wordpress filesystem.
	global $wp_filesystem;
	if ( empty( $wp_filesystem ) ) {
		require_once( ABSPATH . '/wp-admin/includes/file.php' );
		WP_Filesystem();
	}
	// Attempt to write the file
	if ( ! $wp_filesystem->put_contents( $file_path, $body, FS_CHMOD_FILE ) ) {
		// If the attempt to write to the file failed, then return false
		return false;
	}

	// If all went well, then return the headers of the request
	if ( isset( $request['headers'] ) ) {
		$request['headers']['response'] = $request['response']['code'];
		return $request['headers'];
	}
	// If all else fails, then return false.
	return false;
}

// Omit closing PHP tag to avoid "Headers already sent" issues.

add_action( 'wp_head', 'avada_set_post_views' );
if ( ! function_exists( 'avada_set_post_views' ) ) {
	function avada_set_post_views() {
		global $post;
		if ( 'post' == get_post_type() && is_single() ) {
			$postID = $post->ID;
			if ( ! empty( $postID ) ) {
				$count_key = 'avada_post_views_count';
				$count     = get_post_meta( $postID, $count_key, true );
				if ( '' == $count ) {
					$count = 0;
					delete_post_meta( $postID, $count_key );
					add_post_meta( $postID, $count_key, '0' );
				} else {
					$count++;
					update_post_meta( $postID, $count_key, $count );
				}
			}
		}
	}
}

if ( ! function_exists( 'avada_get_slider' ) ) {
	function avada_get_slider( $post_id, $type ) {
		$type = Avada_Helper::slider_name( $type );
		return ( $type ) ?get_post_meta( $post_id, 'pyre_' . $type, true ) : false;
	}
}

if ( ! function_exists( 'avada_slider' ) ) {
	function avada_slider( $post_id ) {
		$slider_type = avada_get_slider_type( $post_id );
		$slider      = avada_get_slider( $post_id, $slider_type );

		if ( $slider ) {
			$slider_name = Avada_Helper::slider_name( $slider_type );
			$slider_name = ( 'slider' == $slider_name ) ? 'layerslider' : $slider_name;

			$function = 'avada_' . $slider_name;

			$function( $slider );
		}
	}
}

if ( ! function_exists( 'avada_revslider' ) ) {
	function avada_revslider( $name ) {
		if ( function_exists('putRevSlider') ) {
			putRevSlider( $name );
		}
	}
}

if ( ! function_exists( 'avada_layerslider' ) ) {
	function avada_layerslider( $id ) {
		global $wpdb;

		// Get slider
		$ls_table_name = $wpdb->prefix . "layerslider";
		$ls_slider     = $wpdb->get_row( "SELECT * FROM $ls_table_name WHERE id = " . (int) $id . " ORDER BY date_c DESC LIMIT 1" , ARRAY_A );
		$ls_slider     = json_decode( $ls_slider['data'], true );
		?>
		<style type="text/css">
			#layerslider-container{max-width:<?php echo $ls_slider['properties']['width'] ?>;}
		</style>
		<div id="layerslider-container">
			<div id="layerslider-wrapper">
				<?php if ( 'avada' == $ls_slider['properties']['skin'] ) : ?>
					<div class="ls-shadow-top"></div>
				<?php endif; ?>
				<?php echo do_shortcode( '[layerslider id="' . $id . '"]' ); ?>
				<?php if ( 'avada' == $ls_slider['properties']['skin'] ) : ?>
					<div class="ls-shadow-bottom"></div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'avada_elasticslider' ) ) {
	function avada_elasticslider( $term ) {

		if ( Avada()->settings->get( 'status_eslider' ) ) {
			$args				= array(
				'post_type'        => 'themefusion_elastic',
				'posts_per_page'   => -1,
				'suppress_filters' => 0
			);
			$args['tax_query'][] = array(
				'taxonomy' => 'themefusion_es_groups',
				'field'    => 'slug',
				'terms'    => $term
			);
			$query = new WP_Query( $args );
			$count = 1;
			?>

			<?php if ( $query->have_posts() ) : ?>
				<div id="ei-slider" class="ei-slider">
					<div class="fusion-slider-loading"><?php _e( 'Loading...', 'Avada' ); ?></div>
					<ul class="ei-slider-large">
						<?php while ( $query->have_posts() ) : $query->the_post(); ?>
							<li style="<?php echo ( $count > 0 ) ? 'opacity: 0;' : ''; ?>">
								<?php the_post_thumbnail( 'full', array( 'title' => '', 'alt' => get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ) ); ?>
								<div class="ei-title">
									<?php if ( get_post_meta( get_the_ID(), 'pyre_caption_1', true ) ): ?>
										<h2><?php echo get_post_meta( get_the_ID(), 'pyre_caption_1', true ); ?></h2>
									<?php endif; ?>
									<?php if ( get_post_meta( get_the_ID(), 'pyre_caption_2', true ) ): ?>
										<h3><?php echo get_post_meta( get_the_ID(), 'pyre_caption_2', true ); ?></h3>
									<?php endif; ?>
								</div>
							</li>
							<?php $count ++; ?>
						<?php endwhile; ?>
					</ul>
					<ul class="ei-slider-thumbs" style="display: none;">
						<li class="ei-slider-element">Current</li>
						<?php while ( $query->have_posts() ) : $query->the_post(); ?>
							<li>
								<a href="#"><?php the_title(); ?></a>
								<?php the_post_thumbnail( 'full', array( 'title' => '', 'alt' => get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ) ); ?>
							</li>
						<?php endwhile; ?>
					</ul>
				</div>
				<?php wp_reset_postdata(); ?>
			<?php endif; ?>
			<?php wp_reset_query();
		}
	}
}

if ( ! function_exists( 'avada_wooslider' ) ) {
	function avada_wooslider( $term ) {

		if ( Avada()->settings->get( 'status_fusion_slider' ) ) {
			$term_details = get_term_by( 'slug', $term, 'slide-page' );
			$slider_settings = array();

			if ( is_object( $term_details ) ) {
				$slider_settings = get_option( 'taxonomy_' . $term_details->term_id );
			}

			if ( ! isset( $slider_settings['typo_sensitivity'] ) ) {
				$slider_settings['typo_sensitivity'] = '0.6';
			}

			if ( ! isset( $slider_settings['typo_factor'] ) ) {
				$slider_settings['typo_factor'] = '1.5';
			}


			if ( ! isset( $slider_settings['slider_width'] ) || '' == $slider_settings['slider_width'] ) {
				$slider_settings['slider_width'] = '100%';
			}

			if ( ! isset( $slider_settings['slider_height'] ) || '' == $slider_settings['slider_height'] ) {
				$slider_settings['slider_height'] = '500px';
			}

			if ( ! isset( $slider_settings['full_screen'] ) ) {
				$slider_settings['full_screen'] = false;
			}

			if ( ! isset( $slider_settings['animation'] ) ) {
				$slider_settings['animation'] = true;
			}

			if ( ! isset( $slider_settings['nav_box_width'] ) ) {
				$slider_settings['nav_box_width'] = '63px';
			}

			if ( ! isset( $slider_settings['nav_box_height'] ) ) {
				$slider_settings['nav_box_height'] = '63px';
			}

			if ( ! isset( $slider_settings['nav_arrow_size'] ) ) {
				$slider_settings['nav_arrow_size'] = '25px';
			}

			$nav_box_height_half = '0';
			if ( $slider_settings['nav_box_height'] ) {
				$nav_box_height_half = intval( $slider_settings['nav_box_height'] ) / 2;
			}

			$slider_data = '';

			if ( $slider_settings ) {
				foreach( $slider_settings as $slider_setting => $slider_setting_value ) {
					$slider_data .= 'data-' . $slider_setting . '="' . $slider_setting_value . '" ';
				}
			}

			$slider_class = '';

			if ( '100%' == $slider_settings['slider_width'] && ! $slider_settings['full_screen'] ) {
				$slider_class .= ' full-width-slider';
			} elseif ( '100%' != $slider_settings['slider_width'] && ! $slider_settings['full_screen'] ) {
				$slider_class .= ' fixed-width-slider';
			}

			if ( isset( $slider_settings['slider_content_width'] ) && '' != $slider_settings['slider_content_width'] ) {
				$content_max_width = 'max-width:' . $slider_settings['slider_content_width'];
			} else {
				$content_max_width = '';
			}

			$args = array(
				'post_type'        => 'slide',
				'posts_per_page'   => -1,
				'suppress_filters' => 0
			);
			$args['tax_query'][] = array(
				'taxonomy' => 'slide-page',
				'field'    => 'slug',
				'terms'    => $term
			);

			$query = new WP_Query( $args );
			?>

			<?php if ( $query->have_posts() ) : ?>

				<?php $max_width = ( 'fade' == $slider_settings['animation'] ) ? 'max-width:' . $slider_settings['slider_width'] : ''; ?>

				<div class="fusion-slider-container fusion-slider-<?php the_ID(); ?> <?php echo $slider_class; ?>-container" style="height:<?php echo $slider_settings['slider_height']; ?>;max-width:<?php echo $slider_settings['slider_width']; ?>;">
					<style type="text/css" scoped="scoped">
					.fusion-slider-<?php the_ID(); ?> .flex-direction-nav a {
						<?php
						if ( $slider_settings['nav_box_width'] ) {
							echo 'width:' . $slider_settings['nav_box_width'] . ';';
						}
						if ( $slider_settings['nav_box_height'] ) {
							echo 'height:' . $slider_settings['nav_box_height'] . ';';
							echo 'line-height:' . $slider_settings['nav_box_height'] . ';';
							echo 'margin-top:-' . $nav_box_height_half . 'px;';
						}
						if ( $slider_settings['nav_arrow_size'] ) {
							echo 'font-size:' . $slider_settings['nav_arrow_size'] . ';';
						}
						?>
					}
					</style>
					<div class="fusion-slider-loading"><?php _e( 'Loading...', 'Avada' ); ?></div>
					<div class="tfs-slider flexslider main-flex<?php echo $slider_class; ?>" style="max-width:<?php echo $slider_settings['slider_width']; ?>;" <?php echo $slider_data; ?>>
						<ul class="slides" style="<?php echo $max_width ?>;">
							<?php while ( $query->have_posts() ) : $query->the_post(); ?>
								<?php
								$metadata = get_metadata( 'post', get_the_ID() );
								$background_image = '';
								$background_class = '';

								$img_width = '';
								$image_url = array( '', '' );

								if ( isset( $metadata['pyre_type'][0] ) && 'image' == $metadata['pyre_type'][0] && has_post_thumbnail() ) {
									$image_id         = get_post_thumbnail_id();
									$image_url        = wp_get_attachment_image_src( $image_id, 'full', true );
									$background_image = 'background-image: url(' . $image_url[0] . ');';
									$background_class = 'background-image';
									$img_width        = $image_url[1];
								}

								$aspect_ratio 		= '16:9';
								$video_attributes   = '';
								$youtube_attributes = '';
								$vimeo_attributes   = '';
								$data_mute          = 'no';
								$data_loop          = 'no';
								$data_autoplay      = 'no';

								if ( isset( $metadata['pyre_aspect_ratio'][0] ) && $metadata['pyre_aspect_ratio'][0] ) {
									$aspect_ratio = $metadata['pyre_aspect_ratio'][0];
								}

								if ( isset( $metadata['pyre_mute_video'][0] ) && 'yes' == $metadata['pyre_mute_video'][0] ) {
									$video_attributes = 'muted';
									$data_mute        = 'yes';
								}

								// Do not set the &auoplay=1 attributes, as this is done in js to make sure the page is fully loaded before the video begins to play
								if ( isset( $metadata['pyre_autoplay_video'][0] ) && 'yes' == $metadata['pyre_autoplay_video'][0] ) {
									$video_attributes   .= ' autoplay';
									$data_autoplay       = 'yes';
								}

								if ( isset( $metadata['pyre_loop_video'][0] ) && 'yes' == $metadata['pyre_loop_video'][0] ) {
									$video_attributes   .= ' loop';
									$youtube_attributes .= '&amp;loop=1&amp;playlist=' . $metadata['pyre_youtube_id'][0];
									$vimeo_attributes   .= '&amp;loop=1';
									$data_loop           = 'yes';
								}

								if ( isset( $metadata['pyre_hide_video_controls'][0] ) && 'no' == $metadata['pyre_hide_video_controls'][0] ) {
									$video_attributes   .= ' controls';
									$youtube_attributes .= '&amp;controls=1';
									$video_zindex        = 'z-index: 1;';
								} else {
									$youtube_attributes .= '&amp;controls=0';
									$video_zindex        = 'z-index: -99;';
								}

								$heading_color = 'color:#fff;';

								if ( isset( $metadata['pyre_heading_color'][0] ) && $metadata['pyre_heading_color'][0] ) {
									$heading_color = 'color:' . $metadata['pyre_heading_color'][0] . ';';
								}

								$heading_bg = '';

								if ( isset( $metadata['pyre_heading_bg'][0] ) && 'yes' == $metadata['pyre_heading_bg'][0] ) {
									$heading_bg = 'background-color: rgba(0,0,0, 0.4);';
									if ( isset( $metadata['pyre_heading_bg_color'][0] ) && '' != $metadata['pyre_heading_bg_color'][0] ) {
										$rgb        = fusion_hex2rgb( $metadata['pyre_heading_bg_color'][0] );
										$heading_bg = sprintf( 'background-color: rgba(%s,%s,%s,%s);', $rgb[0], $rgb[1], $rgb[2], 0.4 );
									}
								}

								$caption_color = 'color:#fff;';

								if ( isset( $metadata['pyre_caption_color'][0] ) && $metadata['pyre_caption_color'][0] ) {
									$caption_color = 'color:' . $metadata['pyre_caption_color'][0] . ';';
								}

								$caption_bg = '';

								if ( isset( $metadata['pyre_caption_bg'][0] ) && 'yes' == $metadata['pyre_caption_bg'][0] ) {
									$caption_bg = 'background-color: rgba(0, 0, 0, 0.4);';

									if ( isset( $metadata['pyre_caption_bg_color'][0] ) && '' != $metadata['pyre_caption_bg_color'][0] ) {
										$rgb        = fusion_hex2rgb( $metadata['pyre_caption_bg_color'][0] );
										$caption_bg = sprintf( 'background-color: rgba(%s,%s,%s,%s);', $rgb[0], $rgb[1], $rgb[2], 0.4 );
									}
								}

								$video_bg_color = '';

								if ( isset( $metadata['pyre_video_bg_color'][0] ) && $metadata['pyre_video_bg_color'][0] ) {
									$video_bg_color_hex = fusion_hex2rgb( $metadata['pyre_video_bg_color'][0]  );
									$video_bg_color     = 'background-color: rgba(' . $video_bg_color_hex[0] . ', ' . $video_bg_color_hex[1] . ', ' . $video_bg_color_hex[2] . ', 0.4);';
								}

								$video = false;

								if ( isset( $metadata['pyre_type'][0] ) ) {
									if ( isset( $metadata['pyre_type'][0] ) && in_array( $metadata['pyre_type'][0], array( 'self-hosted-video', 'youtube', 'vimeo' ) ) ) {
										$video = true;
									}
								}

								if ( isset( $metadata['pyre_type'][0] ) &&  $metadata['pyre_type'][0] == 'self-hosted-video' ) {
									$background_class = 'self-hosted-video-bg';
								}

								$heading_font_size = 'font-size:60px;line-height:80px;';
								if ( isset( $metadata['pyre_heading_font_size'][0] ) && $metadata['pyre_heading_font_size'][0] ) {
									$line_height       = $metadata['pyre_heading_font_size'][0] * 1.2;
									$heading_font_size = 'font-size:' . $metadata['pyre_heading_font_size'][0] . 'px;line-height:' . $line_height . 'px;';
								}

								$caption_font_size = 'font-size: 24px;line-height:38px;';
								if ( isset( $metadata['pyre_caption_font_size'][0] ) && $metadata['pyre_caption_font_size'][0] ) {
									$line_height       = $metadata['pyre_caption_font_size'][0] * 1.2;
									$caption_font_size = 'font-size:' . $metadata['pyre_caption_font_size'][0] . 'px;line-height:' . $line_height . 'px;';
								}

								$heading_styles = $heading_color . $heading_font_size;
								$caption_styles = $caption_color . $caption_font_size;
								$heading_title_sc_wrapper_class = '';
								$caption_title_sc_wrapper_class = '';

								if ( ! isset( $metadata['pyre_heading_separator'][0] ) ) {
									$metadata['pyre_heading_separator'][0] = 'none';
								}

								if ( ! isset( $metadata['pyre_caption_separator'][0] ) ) {
									$metadata['pyre_caption_separator'][0] = 'none';
								}

								if ( $metadata['pyre_content_alignment'][0] != 'center' ) {
									$metadata['pyre_heading_separator'][0] = 'none';
									$metadata['pyre_caption_separator'][0] = 'none';
								}

								if ( $metadata['pyre_content_alignment'][0] == 'center' ) {
									if ( $metadata['pyre_heading_separator'][0] != 'none' ) {
										$heading_title_sc_wrapper_class = ' fusion-block-element';
									}

									if ( $metadata['pyre_caption_separator'][0] != 'none' ) {
										$caption_title_sc_wrapper_class = ' fusion-block-element';
									}
								}
								?>
								<li data-mute="<?php echo $data_mute; ?>" data-loop="<?php echo $data_loop; ?>" data-autoplay="<?php echo $data_autoplay; ?>">
									<div class="slide-content-container slide-content-<?php if ( isset( $metadata['pyre_content_alignment'][0] ) && $metadata['pyre_content_alignment'][0] ) { echo $metadata['pyre_content_alignment'][0]; } ?>" style="display: none;">
										<div class="slide-content" style="<?php echo $content_max_width; ?>">
											<?php if ( isset( $metadata['pyre_heading'][0] ) && $metadata['pyre_heading'][0] ) : ?>
												<div class="heading <?php echo ( $heading_bg ) ? 'with-bg' : ''; ?>">
													<div class="fusion-title-sc-wrapper<?php echo $heading_title_sc_wrapper_class; ?>" style="<?php echo $heading_bg; ?>">
														<?php echo do_shortcode( sprintf( '[title size="2" content_align="%s" sep_color="%s" margin_top="0px" margin_bottom="0px" style_type="%s" style_tag="%s"]%s[/title]',  $metadata['pyre_content_alignment'][0], $metadata['pyre_heading_color'][0], $metadata['pyre_heading_separator'][0], $heading_styles, do_shortcode( $metadata['pyre_heading'][0] ) ) ); ?>
													</div>
												</div>
											<?php endif; ?>
											<?php if ( isset( $metadata['pyre_caption'][0] ) && $metadata['pyre_caption'][0] ) : ?>
												<div class="caption <?php echo ( $caption_bg ) ? 'with-bg' : ''; ?>">
													<div class="fusion-title-sc-wrapper<?php echo $caption_title_sc_wrapper_class; ?>" style="<?php echo $caption_bg; ?>">
														<?php echo do_shortcode( sprintf( '[title size="3" content_align="%s" sep_color="%s" margin_top="0px" margin_bottom="0px" style_type="%s" style_tag="%s"]%s[/title]',  $metadata['pyre_content_alignment'][0], $metadata['pyre_caption_color'][0], $metadata['pyre_caption_separator'][0], $caption_styles, do_shortcode( $metadata['pyre_caption'][0] ) ) ); ?>
													</div>
												</div>
											<?php endif; ?>
											<?php if ( isset( $metadata['pyre_link_type'][0] ) && 'button' == $metadata['pyre_link_type'][0] ) : ?>
												<div class="buttons" >
													<?php if ( isset( $metadata['pyre_button_1'][0] ) && $metadata['pyre_button_1'][0] ) : ?>
														<div class="tfs-button-1"><?php echo do_shortcode( $metadata['pyre_button_1'][0] ); ?></div>
													<?php endif; ?>
													<?php if ( isset( $metadata['pyre_button_2'][0] ) && $metadata['pyre_button_2'][0] ) : ?>
														<div class="tfs-button-2"><?php echo do_shortcode( $metadata['pyre_button_2'][0] ); ?></div>
													<?php endif; ?>
												</div>
											<?php endif; ?>
										</div>
									</div>
									<?php if ( isset( $metadata['pyre_link_type'][0] ) && 'full' == $metadata['pyre_link_type'][0] && isset( $metadata['pyre_slide_link'][0] ) && $metadata['pyre_slide_link'][0] ) : ?>
										<a href="<?php echo $metadata['pyre_slide_link'][0]; ?>" class="overlay-link" <?php echo ( isset( $metadata['pyre_slide_target'][0] ) && 'yes' == $metadata['pyre_slide_target'][0] ) ? 'target="_blank"' : ''; ?>></a>
									<?php endif; ?>
									<?php if ( isset( $metadata['pyre_preview_image'][0] ) && $metadata['pyre_preview_image'][0] && isset( $metadata['pyre_type'][0] ) && 'self-hosted-video' == $metadata['pyre_type'][0] ) : ?>
										<div class="mobile_video_image" style="background-image: url(<?php echo Avada_Sanitize::css_asset_url( $metadata['pyre_preview_image'][0] ); ?>);"></div>
									<?php elseif ( isset( $metadata['pyre_type'][0] ) && 'self-hosted-video' == $metadata['pyre_type'][0] ) : ?>
										<div class="mobile_video_image" style="background-image: url(<?php echo Avada_Sanitize::css_asset_url( get_template_directory_uri() . '/assets/images/video_preview.jpg' ); ?>);"></div>
									<?php endif; ?>
									<?php if ( $video_bg_color && true == $video ) : ?>
										<div class="overlay" style="<?php echo $video_bg_color; ?>"></div>
									<?php endif; ?>
									<div class="background <?php echo $background_class; ?>" style="<?php echo $background_image; ?>max-width:<?php echo $slider_settings['slider_width']; ?>;height:<?php echo $slider_settings['slider_height']; ?>;filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $image_url[0]; ?>', sizingMethod='scale');-ms-filter:'progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $image_url[0]; ?>', sizingMethod='scale')';" data-imgwidth="<?php echo $img_width; ?>">
										<?php if ( isset( $metadata['pyre_type'][0] ) ) : ?>
											<?php if ( 'self-hosted-video' == $metadata['pyre_type'][0] && ( $metadata['pyre_webm'][0] || $metadata['pyre_mp4'][0] || $metadata['pyre_ogg'][0] ) ) : ?>
												<video width="1800" height="700" <?php echo $video_attributes; ?> preload="auto">
													<?php if ( array_key_exists( 'pyre_mp4', $metadata ) && $metadata['pyre_mp4'][0] ) : ?>
														<source src="<?php echo $metadata['pyre_mp4'][0]; ?>" type="video/mp4">
													<?php endif; ?>
													<?php if ( array_key_exists( 'pyre_ogg', $metadata ) && $metadata['pyre_ogg'][0] ) : ?>
														<source src="<?php echo $metadata['pyre_ogg'][0]; ?>" type="video/ogg">
													<?php endif; ?>
													<?php if ( array_key_exists( 'pyre_webm', $metadata ) && $metadata['pyre_webm'][0] ) : ?>
														<source src="<?php echo $metadata['pyre_webm'][0]; ?>" type="video/webm">
													<?php endif; ?>
												</video>
											<?php endif; ?>
										<?php endif; ?>
										<?php if ( isset( $metadata['pyre_type'][0] ) && isset( $metadata['pyre_youtube_id'][0] ) && 'youtube' == $metadata['pyre_type'][0] && $metadata['pyre_youtube_id'][0] ) : ?>
											<div style="position: absolute; top: 0; left: 0; <?php echo $video_zindex; ?> width: 100%; height: 100%" data-youtube-video-id="<?php echo $metadata['pyre_youtube_id'][0]; ?>" data-video-aspect-ratio="<?php echo $aspect_ratio; ?>">
												<div id="video-<?php echo $metadata['pyre_youtube_id'][0]; ?>-inner">
													<iframe height="100%" width="100%" src="https://www.youtube.com/embed/<?php echo $metadata['pyre_youtube_id'][0]; ?>?wmode=transparent&amp;modestbranding=1&amp;showinfo=0&amp;autohide=1&amp;enablejsapi=1&amp;rel=0&amp;vq=hd720&amp;<?php echo $youtube_attributes; ?>"></iframe>
												</div>
											</div>
										<?php endif; ?>
										<?php if ( isset( $metadata['pyre_type'][0] ) && isset( $metadata['pyre_vimeo_id'][0] ) &&  'vimeo' == $metadata['pyre_type'][0] && $metadata['pyre_vimeo_id'][0] ) : ?>
											<div style="position: absolute; top: 0; left: 0; <?php echo $video_zindex; ?> width: 100%; height: 100%" data-mute="<?php echo $data_mute; ?>" data-vimeo-video-id="<?php echo $metadata['pyre_vimeo_id'][0]; ?>" data-video-aspect-ratio="<?php echo $aspect_ratio; ?>">
												<iframe src="https://player.vimeo.com/video/<?php echo $metadata['pyre_vimeo_id'][0]; ?>?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff&amp;badge=0&amp;title=0<?php echo $vimeo_attributes; ?>" height="100%" width="100%" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
											</div>
										<?php endif; ?>
									</div>
								</li>
							<?php endwhile; ?>
						</ul>
					</div>
				</div>
			<?php endif; ?>
			<?php wp_reset_query();
		}
	}
}

if ( ! function_exists( 'avada_get_page_title_bar_contents' ) ) {
	function avada_get_page_title_bar_contents( $post_id, $get_secondary_content = TRUE ) {

		if ( $get_secondary_content ) {
			ob_start();
			if ( fusion_get_option( 'page_title_bar_bs', 'page_title_breadcrumbs_search_bar', $post_id ) != 'none' ) {
				if ( ( 'Breadcrumbs' == Avada()->settings->get( 'page_title_bar_bs' ) && in_array( get_post_meta( $post_id, 'pyre_page_title_breadcrumbs_search_bar', true ), array( 'breadcrumbs', 'default', '' ) ) ) || 'breadcrumbs' == get_post_meta( $post_id, 'pyre_page_title_breadcrumbs_search_bar', true ) ) {
					fusion_breadcrumbs();
				} elseif ( ( 'Search Box' == Avada()->settings->get( 'page_title_bar_bs' ) && in_array( get_post_meta( $post_id, 'pyre_page_title_breadcrumbs_search_bar', true ), array( 'searchbar', 'default', '' ) ) ) || 'searchbar' == get_post_meta( $post_id, 'pyre_page_title_breadcrumbs_search_bar', true ) ) {
					get_search_form();
				}
			}
			$secondary_content = ob_get_contents();
			ob_get_clean();
		} else {
			$secondary_content = '';
		}

		$title    = '';
		$subtitle = '';

		if ( '' != get_post_meta( $post_id, 'pyre_page_title_custom_text', true ) ) {
			$title = get_post_meta( $post_id, 'pyre_page_title_custom_text', true );
		}

		if ( '' != get_post_meta( $post_id, 'pyre_page_title_custom_subheader', true ) ) {
			$subtitle = get_post_meta( $post_id, 'pyre_page_title_custom_subheader', true );
		}

		if ( '' == get_post_meta( $post_id, 'pyre_page_title_text', true ) || 'default' == get_post_meta( $post_id, 'pyre_page_title_text', true ) ) {
			if ( Avada()->settings->get( 'page_title_bar_text' ) ) {
				$page_title_text = 'yes';
			} else {
				$page_title_text = 'no';
			}
		} else {
			$page_title_text = get_post_meta( $post_id, 'pyre_page_title_text', true );
		}

		if ( is_search() ) {
			$title = sprintf( esc_html__( 'Search results for: %s', 'Avada' ), get_search_query() );
			$subtitle = '';
		}

		if ( ! $title ) {
			$title = get_the_title( $post_id );

			// Only assing blog title theme option to default blog page and not posts page
			if ( is_home() && get_option( 'show_on_front' ) != 'page' ) {
				$title = Avada()->settings->get( 'blog_title' );
			}

			if ( is_404() ) {
				$title = esc_html__( 'Error 404 Page', 'Avada' );
			}

			if ( class_exists( 'Tribe__Events__Main' ) && ( ( tribe_is_event() && ! is_single() && ! is_home() ) || is_events_archive() || ( is_events_archive() && is_404() ) ) ) {
				$title = tribe_get_events_title();
			} elseif ( is_archive() && ! is_bbpress() && ! is_search() ) {
				if ( is_day() ) {
					$title = sprintf( esc_html__( 'Daily Archives: %s', 'Avada' ), '<span>' . get_the_date() . '</span>' );
				} else if ( is_month() ) {
					$title = sprintf( esc_html__( 'Monthly Archives: %s', 'Avada' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );
				} elseif ( is_year() ) {
					$title = sprintf( esc_html__( 'Yearly Archives: %s', 'Avada' ), '<span> ' . get_the_date( 'Y' ) . '</span>' );
				} elseif ( is_author() ) {
					$curauth = get_user_by( 'id', get_query_var( 'author' ) );
					$title   = $curauth->nickname;
				} elseif ( is_post_type_archive() ) {
					$title = post_type_archive_title( '', false );

					$sermon_settings = get_option( 'wpfc_options' );
					if ( is_array( $sermon_settings ) ) {
						$title = $sermon_settings['archive_title'];
					}

				} else {
					$title = single_cat_title( '', false );
				}
			}

			if ( class_exists( 'WooCommerce' ) && is_woocommerce() && ( is_product() || is_shop() ) && ! is_search() ) {
				if ( ! is_product() ) {
					$title = woocommerce_page_title( false );
				}
			}
		}

		// Only assing blog subtitle theme option to default blog page and not posts page
		if ( ! $subtitle && is_home() && get_option( 'show_on_front' ) != 'page' ) {
			$subtitle = Avada()->settings->get( 'blog_subtitle' );
		}

		if ( ! is_archive() && ! is_search() && ! ( is_home() && ! is_front_page() ) ) {
			if ( 'no' == $page_title_text && ( 'yes' == get_post_meta( $post_id, 'pyre_page_title', true ) || 'yes_without_bar' == get_post_meta( $post_id, 'pyre_page_title', true ) || ( 'hide' != Avada()->settings->get( 'page_title_bar' ) && 'no' != get_post_meta( $post_id, 'pyre_page_title', true ) ) ) ) {
				$title    = '';
				$subtitle = '';
			}
		} else {
			if ( 'hide' != Avada()->settings->get( 'page_title_bar' ) && 'no' == $page_title_text ) {
				$title    = '';
				$subtitle = '';
			}
		}

		return array( $title, $subtitle, $secondary_content );
	}

}

if ( ! function_exists( 'avada_current_page_title_bar' ) ) {
	function avada_current_page_title_bar( $post_id  ) {
		$page_title_bar_contents = avada_get_page_title_bar_contents( $post_id );

		if ( ( ! is_archive() || class_exists( 'WooCommerce' ) && is_shop() ) &&
			 ! is_search()
		) {
			if ( 'yes' == get_post_meta( $post_id, 'pyre_page_title', true ) || 'yes_without_bar' == get_post_meta( $post_id, 'pyre_page_title', true ) || ( 'hide' != Avada()->settings->get( 'page_title_bar' ) && 'no' != get_post_meta( $post_id, 'pyre_page_title', true ) ) ) {
				if ( is_home() && is_front_page() && ! Avada()->settings->get( 'blog_show_page_title_bar' ) ) {
					// do nothing
				} else {
					if ( is_home() && get_post_meta( $post_id, 'pyre_page_title', true ) == 'default' && ! Avada()->settings->get( 'blog_show_page_title_bar' ) ) {
						return;
					}
					avada_page_title_bar( $page_title_bar_contents[0], $page_title_bar_contents[1], $page_title_bar_contents[2] );
				}
			}
		} else {
			if ( is_home() && Avada()->settings->get( 'blog_show_page_title_bar' ) ) {
				avada_page_title_bar( $page_title_bar_contents[0], $page_title_bar_contents[1], $page_title_bar_contents[2] );
			} else {
				if ( 'hide' != Avada()->settings->get( 'page_title_bar' ) ) {
					avada_page_title_bar( $page_title_bar_contents[0], $page_title_bar_contents[1], $page_title_bar_contents[2] );
				}
			}
		}
	}
}

if ( ! function_exists( 'avada_backend_check_new_bbpress_post' ) ) {
	function avada_backend_check_new_bbpress_post() {
		global $pagenow, $post_type;
		return ( 'post-new.php' == $pagenow && in_array( $post_type, array( 'forum', 'topic', 'reply' ) ) ) ? true : false;
	}
}

if ( ! function_exists( 'avada_featured_images_for_pages' ) ) {
	function avada_featured_images_for_pages() {

		$html = $video = $featured_images = '';

		if ( ! post_password_required( get_the_ID() ) ) {

			if ( Avada()->settings->get( 'featured_images_pages' ) ) {
				if ( 0 < avada_number_of_featured_images() || get_post_meta( get_the_ID(), 'pyre_video', true ) ) {
					if ( get_post_meta( get_the_ID(), 'pyre_video', true ) ) {
						$video = '<li><div class="full-video">' . get_post_meta( get_the_ID(), 'pyre_video', true ) . '</div></li>';
					}

					if ( has_post_thumbnail() && 'yes' != get_post_meta( get_the_ID(), 'pyre_show_first_featured_image', true ) ) {
						$attachment_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
						$full_image       = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
						$attachment_data  = wp_get_attachment_metadata( get_post_thumbnail_id() );

						$featured_images .= sprintf(
							'<li><a href="%s" rel="prettyPhoto[gallery%s]" data-title="%s" data-caption="%s"><img src="%s" alt="%s" role="presentation" /></a></li>',
							$full_image[0],
							get_the_ID(),
							get_post_field( 'post_title', get_post_thumbnail_id() ),
							get_post_field( 'post_excerpt', get_post_thumbnail_id() ),
							$attachment_image[0],
							get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true )
						);
					}

					$i = 2;
					while ( $i <= Avada()->settings->get( 'posts_slideshow_number' ) ) :

						$attachment_new_id = kd_mfi_get_featured_image_id( 'featured-image-'.$i, 'page' );

						if ( $attachment_new_id ) {

							$attachment_image = wp_get_attachment_image_src( $attachment_new_id, 'full' );
							$full_image       = wp_get_attachment_image_src( $attachment_new_id, 'full' );
							$attachment_data  = wp_get_attachment_metadata( $attachment_new_id );

							$featured_images .= sprintf(
								'<li><a href="%s" rel="iLightbox[gallery%s]" data-title="%s" data-caption="%s"><img src="%s" alt="%s" role="presentation" /></a></li>',
								$full_image[0],
								get_the_ID(),
								get_post_field( 'post_title', $attachment_new_id ),
								get_post_field( 'post_excerpt', $attachment_new_id ),
								$attachment_image[0],
								get_post_meta( $attachment_new_id, '_wp_attachment_image_alt', true )
							);
						}
						$i++;
					endwhile;

					$html .= sprintf(
						'<div class="fusion-flexslider flexslider post-slideshow"><ul class="slides">%s%s</ul></div>',
						$video,
						$featured_images
					);
				}
			}
		}
		return $html;
	}
}

if ( ! function_exists( 'avada_featured_images_lightbox' ) ) {
	function avada_featured_images_lightbox( $post_id ) {
		$html = $video = $featured_images = '';

		if ( get_post_meta( $post_id, 'pyre_video_url', true ) ) {
			$video = sprintf( '<a href="%s" class="iLightbox[gallery%s]"></a>', get_post_meta( $post_id, 'pyre_video_url', true ), $post_id );
		}

		$i = 2;

		while ( $i <= Avada()->settings->get( 'posts_slideshow_number' ) ) :

			$attachment_new_id = kd_mfi_get_featured_image_id( 'featured-image-'.$i, get_post_type( $post_id ) );
			if ( $attachment_new_id ) {
				$attachment_image = wp_get_attachment_image_src($attachment_new_id, 'full' );
				$full_image       = wp_get_attachment_image_src($attachment_new_id, 'full' );
				$attachment_data  = wp_get_attachment_metadata($attachment_new_id );
				$featured_images .= sprintf(
					'<a href="%s" data-rel="iLightbox[gallery%s]" title="%s" data-title="%s" data-caption="%s"></a>',
					$full_image[0],
					$post_id,
					get_post_field( 'post_title', $attachment_new_id ),
					get_post_field( 'post_title', $attachment_new_id ),
					get_post_field( 'post_excerpt', $attachment_new_id )
				);
			}
			$i++;

		endwhile;

		$html .= sprintf( '<div class="fusion-portfolio-gallery-hidden">%s%s</div>', $video, $featured_images );

		return $html;
	}

}

if ( ! function_exists( 'avada_display_sidenav' ) ) {
	function avada_display_sidenav( $post_id ) {

		if ( is_page_template( 'side-navigation.php' ) ) {
			$html = '<ul class="side-nav">';

			$post_ancestors = get_ancestors( $post_id, 'page' );
			$post_parent    = end( $post_ancestors );

			$html .= ( is_page( $post_parent ) ) ? '<li class="current_page_item">' : '<li>';

			if ( $post_parent ) {
				$html .= sprintf( '<a href="%s" title="%s">%s</a></li>', get_permalink( $post_parent ), esc_html__( 'Back to Parent Page', 'Avada' ), get_the_title( $post_parent ) );
				$children = wp_list_pages( sprintf( 'title_li=&child_of=%s&echo=0', $post_parent ) );
			} else {
				$html .= sprintf( '<a href="%s" title="%s">%s</a></li>', get_permalink( $post_id ), esc_html__( 'Back to Parent Page', 'Avada' ), get_the_title( $post_id ) );
				$children = wp_list_pages( sprintf( 'title_li=&child_of=%s&echo=0', $post_id ) );
			}

			if ( $children ) {
				$html .= $children;
			}

			$html .= '</ul>';

			return $html;
		}
	}
}

if ( ! function_exists( 'avada_link_pages' ) ) {
	function avada_link_pages() {
		wp_link_pages( array(
			'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'Avada' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span class="page-number">',
			'link_after'  => '</span>'
		) );
	}
}

if ( ! function_exists( 'avada_number_of_featured_images' ) ) {
	function avada_number_of_featured_images() {
		global $post;
		$number_of_images = 0;

		if ( has_post_thumbnail() && 'yes' != get_post_meta( $post->ID, 'pyre_show_first_featured_image', true ) ) {
			$number_of_images++;
		}

		for ( $i = 2; $i <= Avada()->settings->get( 'posts_slideshow_number' ); $i++ ) {
			$attachment_new_id = kd_mfi_get_featured_image_id('featured-image-'.$i, $post->post_type );

			if ( $attachment_new_id ) {
				$number_of_images++;
			}
		}
		return $number_of_images;
	}
}

// Omit closing PHP tag to avoid "Headers already sent" issues.