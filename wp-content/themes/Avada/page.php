<?php get_header(); ?>
<div id="content" <?php Avada()->layout->add_style( 'content_style' ); ?>>
	<?php while ( have_posts() ) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php echo avada_render_rich_snippets_for_pages(); ?>
			<?php if ( ! post_password_required( $post->ID ) ) : ?>
				<?php if ( Avada()->settings->get( 'featured_images_pages' ) ) : ?>
					<?php if ( 0 < avada_number_of_featured_images() || get_post_meta( $post->ID, 'pyre_video', true ) ) : ?>
						<div class="fusion-flexslider flexslider post-slideshow">
							<ul class="slides">
								<?php if ( get_post_meta( $post->ID, 'pyre_video', true ) ) : ?>
									<li>
										<div class="full-video">
											<?php echo get_post_meta( $post->ID, 'pyre_video', true ); ?>
										</div>
									</li>
								<?php endif; ?>
								<?php if ( has_post_thumbnail() && 'yes' != get_post_meta( $post->ID, 'pyre_show_first_featured_image', true ) ) : ?>
									<?php $attachment_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ); ?>
									<?php $full_image       = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ); ?>
									<?php $attachment_data  = wp_get_attachment_metadata( get_post_thumbnail_id() ); ?>
									<li>
										<a href="<?php echo $full_image[0]; ?>" data-rel="iLightbox[gallery<?php the_ID(); ?>]" title="<?php echo get_post_field( 'post_excerpt', get_post_thumbnail_id() ); ?>" data-title="<?php echo get_post_field( 'post_title', get_post_thumbnail_id() ); ?>" data-caption="<?php echo get_post_field( 'post_excerpt', get_post_thumbnail_id() ); ?>">
											<img src="<?php echo $attachment_image[0]; ?>" alt="<?php echo get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ); ?>" role="presentation" />
										</a>
									</li>
								<?php endif; ?>
								<?php $i = 2; ?>
								<?php while ( $i <= Avada()->settings->get( 'posts_slideshow_number' ) ) : ?>
									<?php $attachment_new_id = kd_mfi_get_featured_image_id( 'featured-image-' . $i, 'page' ); ?>
									<?php if ( $attachment_new_id ) : ?>
										<?php $attachment_image = wp_get_attachment_image_src( $attachment_new_id, 'full' ); ?>
										<?php $full_image       = wp_get_attachment_image_src( $attachment_new_id, 'full' ); ?>
										<?php $attachment_data  = wp_get_attachment_metadata( $attachment_new_id ); ?>
										<li>
											<a href="<?php echo $full_image[0]; ?>" data-rel="iLightbox[gallery<?php the_ID(); ?>]" title="<?php echo get_post_field( 'post_excerpt', $attachment_new_id ); ?>" data-title="<?php echo get_post_field( 'post_title', $attachment_new_id ); ?>" data-caption="<?php echo get_post_field( 'post_excerpt', $attachment_new_id ); ?>">
												<img src="<?php echo $attachment_image[0]; ?>" alt="<?php echo get_post_meta( $attachment_new_id, '_wp_attachment_image_alt', true ); ?>" role="presentation" />
											</a>
										</li>
									<?php endif; ?>
									<?php $i++; ?>
								<?php endwhile; ?>
							</ul>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			<?php endif; // password check ?>

			<div class="post-content">
				<?php the_content(); ?>
				<?php avada_link_pages(); ?>
			</div>
			<?php if ( ! post_password_required( $post->ID ) ) : ?>
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<?php $woo_thanks_page_id = get_option( 'woocommerce_thanks_page_id' ); ?>
					<?php $is_woo_thanks_page = ( ! get_option( 'woocommerce_thanks_page_id' ) ) ? false : is_page( get_option( 'woocommerce_thanks_page_id' ) ); ?>
					<?php if ( Avada()->settings->get( 'comments_pages' ) && ! is_cart() && ! is_checkout() && ! is_account_page() && ! $is_woo_thanks_page ) : ?>
						<?php wp_reset_query(); ?>
						<?php comments_template(); ?>
					<?php endif; ?>
				<?php else: ?>
					<?php if ( Avada()->settings->get( 'comments_pages' ) ) : ?>
						<?php wp_reset_query(); ?>
						<?php comments_template(); ?>
					<?php endif; ?>
				<?php endif; ?>
			<?php endif; // password check ?>
		</div>
	<?php endwhile; ?>
	<?php wp_reset_query(); ?>
</div>
<?php do_action( 'avada_after_content' ); ?>
<?php get_footer();

// Omit closing PHP tag to avoid "Headers already sent" issues.
