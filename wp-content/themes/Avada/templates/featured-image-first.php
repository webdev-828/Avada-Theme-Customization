<?php ob_start(); ?>
<?php if ( 'related' == $type && 'fixed' == $post_featured_image_size && get_post_thumbnail_id( $post_id ) ) :

	/**
	 * Resize images for use as related posts
	 */
	$image = Fusion_Image_Resizer::image_resize( array(
		'width'  => '500',
		'height' => '383',
		'url'    => wp_get_attachment_url( get_post_thumbnail_id( $post_id ) ),
		'path'   => get_attached_file( get_post_thumbnail_id( $post_id ) ),
	) );
	$scrset = ( isset( $image['retina_url'] ) && $image['retina_url'] ) ? ' srcset="' . $image['url'] . ' 1x, ' . $image['retina_url'] . ' 2x"' : ''; ?>
	<img src="<?php echo $image['url']; ?>"<?php echo $scrset; ?> width="<?php echo $image['width']; ?>" height="<?php echo $image['height']; ?>" alt="<?php the_title_attribute( 'post=' . $post_id ); ?>" />

<?php else : ?>

	<?php if ( has_post_thumbnail( $post_id ) ) : ?>
		<?php
		/**
		 * Get the featured image if one is set.
		 */
		?>
		<?php echo get_the_post_thumbnail( $post_id, $post_featured_image_size ); ?>

	<?php elseif ( get_post_meta( $post_id, 'pyre_video', true ) ) : ?>

		<?php
		/**
		 * Show the video if one is set
		 */
		?>
		<div class="full-video">
			<?php echo get_post_meta( $post_id, 'pyre_video', true ); ?>
		</div>

	<?php elseif ( $display_placeholder_image ) : ?>

		<?php
		/**
		 * avada_placeholder_image hook
		 *
		 * @hooked avada_render_placeholder_image - 10 (outputs the HTML for the placeholder image)
		 */
		?>
		<?php do_action( 'avada_placeholder_image', $post_featured_image_size ); ?>

	<?php endif; ?>

<?php endif; ?>

<?php
/**
 * Set the markup generated above as a variable.
 * Depending on the use case we'll be echoing this markup in a wrapper or followed by an action.
 */
$featured_image = ob_get_clean();
?>

<div class="fusion-image-wrapper<?php echo $image_size_class; ?>" aria-haspopup="true">

	<?php if ( ( Avada()->settings->get( 'image_rollover' ) && 'yes' == $display_rollover ) || 'force_yes' == $display_rollover ) : ?>

		<?php echo $featured_image; ?>
		<?php do_action( 'avada_rollover', $post_id, $post_permalink, $display_woo_price, $display_woo_buttons, $display_post_categories, $display_post_title, $gallery_id, $display_woo_rating ); ?>

	<?php else : ?>

		<a href="<?php echo $post_permalink; ?>">
			<?php echo $featured_image; ?>
		</a>

	<?php endif; ?>

</div>
