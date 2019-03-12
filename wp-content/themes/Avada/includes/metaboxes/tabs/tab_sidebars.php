<?php

sidebar_generator::edit_form();

$this->select(
	'sidebar_position',
	esc_html__( 'Sidebar 1 Position', 'Avada' ),
	array(
		'default' => esc_html__( 'Default', 'Avada' ),
		'right'   => esc_html__( 'Right', 'Avada' ),
		'left'    => esc_html__( 'Left', 'Avada' )
	),
	esc_html__( 'Select the sidebar 1 position. If sidebar 2 is selected, it will display on the opposite side.', 'Avada' )
);

$this->text(
	'sidebar_bg_color',
	esc_html__( 'Sidebar Background Color', 'Avada' ),
	esc_html__( 'Controls the background color of the sidebar. Hex code, ex: #000', 'Avada' )
);

// Omit closing PHP tag to avoid "Headers already sent" issues.
