<?php

class Avada_Images {

	public static $grid_image_meta;
	public static $grid_accepted_widths;
	public static $supported_grid_layouts;

	public function __construct() {

		self::$grid_image_meta = array();
		self::$grid_accepted_widths = array( '200', '400', '600', '800', '1200' );
		self::$supported_grid_layouts = array( 'grid', 'timeline', 'large', 'portfolio_full', 'related-posts' );

		$options = get_option( Avada::get_option_name() );
		if ( isset( $options['status_lightbox'] ) && $options['status_lightbox'] ) {
			add_filter( 'wp_get_attachment_link', array( $this, 'prepare_lightbox_links' ) );
		}

		add_filter( 'jpeg_quality', array( $this, 'set_jpeg_quality' ) );
		add_filter( 'wp_editor_set_quality', array( $this, 'set_jpeg_quality' ) );
		add_filter( 'max_srcset_image_width', array( $this, 'set_max_srcset_image_width' ) );
		add_filter( 'wp_calculate_image_srcset', array( $this, 'set_largest_image_size' ), '10', '5' );
		add_filter( 'wp_calculate_image_srcset', array( $this, 'edit_grid_image_srcset' ), '15', '5' );
		add_filter( 'wp_calculate_image_sizes', array( $this, 'edit_grid_image_sizes' ), '10', '5' );
		add_filter( 'post_thumbnail_html', array( $this, 'edit_grid_image_src' ), '10', '5' );
		add_action( 'delete_attachment', array( $this, 'delete_resized_images' ) );
	}

	/**
	 * Adds lightbox attributes to links
	 */
	public function prepare_lightbox_links( $content ) {

		preg_match_all('/<a[^>]+href=([\'"])(.+?)\1[^>]*>/i', $content, $matches );
		$attachment_id = self::get_attachment_id_from_url( $matches[2][0] );
		$title = get_post_field( 'post_title', $attachment_id );
		$caption = get_post_field('post_excerpt', $attachment_id );

		$content = preg_replace( "/<a/", '<a data-rel="iLightbox[postimages]" data-title="' . $title . '" data-caption="' . $caption . '"' , $content, 1 );

		return $content;
	}

	/**
	 * Modify the image quality and set it to chosen Theme Options value.
	 * @since 3.9
	 *
	 * @return string The new image quality.
	 */
	public function set_jpeg_quality() {
		return Avada()->settings->get( 'pw_jpeg_quality' );
	}

	/**
	 * Modify the maximum image width to be included in srcset attribute.
	 * @since 3.9
	 *
	 * @param int   $max_width  The maximum image width to be included in the 'srcset'. Default '1600'.
	 *
	 * @return int 	The new max width.
	 */
	public function set_max_srcset_image_width( $max_width ) {
		return 1920;
	}

	/**
	 * Add the fullsize image to the scrset attribute.
	 *
	 * @since 3.9.0
	 *
	 * @param array  $sources {
	 *     One or more arrays of source data to include in the 'srcset'.
	 *
	 *     @type array $width {
	 *         @type string $url        The URL of an image source.
	 *         @type string $descriptor The descriptor type used in the image candidate string,
	 *                                  either 'w' or 'x'.
	 *         @type int    $value      The source width if paired with a 'w' descriptor, or a
	 *                                  pixel density value if paired with an 'x' descriptor.
	 *     }
	 * }
	 * @param array  $size_array    Array of width and height values in pixels (in that order).
	 * @param string $image_src     The 'src' of the image.
	 * @param array  $image_meta    The image meta data as returned by 'wp_get_attachment_metadata()'.
	 * @param int    $attachment_id Image attachment ID or 0.
	 *
	 * @return array $sources 		One or more arrays of source data to include in the 'srcset'.
	 */
	public function set_largest_image_size( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {
		$cropped_image = false;

		foreach ( $sources as $source => $details ) {
			if ( $details['url'] == $image_src ) {
				$cropped_image = true;
			}
		}

		if ( ! $cropped_image ) {
			$full_image_src = wp_get_attachment_image_src( $attachment_id, 'full' );

			$full_size = array(
				'url'        => $full_image_src[0],
				'descriptor' => 'w',
				'value'      => $image_meta['width']
			);

			$sources[ $image_meta['width'] ] = $full_size;
		}

		return $sources;
	}

	/**
	 * Filter out all srcset attributes, that do not fit current grid layout.
	 *
	 * @since 4.0.0
	 *
	 * @param array  $sources {
	 *     One or more arrays of source data to include in the 'srcset'.
	 *
	 *     @type array $width {
	 *         @type string $url        The URL of an image source.
	 *         @type string $descriptor The descriptor type used in the image candidate string,
	 *                                  either 'w' or 'x'.
	 *         @type int    $value      The source width if paired with a 'w' descriptor, or a
	 *                                  pixel density value if paired with an 'x' descriptor.
	 *     }
	 * }
	 * @param array  $size_array    Array of width and height values in pixels (in that order).
	 * @param string $image_src     The 'src' of the image.
	 * @param array  $image_meta    The image meta data as returned by 'wp_get_attachment_metadata()'.
	 * @param int    $attachment_id Image attachment ID or 0.
	 *
	 * @return array $sources 		One or more arrays of source data to include in the 'srcset'.
	 */
	public function edit_grid_image_srcset( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {
		// Only do manipulation for blog images
		if ( ! empty( self::$grid_image_meta ) ) {

			// Check if Safari below version 9 is used
			$is_safari_below_v9 = false;
			if ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
				$user_agent = $_SERVER['HTTP_USER_AGENT'];

				preg_match( "/(?:version\/|(?:safari) )([\d.]+)/i", $user_agent, $matches );
				$version = isset( $matches[1] ) ? $matches[1] : false;

				if ( false !== stripos( $user_agent, 'safari' ) && false === stripos( $user_agent, 'chrome' ) && version_compare( $version, '9.0.0', '<' ) ) {
					$is_safari_below_v9 = true;
				}
			}

			// All browsers except Safari below version 9
			if ( ! $is_safari_below_v9 ) {
				// Only include the uncropped sizes in srcset
				foreach ( $sources as $width => $source ) {
					if ( ! in_array( $width, self::$grid_accepted_widths ) ) {
						unset( $sources[$width] );
					}
				}
			// Safari below version 9
			} else {
				$accepted_widths = array( '400', '800', '1200' );

				foreach ( $sources as $width => $source ) {
					if ( ! in_array( $width, $accepted_widths ) ) {
						// Unset cropped sizes
						unset( $sources[$width] );
					} else {
						// Reset the sourcesto x descriptor
						if ( in_array( $width, $accepted_widths ) ) {
							$sources[$width]['descriptor'] = 'x';
							$sources[$width]['value'] = array_search( $width, $accepted_widths ) + 1;
						}
					}
				}
			}
		}

		ksort( $sources );

		return $sources;
	}

	/**
	 * Edits the'sizes' attribute for grid images.
	 *
	 * @since 4.0
	 *
	 * @param string       $sizes         A source size value for use in a 'sizes' attribute.
	 * @param array|string $size          Image size to retrieve. Accepts any valid image size, or an array
	 *                                    of width and height values in pixels (in that order). Default 'medium'.
	 * @param string       $image_src     Optional. The URL to the image file. Default null.
	 * @param array        $image_meta    Optional. The image meta data as returned by 'wp_get_attachment_metadata()'.
	 *                                    Default null.
	 * @param int          $attachment_id Optional. Image attachment ID. Either `$image_meta` or `$attachment_id`
	 *                                    is needed when using the image size name as argument for `$size`. Default 0.
	 * @return string|bool A valid source size value for use in a 'sizes' attribute or false.
	 */
	public function edit_grid_image_sizes( $sizes, $size, $image_src, $image_meta, $attachment_id ) {
		// Only do manipulation for blog images
		if ( isset( self::$grid_image_meta['layout'] ) ) {
			$side_header_width = ( 'Top' == Avada()->settings->get( 'header_position' ) ) ? 0 : intval( Avada()->settings->get( 'side_header_width' ) );
			$content_break_point = $side_header_width + intval( Avada()->settings->get( 'content_break_point' ) );
			$content_width = Avada()->layout->get_content_width();

			// Grid
			if ( 'grid' == self::$grid_image_meta['layout'] || 'portfolio_full' == self::$grid_image_meta['layout'] ) {
				$main_break_point = (int) Avada()->settings->get( 'grid_main_break_point' );
				if ( 640 < $main_break_point ) {
					$breakpoint_range = $main_break_point - 640;
				} else {
					$breakpoint_range = 360;
				}

				$breakpoint_interval = $breakpoint_range / 5;

				$break_points[6] = $main_break_point + $side_header_width;
				$break_points[5] = $break_points[6] - $breakpoint_interval;
				$break_points[4] = $break_points[6] - 2 * $breakpoint_interval;
				$break_points[3] = $break_points[6] - 3 * $breakpoint_interval;
				$break_points[2] = $break_points[6] - 4 * $breakpoint_interval;
				$break_points[1] = $break_points[6] - 5 * $breakpoint_interval;
				$sizes = '';
				foreach( $break_points as $columns => $breakpoint ) {

					if ( $columns <= (int) self::$grid_image_meta['columns'] ) {
						$width = $content_width / $columns;
						if ( $breakpoint < $width ) {
						 $width = $breakpoint + $breakpoint_interval;
						}
						$sizes .= sprintf( '(min-width: %spx) %spx, ', round( $breakpoint ), round( $width ) );
					}

				}
				$sizes .= '100vw';

			// Timeline
			} elseif ( 'timeline' == self::$grid_image_meta['layout'] ) {
				$width = 40;
				$sizes = sprintf( '(max-width: %spx) 100vw, %svw', $content_break_point, $width );

			// Large Layouts
			} else if ( false !== strpos( self::$grid_image_meta['layout'], 'large' ) ) {
				$sizes = sprintf( '(max-width: %spx) 100vw, %spx', $content_break_point, $content_width );
			}
		}

		return $sizes;
	}

    /**
     * Change the src attribute for grid images.
     *
     * @since 4.0.0
     *
     * @param string       $html              The post thumbnail HTML.
     * @param int          $post_id           The post ID.
     * @param string       $post_thumbnail_id The post thumbnail ID.
     * @param string|array $size              The post thumbnail size. Image size or array of width and height
     *                                        values (in that order). Default 'post-thumbnail'.
     * @param string       $attr              Query string of attributes.
     * @return string The html markup of the image.
     */
	public function edit_grid_image_src( $html, $post_id = null, $post_thumbnail_id = null, $size = null, $attr = null ) {
		if ( isset( self::$grid_image_meta['layout'] ) && in_array( self::$grid_image_meta['layout'], self::$supported_grid_layouts ) && $size == 'full' ) {

			$image_size = $this->get_grid_image_base_size( $post_thumbnail_id, self::$grid_image_meta['layout'], self::$grid_image_meta['columns'] );

			$full_image_src = wp_get_attachment_image_src( $post_thumbnail_id, $image_size );

			$html = preg_replace( '@src="([^"]+)"@', 'src="' . $full_image_src[0] . '"', $html );
		}

		return $html;
	}

	/**
	 * Get image size based on column size
	 *
	 * @since 4.0.0
	 *
	 * @param int          $post_thumbnail_id Attachment ID.
	 * @param string       $layout            Number of columns.
	 * @return string Image size name.
	 */
	public function get_grid_image_base_size( $post_thumbnail_id = null, $layout = null, $columns = null ) {
		global $is_IE;
		$sizes = array();
		$width = '';

		// Get image metadata
		$image_meta = wp_get_attachment_metadata( $post_thumbnail_id );
		
		if ( $image_meta ) {
			$image_sizes = $image_meta['sizes'];
		
			foreach ( $image_sizes as $name => $image ) {
				if ( in_array( $name, self::$grid_accepted_widths ) ) {
					// Create accepted sizes array
					if ( $image['width'] ) {
						$sizes[ $image['width'] ] = $name;
					}
				}
			}
		}

		if ( false !== strpos( $layout, 'large' ) ) {
			$width = Avada()->layout->get_content_width();
		} elseif ( 'timeline' == $layout ) {
			$width = Avada()->layout->get_content_width() * 0.8 / $columns;
		} else {
			$width = Avada()->layout->get_content_width() / $columns;
		}

		ksort( $sizes );

		// Find closest size match
		$image_size = null;
		$size_name = null;

		foreach ( $sizes as $size => $name ) {
			if ( $image_size === null || abs( $width - $image_size ) > abs( $size - $width ) ) {
				$image_size = $size;
				$size_name = $name;
			}
		}

		// Fallback to 'full' image size if no match was found or Internet Explorer is used
		if ( $size_name == null || $size_name == '' || $is_IE ) {
			$size_name = 'full';
		}

		return $size_name;
	}

	/**
	 * Setter function for the $grid_image_meta variable
	 * @since 4.0
	 *
	 * @param array  $grid_image_meta    Array containing layout and number of columns.
	 *
	 * @return void
	 */
	public function set_grid_image_meta( $grid_image_meta ) {
		self::$grid_image_meta = $grid_image_meta;
	}

	/**
	 * Gets the attachment ID from the url
	 *
	 * @param string $attachment_url The url of the attachment
	 *
	 * @return string The attachment ID
	 */
	public static function get_attachment_id_from_url( $attachment_url = '' ) {
		global $wpdb;
		$attachment_id = false;

		if ( $attachment_url == '' || ! is_string( $attachment_url ) ) {
			return '';
		}

		$upload_dir_paths = wp_upload_dir();
		$upload_dir_paths_baseurl = $upload_dir_paths['baseurl'];

		if ( substr( $attachment_url, 0, 2 ) == '//' ) {
			$upload_dir_paths_baseurl = Avada_Sanitize::get_url_with_correct_scheme( $upload_dir_paths_baseurl );
		}

		// Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
		if ( false !== strpos( $attachment_url, $upload_dir_paths_baseurl ) ) {

			// If this is the URL of an auto-generated thumbnail, get the URL of the original image
			$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif|tiff|svg)$)/i', '', $attachment_url );

			// Remove the upload path base directory from the attachment URL
			$attachment_url = str_replace( $upload_dir_paths_baseurl . '/', '', $attachment_url );

			// Run a custom database query to get the attachment ID from the modified attachment URL
			$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );
		}

		return $attachment_id;
	}

	/**
	 * Gets the most important attachment data from the url.
	 *
	 * @since 4.0
	 *
	 * @param string $attachment_url The url of the used attachment.
	 *
	 * @return array/bool The attachment data of the image, false if the url is empty or attachment not found.
	 */
	public static function get_attachment_data_from_url( $attachment_url = '' ) {

		if ( $attachment_url == '' ) {
			return false;
		}

		$attachment_data['url'] = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );
		$attachment_data['id'] = self::get_attachment_id_from_url( $attachment_data['url'] );

		if ( ! $attachment_data['id'] ) {
			return false;
		}

		preg_match( '/\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', $attachment_url, $matches );
		if ( count( $matches ) > 0 ) {
			$dimensions = explode( 'x', $matches[0] );
			$attachment_data['width'] = $dimensions[0];
			$attachment_data['height'] = $dimensions[1];
		} else {
			$attachment_src = wp_get_attachment_image_src( $attachment_data['id'], 'full' );
			$attachment_data['width'] = $attachment_src[1];
			$attachment_data['height'] = $attachment_src[2];
		}

		$attachment_data['alt'] = get_post_field( '_wp_attachment_image_alt', $attachment_data['id'] );
		$attachment_data['caption'] = get_post_field( 'post_excerpt', $attachment_data['id'] );
		$attachment_data['title'] = get_post_field( 'post_title', $attachment_data['id'] );

		return $attachment_data;
	}

	/**
	 * Deletes the resized images when the original image is deleted from the Wordpress Media Library.
	 * This is necessary in order to handle custom image sizes created from the Fusion_Image_Resizer class.
	 */
	function delete_resized_images( $post_id ) {
		// Get attachment image metadata
		$metadata = wp_get_attachment_metadata( $post_id );
		if ( ! $metadata ) {
			return;
		}
		// Do some bailing if we cannot continue
		if ( ! isset( $metadata['file'] ) || ! isset( $metadata['image_meta']['resized_images'] ) ) {
			return;
		}
		$pathinfo = pathinfo( $metadata['file'] );
		$resized_images = $metadata['image_meta']['resized_images'];
		// Get Wordpress uploads directory (and bail if it doesn't exist)
		$wp_upload_dir = wp_upload_dir();
		$upload_dir    = $wp_upload_dir['basedir'];
		if ( ! is_dir( $upload_dir ) ) {
			return;
		}
		// Delete the resized images
		foreach ( $resized_images as $dims ) {
			// Get the resized images filename
			$file = $upload_dir .'/'. $pathinfo['dirname'] .'/'. $pathinfo['filename'] .'-'. $dims .'.'. $pathinfo['extension'];
			// Delete the resized image
			@unlink( $file );
		}
	}

	/**
	 * Gets the logo data (url, width, height ) for the specified option name
	 *
	 * @since 4.0
	 *
	 * @param string $logo_option_name The name of the logo option
	 *
	 * @return array The logo data
	 */
	public function get_logo_data( $logo_option_name ) {

		$logo_data = array(
			'url'    => '',
			'width'  => '',
			'height' => ''
		);

		$logo_url = Avada_Sanitize::get_url_with_correct_scheme( Avada()->settings->get( $logo_option_name, 'url' ) );

		if ( $logo_url ) {
			$logo_data['url'] = $logo_url;

			if ( false !== strpos( $logo_option_name, 'retina' ) ) {
				$logo_url = Avada_Sanitize::get_url_with_correct_scheme( Avada()->settings->get( str_replace( '_retina', '', $logo_option_name ), 'url' ) );
			}

			$logo_attachment_data = self::get_attachment_data_from_url( $logo_url );

			if ( $logo_attachment_data ) {
				$logo_data['width'] = $logo_attachment_data['width'];
				$logo_data['height'] = $logo_attachment_data['height'];
			}
		}

		return $logo_data;
	}
}

// Omit closing PHP tag to avoid "Headers already sent" issues.
