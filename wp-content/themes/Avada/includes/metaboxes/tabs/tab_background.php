<?php

$this->select(
	'page_bg_layout',
	esc_html__('Layout', 'Avada'),
	array(
		'default' => esc_html__( 'Default', 'Avada' ),
		'wide'    => esc_html__( 'Wide', 'Avada' ),
		'boxed'   => esc_html__( 'Boxed', 'Avada' )
	),
	esc_html__( 'Select boxed or wide layout.', 'Avada' )
);

printf( '<h3>%s</h3>', esc_html__( 'Following options only work in boxed mode:', 'Avada' ) );

$this->upload(
	'page_bg',
	esc_html__( 'Background Image for Outer Area', 'Avada' ),
	esc_html__( 'Select an image to use for the outer background.', 'Avada' )
);

$this->text(
	'page_bg_color',
	esc_html__( 'Background Color', 'Avada' ),
	esc_html__( 'Controls the background color for the outer background. Hex code, ex: #000', 'Avada' )
);

$this->select(
	'page_bg_full',
	esc_html__( '100% Background Image', 'Avada' ),
	array(
		'no'  => esc_html__( 'No', 'Avada' ),
		'yes' => esc_html__( 'Yes', 'Avada' )
	),
	esc_html__( 'Choose to have the background image display at 100%.', 'Avada' )
);

$this->select(
	'page_bg_repeat',
	esc_html__( 'Background Repeat', 'Avada' ),
	array(
		'repeat'    => esc_html__( 'Tile', 'Avada' ),
		'repeat-x'  => esc_html__( 'Tile Horizontally', 'Avada' ),
		'repeat-y'  => esc_html__( 'Tile Vertically', 'Avada' ),
		'no-repeat' => esc_html__( 'No Repeat', 'Avada' )
	),
	esc_html__( 'Select how the background image repeats.', 'Avada' )
);

printf( '<h3>%s</h3>', esc_html__( 'Following options work in boxed and wide mode:', 'Avada' ) );

$this->upload(
	'wide_page_bg',
	esc_html__( 'Background Image for Main Content Area', 'Avada' ),
	esc_html__( 'Select an image to use for the main content area.', 'Avada' )
);

$this->text(
	'wide_page_bg_color',
	esc_html__( 'Background Color', 'Avada' ),
	esc_html__( 'Controls the background color for the main content area. Hex code, ex: #000', 'Avada' )
);

$this->select(
	'wide_page_bg_full',
	esc_html__( '100% Background Image', 'Avada' ),
	array(
		'no'  => esc_html__( 'No', 'Avada' ),
		'yes' => esc_html__( 'Yes', 'Avada' )
	),
	esc_html__( 'Choose to have the background image display at 100%.', 'Avada' )
);

$this->select(
	'wide_page_bg_repeat',
	esc_html__( 'Background Repeat', 'Avada' ),
	array(
		'repeat'    => esc_html__( 'Tile', 'Avada' ),
		'repeat-x'  => esc_html__( 'Tile Horizontally', 'Avada' ),
		'repeat-y'  => esc_html__( 'Tile Vertically', 'Avada' ),
		'no-repeat' => esc_html__( 'No Repeat', 'Avada' )
	),
	esc_html__( 'Select how the background image repeats.', 'Avada' )
);

// Omit closing PHP tag to avoid "Headers already sent" issues.
