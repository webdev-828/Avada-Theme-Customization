<?php

$this->select(
	'display_footer',
	esc_html__( 'Display Footer Widget Area', 'Avada' ),
	array(
		'default' => esc_html__( 'Default', 'Avada' ),
		'yes'     => esc_html__( 'Yes', 'Avada' ),
		'no'      => esc_html__( 'No', 'Avada' )
	),
	esc_html__( 'Choose to show or hide the footer.', 'Avada' )
);

$this->select(
	'display_copyright',
	esc_html__( 'Display Copyright Area', 'Avada' ),
	array(
		'default' => esc_html__( 'Default', 'Avada' ),
		'yes'     => esc_html__( 'Yes', 'Avada' ),
		'no'      => esc_html__( 'No', 'Avada' )
	),
	esc_html__( 'Choose to show or hide the copyright area.', 'Avada' )
);

$this->select(
	'footer_100_width',
	esc_html__( '100% Footer Width', 'Avada' ),
	array(
		'default' => esc_html__( 'Default', 'Avada' ),
		'yes'     => esc_html__( 'Yes', 'Avada' ),
		'no'      => esc_html__( 'No', 'Avada' )
	),
	esc_html__( 'Choose to set footer width to 100% of the browser width. Select "No" for site width.', 'Avada' )
);

// Omit closing PHP tag to avoid "Headers already sent" issues.
