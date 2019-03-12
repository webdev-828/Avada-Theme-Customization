<div id="sidebar" <?php Avada()->layout->add_class( 'sidebar_1_class' ); ?> <?php Avada()->layout->add_style( 'sidebar_1_style' ); ?>>
	<?php if ( ! Avada()->template->has_sidebar() || 'left' == Avada()->layout->sidebars['position'] || ( 'right' == Avada()->layout->sidebars['position'] && ! Avada()->template->double_sidebars() ) ) : ?>
		<?php echo avada_display_sidenav( Avada::c_pageID() ); ?>

		<?php if ( class_exists( 'Tribe__Events__Main' ) && is_singular( 'tribe_events' ) ) : ?>
			<?php do_action( 'tribe_events_single_event_before_the_meta' ); ?>
			<?php tribe_get_template_part( 'modules/meta' ); ?>
			<?php do_action( 'tribe_events_single_event_after_the_meta' ); ?>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ( isset( Avada()->layout->sidebars['sidebar_1'] ) && Avada()->layout->sidebars['sidebar_1'] ) : ?>
		<?php generated_dynamic_sidebar( Avada()->layout->sidebars['sidebar_1'] ); ?>
	<?php endif; ?>
</div>
