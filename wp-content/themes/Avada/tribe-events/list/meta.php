<?php
// Setup an array of venue details for use later in the template
$venue_details = tribe_get_venue_details();

// Venue microformats
$has_venue_address = ( ! empty( $venue_details['address'] ) ) ? ' location' : '';
?>
<div class="tribe-events-event-meta vcard">
	<div class="author <?php echo esc_attr( $has_venue_address ); ?>">

		<?php if ( ! has_post_thumbnail() ): ?>
		<div class="fusion-tribe-events-headline">
			<!-- Event Title -->
			<?php do_action( 'tribe_events_before_the_event_title' ) ?>
			<h3 class="tribe-events-list-event-title entry-title summary">
				<a class="url" href="<?php echo esc_url( tribe_get_event_link() ); ?>" title="<?php the_title() ?>" rel="bookmark">
					<?php the_title() ?>
				</a>
			</h3>
			<?php do_action( 'tribe_events_after_the_event_title' ) ?>
		</div>
		<?php endif; ?>

		<!-- Schedule & Recurrence Details -->
		<div class="updated published time-details">
			<?php echo tribe_events_event_schedule_details() ?>
		</div>

		<?php if ( $venue_details ) : ?>
			<!-- Venue Display Info -->
			<div class="tribe-events-venue-details">
				<?php if ( $venue_details['name'] ): ?>
					<?php echo $venue_details['name']; ?>
				<?php endif; ?>

				<?php echo tribe_get_full_address(); ?>
			</div> <!-- .tribe-events-venue-details -->

			<?php if ( tribe_show_google_map_link() ) : ?>
				<div class="fusion-tribe-events-venue-details-map">
					<a class="tribe-events-gmap" href="<?php echo tribe_get_map_link(); ?>">Google Map</a>
				</div>
			<?php endif; ?>
		<?php endif; ?>

	</div>
</div><!-- .tribe-events-event-meta -->
