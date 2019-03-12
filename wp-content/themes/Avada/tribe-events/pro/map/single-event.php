<?php
/**
 * Map View Single Event
 * This file contains one event in the map
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/map/single_event.php
 *
 * @package TribeEventsCalendar
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// Setup an array of venue details for use later in the template
$venue_details = tribe_get_venue_details();

// Venue microformats
$has_venue_address = ( ! empty( $venue_details['address'] ) ) ? ' location' : '';

// Organizer
$organizer = tribe_get_organizer();
?>

<div class="<?php if ( has_post_thumbnail() ): echo 'fusion-tribe-has-featured-image'; else: echo 'fusion-tribe-no-featured-image'; endif; ?>">
	<!-- Event Cost -->
	<?php if ( tribe_get_cost() ) : ?>
		<div class="tribe-events-event-cost">
			<span><?php echo tribe_get_cost( null, true ); ?></span>
		</div>
	<?php endif; ?>

	<?php
	if ( has_post_thumbnail() ):
		$url = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
	?>
	<div class="fusion-tribe-primary-info">
		<div class="hover-type-<?php echo Avada()->settings->get( 'ec_hover_type' ); ?>">
			<!-- Event Title -->
			<?php do_action( 'tribe_events_before_the_event_title' ) ?>
			<h3 class="tribe-events-list-event-title entry-title summary">
				<a class="url" href="<?php echo esc_url( tribe_get_event_link() ); ?>" title="<?php the_title() ?>" rel="bookmark">
					<?php the_title() ?>
				</a>
			</h3>
			<?php do_action( 'tribe_events_after_the_event_title' ) ?>

			<!-- Event Image -->
			<a href="<?php the_permalink(); ?>">
				<?php if (  Avada()->settings->get( 'ec_bg_list_view' ) == 'cover' ): ?>
				<span class="tribe-events-event-image" style="background-image: url(<?php echo $url; ?>); -webkit-background-size: <?php echo Avada()->settings->get( 'ec_bg_list_view' ); ?>; background-size: <?php echo Avada()->settings->get( 'ec_bg_list_view' ); ?>; background-position: center center;"></span>
				<span class="fusion-tribe-events-event-image-responsive"><?php the_post_thumbnail(); ?></span>
				<?php else: ?>
				<?php the_post_thumbnail(); ?>
				<?php endif; ?>
			</a>
		</div>
	</div>
	<?php endif; ?>

	<div class="fusion-tribe-secondary-info">
		<!-- Event Meta -->
		<?php do_action( 'tribe_events_before_the_meta' ) ?>
		<?php tribe_get_template_part( 'list/meta' ); ?>
		<?php do_action( 'tribe_events_after_the_meta' ) ?>

		<!-- Event Content -->
		<?php do_action( 'tribe_events_before_the_content' ) ?>
		<div class="tribe-events-list-event-description tribe-events-content description entry-summary">
			<?php the_excerpt() ?>
			<a href="<?php echo esc_url( tribe_get_event_link() ); ?>" class="fusion-read-more" rel="bookmark"><?php esc_html_e( 'Find out more', 'the-events-calendar' ) ?></a>
		</div><!-- .tribe-events-list-event-description -->
		<?php do_action( 'tribe_events_after_the_content' ); ?>
	</div>
</div>
