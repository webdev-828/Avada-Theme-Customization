<?php
// Template Name: Portfolio Grid
get_header(); ?>
	<div id="content" <?php Avada()->layout->add_class( 'content_class' ); ?> <?php Avada()->layout->add_style( 'content_style' ); ?>>
		<?php get_template_part( 'templates/portfolio', 'layout' ); ?>
	</div>
	<?php do_action( 'avada_after_content' ); ?>
<?php get_footer();

// Omit closing PHP tag to avoid "Headers already sent" issues.
