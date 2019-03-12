<div id="sidebar-2" <?php Avada()->layout->add_class( 'sidebar_2_class' ); ?> <?php Avada()->layout->add_style( 'sidebar_2_style' ); ?>>
	<?php if ( 'right' == Avada()->layout->sidebars['position'] ) : ?>
		<?php echo avada_display_sidenav( Avada::c_pageID() ); ?>

		<?php if ( class_exists( 'Tribe__Events__Main' ) && is_singular( 'tribe_events' ) ) : ?>
			<?php do_action( 'tribe_events_single_event_before_the_meta' ); ?>
			<?php tribe_get_template_part( 'modules/meta' ); ?>
			<?php do_action( 'tribe_events_single_event_after_the_meta' ); ?>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ( isset( Avada()->layout->sidebars['sidebar_2'] ) && Avada()->layout->sidebars['sidebar_2'] ) : ?>
		<?php generated_dynamic_sidebar( Avada()->layout->sidebars['sidebar_2'] ); ?>
	<?php endif; ?>
</div>
