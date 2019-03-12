<?php
$this->select(
	'display_header',
	esc_html__( 'Display Header', 'Avada' ),
	array(
		'yes' => esc_html__( 'Yes', 'Avada' ),
		'no'  => esc_html__( 'No', 'Avada' )
	),
	esc_html__( 'Choose to show or hide the header.', 'Avada' )
);

$this->select(
	'header_100_width',
	esc_html__( '100% Header Width', 'Avada' ),
	array(
		'default' => esc_html__( 'Default', 'Avada' ),
		'yes'     => esc_html__( 'Yes', 'Avada' ),
		'no'      => esc_html__( 'No', 'Avada' )
	),
	esc_html__( 'Choose to set header width to 100% of the browser width. Select "No" for site width.', 'Avada' )
);

$this->upload(
	'header_bg',
	esc_html__( 'Background Image', 'Avada' ),
	esc_html__( 'Select an image for the header background. If left empty, the header background color will be used. For top headers the image displays on top of the header background color and will only display if header opacity is set to 1. For side headers the image displays behind the header background color so the header opacity must be set below 1 to see the image.', 'Avada' )
);

$this->text(
	'header_bg_color',
	esc_html__( 'Background Color', 'Avada' ),
	esc_html__( 'Controls the background color for the header. Hex code or rgba value, ex: #000', 'Avada' )
);

$this->text(
	'header_bg_opacity',
	esc_html__( 'Background Opacity', 'Avada' ),
	esc_html__( 'Controls the opacity of the header background color. Ranges between 0 (transparent) and 1 (opaque). For top headers opacity set below 1 will remove the header height completely. For side headers opacity set below 1 will display a color overlay.', 'Avada' )
);

$this->select(
	'header_bg_full',
	esc_html__( '100% Background Image', 'Avada' ),
	array(
		'no'  => esc_html__( 'No', 'Avada' ),
		'yes' => esc_html__( 'Yes', 'Avada' )
	),
	esc_html__( 'Choose to have the background image display at 100%.', 'Avada' )
);

$this->select(
	'header_bg_repeat',
	esc_html__( 'Background Repeat', 'Avada' ),
	array(
		'repeat'    => esc_html__( 'Tile', 'Avada' ),
		'repeat-x'  => esc_html__( 'Tile Horizontally', 'Avada' ),
		'repeat-y'  => esc_html__( 'Tile Vertically', 'Avada' ),
		'no-repeat' => esc_html__( 'No Repeat', 'Avada' )
	),
	esc_html__( 'Select how the background image repeats.', 'Avada' )
);

$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
$menu_select['default'] = 'Default Menu';

foreach ( $menus as $menu ) {
	$menu_select[$menu->term_id] = $menu->name;
}

$this->select(
	'displayed_menu',
	esc_html__( 'Main Navigation Menu', 'Avada' ),
	$menu_select,
	esc_html__( 'Select which menu displays on this page.', 'Avada' )
);

// Omit closing PHP tag to avoid "Headers already sent" issues.
