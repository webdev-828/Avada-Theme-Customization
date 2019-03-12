<?php
/**
 * Photo View Single Event
 * This file contains one event in the photo view
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/photo/single_event.php
 *
 * @package TribeEventsCalendar
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
} ?>

<?php

global $post;

?>

<div class="fusion-post-wrapper">

	<?php if ( has_post_thumbnail() ) : ?>
	<div class="fusion-flexslider flexslider fusion-post-slideshow">
		<ul class="slides">
			<li class="hover-type-<?php echo Avada()->settings->get( 'ec_hover_type' ); ?>">
				<a href="<?php the_permalink(); ?>">
					<span class="screen-reader-text"><?php printf( esc_attr__( 'Go to "%s"', 'Avada' ), get_the_title( $post ) ); ?></span>
					<?php the_post_thumbnail( 'full' ); ?>
				</a>
			</li>
		</ul>
	</div>
	<?php endif; ?>

	<div class="fusion-post-content-wrapper">

		<div class="fusion-post-content post-content">

			<!-- Event Title -->
			<?php do_action( 'tribe_events_before_the_event_title' ); ?>
			<h2 class="entry-title">
				<a class="url" href="<?php echo esc_url( tribe_get_event_link() ); ?>" title="<?php the_title() ?>" rel="bookmark">
					<?php the_title(); ?>
				</a>
			</h2>
			<?php do_action( 'tribe_events_after_the_event_title' ); ?>

			<!-- Event Meta -->
			<?php do_action( 'tribe_events_before_the_meta' ); ?>
			<div class="fusion-single-line-meta">
				<div class="updated published time-details">
					<?php if ( ! empty( $post->distance ) ) : ?>
						<strong>[<?php echo tribe_get_distance_with_unit( $post->distance ); ?>]</strong>
					<?php endif; ?>
					<?php echo tribe_events_event_schedule_details(); ?>
				</div>
			</div><!-- .tribe-events-event-meta -->
			<?php do_action( 'tribe_events_after_the_meta' ); ?>

			<div class="fusion-content-sep"></div>

			<!-- Event Content -->
			<?php do_action( 'tribe_events_before_the_content' ); ?>
			<div class="fusion-post-content-container">
				<?php echo tribe_events_get_the_excerpt() ?>
			</div>
			<?php do_action( 'tribe_events_after_the_content' ) ?>

			<div class="fusion-meta-info">
				<div class="fusion-alignleft">
					<?php
						if ( Avada()->settings->get( 'post_meta_read' ) ) {
							$link_target = '';
							if ( fusion_get_page_option( 'link_icon_target', get_the_ID() ) == 'yes' ||
								fusion_get_page_option( 'post_links_target', get_the_ID() ) == 'yes' ) {
								$link_target = ' target="_blank"';
							}
							printf( '<a href="%s" class="fusion-read-more"%s>%s</a>', get_permalink(), $link_target, esc_html__( 'Find Out More', 'Avada' ) );
						}
					?>
				</div>
			</div>

		</div>

	</div><!-- /.tribe-events-event-details -->

</div><!-- /.tribe-events-photo-event-wrap -->
