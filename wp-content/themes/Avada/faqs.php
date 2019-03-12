<?php
// Template Name: FAQs
get_header(); ?>

<div id="content" class="fusion-faqs" <?php Avada()->layout->add_style( 'content_style' ); ?>>
	<?php // Get the content of the faq page itself ?>
	<?php while ( have_posts() ): the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<?php // Get rich snippets of the faq page ?>
			<?php echo avada_render_rich_snippets_for_pages(); ?>

			<?php // Get featured images of the faq page ?>
			<?php echo avada_featured_images_for_pages(); ?>

			<?php // Render the content of the faq page ?>
			<div class="post-content">
				<?php the_content(); ?>
				<?php avada_link_pages(); ?>
			</div>
		</div>
	<?php endwhile; ?>

	<?php // Check if the post is password protected ?>
	<?php if ( ! post_password_required( $post->ID ) ) : ?>

		<?php // Get faq terms ?>
		<?php $faq_terms = get_terms( 'faq_category' ); ?>

		<?php // Check if we should display filters ?>
		<?php if ( 'no' != Avada()->settings->get( 'faq_filters' ) && $faq_terms ) : ?>

			<ul class="fusion-filters clearfix">

				<?php // Check if the "All" filter should be displayed ?>
				<?php if ( 'yes' == Avada()->settings->get( 'faq_filters' ) ) : ?>
					<li class="fusion-filter fusion-filter-all fusion-active">
						<a data-filter="*" href="#"><?php echo apply_filters( 'avada_faq_all_filter_name', esc_html( 'All', 'Avada' ) ); ?></a>
					</li>
					<?php $first_filter = false; ?>
				<?php else : ?>
					<?php $first_filter = true; ?>
				<?php endif; ?>

				<?php // Loop through the terms to setup all filters ?>
				<?php foreach ( $faq_terms as $faq_term ) : ?>
					<?php // If the "All" filter is disabled, set the first real filter as active ?>
					<?php if ( $first_filter ) : ?>
						<li class="fusion-filter fusion-active">
							<a data-filter=".<?php echo urldecode( $faq_term->slug ); ?>" href="#"><?php echo $faq_term->name; ?></a>
						</li>
						<?php $first_filter = false; ?>
					<?php else : ?>
						<li class="fusion-filter fusion-hidden">
							<a data-filter=".<?php echo urldecode( $faq_term->slug ); ?>" href="#"><?php echo $faq_term->name; ?></a>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>

		<?php endif; ?>

		<div class="fusion-faqs-wrapper">
			<div class="accordian fusion-accordian">
				<div class="panel-group" id="accordian-one">
				<?php $faq_items = new WP_Query( array( 'post_type' => 'avada_faq', 'posts_per_page' => -1 ) ); ?>
				<?php $count = 0; ?>
				<?php while ( $faq_items->have_posts() ): $faq_items->the_post(); ?>
					<?php $count++; ?>
					<?php //Get all terms of the post and it as classes; needed for filtering ?>
					<?php $post_classes = ''; ?>
					<?php $post_terms = get_the_terms( $post->ID, 'faq_category' ); ?>
					<?php if ( $post_terms ) : ?>
						<?php foreach ( $post_terms as $post_term ) : ?>
							<?php $post_classes .= urldecode( $post_term->slug ) . ' '; ?>
						<?php endforeach; ?>
					<?php endif; ?>

					<div class="fusion-panel panel-default fusion-faq-post <?php echo $post_classes; ?>">
						<?php // get the rich snippets for the post ?>
						<?php echo avada_render_rich_snippets_for_pages(); ?>

						<div class="panel-heading">
							<h4 class="panel-title toggle">
								<a data-toggle="collapse" class="collapsed" data-parent="#accordian-one" data-target="#collapse-<?php echo get_the_ID(); ?>" href="#collapse-<?php echo get_the_ID(); ?>">
									<div class="fusion-toggle-icon-wrapper"><i class="fa-fusion-box"></i></div>
									<div class="fusion-toggle-heading"><?php echo get_the_title(); ?></div>
								</a>
							</h4>
						</div>

						<div id="collapse-<?php echo get_the_ID(); ?>" class="panel-collapse collapse">
							<div class="panel-body toggle-content post-content">
								<?php // Render the featured image of the post ?>
								<?php if ( Avada()->settings->get( 'faq_featured_image' ) && has_post_thumbnail() ) : ?>
									<?php $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ); ?>

									<?php if ( $featured_image[0] ) : ?>
										<div class="flexslider post-slideshow">
											<ul class="slides">
												<li>
													<a href="<?php echo $featured_image[0]; ?>" data-rel="iLightbox[gallery]" data-title="<?php echo get_post_field( 'post_title', get_post_thumbnail_id() ); ?>" data-caption="<?php echo get_post_field( 'post_excerpt', get_post_thumbnail_id() ); ?>">
														<span class="screen-reader-text"><?php esc_attr_e( 'View Larger Image', 'Avada' ); ?></span>
														<?php echo get_the_post_thumbnail( get_the_ID(), 'blog-large' ); ?>
													</a>
												</li>
											</ul>
										</div>
									<?php endif; ?>
								<?php endif; ?>

								<?php the_content(); ?>
							</div>
						</div>
					</div>
					<?php endwhile; // loop through faq_items ?>
				</div>
			</div>
		</div>
	<?php endif; // password check ?>
</div>
<?php wp_reset_query(); ?>
<?php do_action( 'avada_after_content' ); ?>
<?php get_footer();

// Omit closing PHP tag to avoid "Headers already sent" issues.
