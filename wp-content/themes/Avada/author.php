<?php get_header(); ?>
<div id="content" <?php Avada()->layout->add_class( 'content_class' ); ?> <?php Avada()->layout->add_style( 'content_style' ); ?>>
	<?php
	/**
	 * avada_author_info hook
	 *
	 * @hooked avada_render_author_info - 10 (renders the HTML markup of the author info)
	 */
	do_action( 'avada_author_info' );
	?>

	<?php get_template_part( 'templates/blog', 'layout' ); ?>
</div>
<?php do_action( 'avada_after_content' ); ?>
<?php get_footer();

// Omit closing PHP tag to avoid "Headers already sent" issues.
