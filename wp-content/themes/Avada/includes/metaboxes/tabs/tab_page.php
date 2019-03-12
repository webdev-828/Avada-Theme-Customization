<?php

$this->text(
	'main_top_padding',
	esc_html__( 'Page Content Top Padding', 'Avada' ),
	esc_html__( 'In pixels ex: 20px. Leave empty for default value.', 'Avada' )
);

$this->text(
	'main_bottom_padding',
	esc_html__( 'Page Content Bottom Padding', 'Avada' ),
	esc_html__( 'In pixels ex: 20px. Leave empty for default value.', 'Avada' )
);

$this->text(
	'hundredp_padding',
	esc_html__( '100% Width Left/Right Padding', 'Avada' ),
	esc_html__( 'This option controls the left/right padding for page content when using 100% site width or 100% width page template.  Enter value in px. ex: 20px.', 'Avada' )
);

$screen = get_current_screen();

if ( 'page' == $screen->post_type ) {
	$this->select(
		'show_first_featured_image',
		esc_html__( 'Disable First Featured Image', 'Avada' ),
		array(
			'no'  => esc_html__( 'No', 'Avada' ),
			'yes' => esc_html__( 'Yes', 'Avada' )
		),
		esc_html__( 'Disable the 1st featured image on page.', 'Avada' )
	);
}

if ( 'tribe_events' == $screen->post_type ) {
	$this->select(
		'share_box',
		esc_html__( 'Show Social Share Box', 'Avada' ),
		array(
			'default' => esc_html__( 'Default', 'Avada' ),
			'yes'     => esc_html__( 'Show', 'Avada' ),
			'no'      => esc_html__( 'Hide', 'Avada' )
		),
		esc_html__( 'Choose to show or hide the social share box', 'Avada' )
	);
}

// Omit closing PHP tag to avoid "Headers already sent" issues.
