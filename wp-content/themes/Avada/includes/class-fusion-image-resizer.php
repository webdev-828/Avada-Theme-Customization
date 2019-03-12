<?php
/**
 * On the fly image resizer.
 * Script from the Shoestrap theme by @aristath
 *
 * @since 3.8.0
 */

/**
 * The Image handling class
 */
class Fusion_Image_Resizer {

	public static function image_resize( $data ) {
		$defaults = array(
			'url'    => '',
			'width'  => '',
			'height' => '',
			'crop'   => true,
			'retina' => true,
			'resize' => true,
		);
		$settings = wp_parse_args( $data, $defaults );
		if ( empty( $settings['url'] ) ) {
			return;
		}
		// Generate the @2x file if retina is enabled
		if ( $settings['retina'] ) {
			self::_resize( $settings['url'], $settings['width'], $settings['height'], $settings['crop'], true );
		}
		return self::_resize( $settings['url'], $settings['width'], $settings['height'], $settings['crop'], false );
	}

	/**
	 * Resizes an image and returns an array containing the resized URL, width, height and file type.
	 * Uses native Wordpress functionality.
	 *
	 * This is a slightly modified version of http://goo.gl/9iS0CO
	 *
	 * @var    $url    string
	 *         $width  integer
	 *         $height integer
	 *         $crop   boolean
	 *         $retina boolean
	 * @return array   An array containing the resized image URL, width, height and file type.
	 */
	public static function _resize( $url, $width = null, $height = null, $crop = true, $retina = false ) {
		global $wpdb;
		if ( empty( $url ) ) {
			return new WP_Error( 'no_image_url', __( 'No image URL has been entered.', 'Avada' ), $url );
		}
		// Get default size from database
		$width  = ( $width )  ? $width  : get_option( 'thumbnail_size_w' );
		$height = ( $height ) ? $height : get_option( 'thumbnail_size_h' );
		// Allow for different retina sizes
		$retina = $retina ? ( $retina === true ? 2 : $retina ) : 1;
		// Get the image file path
		$upload_dir_paths = wp_upload_dir();

		// Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
		if ( false !== strpos( $url, $upload_dir_paths['baseurl'] ) ) {
			// If this is the URL of an auto-generated thumbnail, get the URL of the original image
			$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $url );
			// Remove the upload path base directory from the attachment URL
			$attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );
			// Run a custom database query to get the attachment ID from the modified attachment URL
			$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );
			// Get the file path
			$file_path = get_attached_file( $attachment_id );
		}

		if ( ! isset( $file_path ) ) {
			return array( 'url' => $url, 'width' => $width, 'height' => $height );
		}

		// Destination width and height variables
		$dest_width  = $width * $retina;
		$dest_height = $height * $retina;
		// Some additional info about the image
		$info = pathinfo( $file_path );
		$dir = $info['dirname'];
		$ext = '';
		if ( ! empty( $info['extension'] ) ) {
			$ext = $info['extension'];
		}
		$name = wp_basename( $file_path, ".$ext" );

		// Suffix applied to filename
		$suffix_width  = ( $dest_width / $retina );
		$suffix_height = ( $dest_height / $retina );
		$suffix_retina = ( $retina != 1 ) ? '@' . $retina . 'x' : NULL;
		$suffix = "{$suffix_width}x{$suffix_height}{$suffix_retina}";
		// Get the destination file name
		$dest_file_name = "{$dir}/{$name}-{$suffix}.{$ext}";

		if ( ! file_exists( $dest_file_name ) ) {
			/*
			 *  Bail if this image isn't in the Media Library.
			 *  We only want to resize Media Library images, so we can be sure they get deleted correctly when appropriate.
			 */
			if ( ! isset( $attachment_id ) || ! $attachment_id ) {
				return array( 'url' => $url, 'width' => $width, 'height' => $height );
			}
			// Load Wordpress Image Editor
			$editor = wp_get_image_editor( $file_path );
			if ( is_wp_error( $editor ) ) {
				return array( 'url' => $url, 'width' => $width, 'height' => $height );
			}

			// Get the original image size
			$size = $editor->get_size();
			$orig_width  = $size['width'];
			$orig_height = $size['height'];
			$src_x = $src_y = 0;
			$src_w = $orig_width;
			$src_h = $orig_height;
			if ( $crop ) {
				$cmp_x = $orig_width / $dest_width;
				$cmp_y = $orig_height / $dest_height;
				// Calculate x or y coordinate, and width or height of source
				if ( $cmp_x > $cmp_y ) {
					$src_w = round( $orig_width / $cmp_x * $cmp_y );
					$src_x = round( ( $orig_width - ( $orig_width / $cmp_x * $cmp_y ) ) / 2 );
				} elseif ( $cmp_y > $cmp_x ) {
					$src_h = round( $orig_height / $cmp_y * $cmp_x );
					$src_y = round( ( $orig_height - ( $orig_height / $cmp_y * $cmp_x ) ) / 2 );
				}
			}
			// Check if the file is writable before proceeding
			global $wp_filesystem;
			if ( empty( $wp_filesystem ) ) {
				require_once( ABSPATH . '/wp-admin/includes/file.php' );
				WP_Filesystem();
			}

			if ( ! $wp_filesystem->put_contents( $dest_file_name, '', FS_CHMOD_FILE ) ) {
				return array( 'url' => $url, 'width' => $orig_width, 'height' => $orig_height );
			}
			// Time to crop the image!
			$editor->crop( $src_x, $src_y, $src_w, $src_h, $dest_width, $dest_height );
			// Now let's save the image
			$saved = $editor->save( $dest_file_name );
			// Get resized image information
			$resized_url    = str_replace( basename( $url ), basename( $saved['path'] ), $url );

			$resized_width  = $saved['width'];
			$resized_height = $saved['height'];
			$resized_type   = $saved['mime-type'];
			// Add the resized dimensions to original image metadata (so we can delete our resized images when the original image is delete from the Media Library)
			$metadata = wp_get_attachment_metadata( $attachment_id );
			if ( isset( $metadata['image_meta'] ) ) {
				$metadata['image_meta']['resized_images'][] = $resized_width .'x'. $resized_height;
				wp_update_attachment_metadata( $attachment_id, $metadata );
			}
			// Create the image array
			$image_array = array(
				'url'    => $resized_url,
				'width'  => $resized_width,
				'height' => $resized_height,
				'type'   => $resized_type,
				'path'   => $dest_file_name,
			);
			$image_array['retina_url'] = ( file_exists( "{$dir}/{$name}-{$suffix}{$suffix_retina}.{$ext}" ) ) ? rtrim( $image_array['url'], ".{$ext}" ) . "@2x.{$ext}" : false;

		} else {
			$image_array = array(
				'url'    => str_replace( basename( $url ), basename( $dest_file_name ), $url ),
				'width'  => $dest_width,
				'height' => $dest_height,
				'type'   => $ext,
				'path'   => $dest_file_name,
			);
			$image_array['retina_url'] = ( file_exists( "{$dir}/{$name}-{$suffix}{$suffix_retina}.{$ext}" ) ) ? rtrim( $image_array['url'], ".{$ext}" ) . "@2x.{$ext}" : false;

		}
		// Return image array
		return $image_array;
	}

}

// Omit closing PHP tag to avoid "Headers already sent" issues.
