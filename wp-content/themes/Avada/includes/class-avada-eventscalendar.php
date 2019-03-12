<?php

class Avada_EventsCalendar {

	public function __construct() {
		add_action( 'tribe_events_before_the_title', array( $this, 'before_the_title' ) );
		add_action( 'tribe_events_after_the_title', array( $this, 'after_the_title' ) );

		//add_filter( 'tribe_the_prev_event_link', array( $this, 'remove_arrow_from_prev_link' ) );
		//add_filter( 'tribe_the_next_event_link', array( $this, 'remove_arrow_from_next_link' ) );

		add_filter( 'tribe_events_mobile_breakpoint', array( $this, 'set_mobile_breakpoint' ) );
		add_action( 'tribe_events_bar_after_template', array( $this, 'add_clearfix' ) );
	}

	public function before_the_title() {
		echo '<div class="fusion-events-before-title">';
	}

	public function after_the_title() {
		echo '</div>';
	}

	public function remove_arrow_from_prev_link( $anchor ) {
		return tribe_get_prev_event_link( '%title%' );
	}

	public function remove_arrow_from_next_link( $anchor ) {
		return tribe_get_next_event_link( '%title%' );
	}

	public function set_mobile_breakpoint() {
		return intval( Avada()->settings->get( 'content_break_point' ) );
	}

	public static function render_single_event_title() {
		$event_id = get_the_ID();
		?>
		<div class="fusion-events-single-title-content">
			<?php the_title( '<h2 class="tribe-events-single-event-title summary entry-title">', '</h2>' ); ?>

			<div class="tribe-events-schedule updated published tribe-clearfix">
				<?php echo tribe_events_event_schedule_details( $event_id, '<h3>', '</h3>' ); ?>
				<?php if ( tribe_get_cost() ) : ?>
					<span class="tribe-events-divider">|</span>
					<span class="tribe-events-cost"><?php echo tribe_get_cost( null, true ) ?></span>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	public function add_clearfix() {
		echo '<div class="clearfix"></div>';
	}
}
