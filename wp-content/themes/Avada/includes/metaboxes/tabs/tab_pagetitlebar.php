<?php

$this->select(
	'page_title',
	esc_html__( 'Page Title Bar', 'Avada' ),
	array(
		'default'         => esc_html__( 'Default', 'Avada' ),
		'yes'             => esc_html__( 'Show Bar and Content', 'Avada' ),
		'yes_without_bar' => esc_html__( 'Show Content Only', 'Avada' ),
		'no'              => esc_html__( 'Hide', 'Avada' )
	),
	esc_html__( 'Choose to show or hide the page title bar.', 'Avada' )
);

$this->select(
	'page_title_text',
	esc_html__( 'Page Title Bar Text', 'Avada' ),
	array(
		'default' => esc_html__( 'Default', 'Avada' ),
		'yes'     => esc_html__( 'Show', 'Avada' ),
		'no'      => esc_html__( 'Hide', 'Avada' )
	),
	esc_html__( 'Choose to show or hide the page title bar text.', 'Avada' )
);

$this->select(
	'page_title_text_alignment',
	esc_html__( 'Page Title Bar Text Alignment', 'Avada' ),
	array(
		'default' => esc_html__( 'Default', 'Avada' ),
		'left'    => esc_html__( 'Left', 'Avada' ),
		'center'  => esc_html__( 'Center', 'Avada' ),
		'right'   => esc_html__( 'Right', 'Avada' )
	),
	esc_html__( 'Choose the title and subhead text alignment', 'Avada' )
);

$this->select(
	'page_title_100_width',
	esc_html__( '100% Page Title Width', 'Avada' ),
	array(
		'default' => esc_html__( 'Default', 'Avada' ),
		'yes'     => esc_html__( 'Yes', 'Avada' ),
		'no'      => esc_html__( 'No', 'Avada' )
	),
	esc_html__( 'Choose to set the page title content to 100% of the browser width. Select "No" for site width. Only works with wide layout mode.', 'Avada' )
);

$this->textarea(
	'page_title_custom_text',
	esc_html__( 'Page Title Bar Custom Text', 'Avada' ),
	esc_html__( 'Insert custom text for the page title bar.', 'Avada' )
);

$this->text(
	'page_title_text_size',
	esc_html__( 'Page Title Bar Text Size', 'Avada' ),
	esc_html__( 'In pixels, default is 18px.', 'Avada' )
);

$this->textarea(
	'page_title_custom_subheader',
	esc_html__( 'Page Title Bar Custom Subheader Text', 'Avada' ),
	esc_html__( 'Insert custom subhead text for the page title bar.', 'Avada' )
);

$this->text(
	'page_title_custom_subheader_text_size',
	esc_html__( 'Page Title Bar Subhead Text Size', 'Avada' ),
	esc_html__( 'In pixels, default is 10px.', 'Avada' )
);

$this->text(
	'page_title_font_color',
	esc_html__( 'Page Title Font Color', 'Avada' ),
	esc_html__( 'Controls the text color of the page title fonts.', 'Avada' )
);

$this->text(
	'page_title_height',
	esc_html__( 'Page Title Bar Height', 'Avada' ),
	esc_html__( 'Set the height of the page title bar. In pixels ex: 100px.', 'Avada' )
);

$this->text(
	'page_title_mobile_height',
	esc_html__( 'Page Title Bar Mobile Height', 'Avada' ),
	esc_html__( 'Set the height of the page title bar on mobile. In pixels ex: 100px.', 'Avada' )
);

$this->upload(
	'page_title_bar_bg',
	esc_html__( 'Page Title Bar Background', 'Avada' ),
	esc_html__( 'Select an image to use for the page title bar background.', 'Avada' )
);

$this->upload(
	'page_title_bar_bg_retina',
	esc_html__( 'Page Title Bar Background Retina', 'Avada' ),
	esc_html__( 'Select an image to use for retina devices.', 'Avada' )
);

$this->text(
	'page_title_bar_bg_color',
	esc_html__( 'Page Title Bar Background Color', 'Avada' ),
	esc_html__( 'Controls the background color of the page title bar. Hex code, ex: #000', 'Avada' )
);

$this->text(
	'page_title_bar_borders_color',
	esc_html__( 'Page Title Bar Borders Color', 'Avada' ),
	esc_html__( 'Controls the border color of the page title bar. Hex code, ex: #000', 'Avada' )
);

$this->select(
	'page_title_bar_bg_full',
	esc_html__( '100% Background Image', 'Avada' ),
	array(
		'default' => esc_html__( 'Default', 'Avada' ),
		'no'      => esc_html__( 'No', 'Avada' ),
		'yes'     => esc_html__( 'Yes', 'Avada' )
	),
	esc_html__( 'Choose to have the background image display at 100%.', 'Avada' )
);

$this->select(
	'page_title_bg_parallax',
	esc_html__( 'Parallax Background Image', 'Avada' ),
	array(
		'default' => esc_html__( 'Default', 'Avada' ),
		'no'      => esc_html__( 'No', 'Avada' ),
		'yes'     => esc_html__( 'Yes', 'Avada' )
	),
	esc_html__( 'Choose a parallax scrolling effect for the background image.', 'Avada' )
);

$this->select(
	'page_title_breadcrumbs_search_bar',
	esc_html__( 'Breadcrumbs/Search Bar', 'Avada' ),
	array(
		'default'     => esc_html__( 'Default', 'Avada' ),
		'breadcrumbs' => esc_html__( 'Breadcrumbs', 'Avada' ),
		'searchbar'   => esc_html__( 'Search Bar', 'Avada' ),
		'none'        => esc_html__( 'None', 'Avada' )
	),
	esc_html__( 'Choose to display the breadcrumbs, search bar or none.', 'Avada' )
);

// Omit closing PHP tag to avoid "Headers already sent" issues.
