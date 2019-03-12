<?php

$this->select(
	'portfolio_width_100',
	esc_html__( 'Use 100% Width Page', 'Avada' ),
	array(
		'no'  => esc_html__( 'No', 'Avada' ),
		'yes' => esc_html__( 'Yes', 'Avada' )
	),
	esc_html__( 'Choose to set a portfolio template page to 100% browser width.', 'Avada' )
);

$this->select(
	'portfolio_content_length',
	esc_html__( 'Excerpt or Full Portfolio Content', 'Avada' ),
	array(
		'default'      => esc_html__( 'Default', 'Avada' ),
		'excerpt'      => esc_html__( 'Excerpt', 'Avada' ),
		'full_content' => esc_html__( 'Full Content', 'Avada' )
	),
	esc_html__( 'Choose to show a text excerpt or full content.', 'Avada' )
);

$this->text(
	'portfolio_excerpt',
	esc_html__( 'Excerpt Length', 'Avada' ),
	esc_html__( 'Insert the number of words you want to show in the post excerpts.', 'Avada' )
);

$types = get_terms( 'portfolio_category', 'hide_empty=0' );
$types_array[0] = esc_html__( 'All categories', 'Avada' );

if ( $types ) {

	foreach ( $types as $type ) {
		$types_array[$type->term_id] = $type->name;
	}

	$this->multiple(
		'portfolio_category',
		esc_html__( 'Portfolio Type', 'Avada' ),
		$types_array,
		esc_html__( 'Choose what portfolio category you want to display on this page. Leave blank for all categories.', 'Avada' )
	);

}

$this->select(
	'portfolio_filters',
	esc_html__( 'Show Portfolio Filters', 'Avada' ),
	array(
		'yes'             => esc_html__( 'Show', 'Avada' ),
		'yes_without_all' => esc_html__( 'Show without "All"', 'Avada' ),
		'no'              => esc_html__( 'Hide', 'Avada' )
	),
	esc_html__( 'Choose to show or hide the portfolio filters.', 'Avada' )
);

$this->select(
	'portfolio_text_layout',
	esc_html__( 'Portfolio Text Layout', 'Avada' ),
	array(
		'default' => esc_html__( 'Default', 'Avada' ),
		'boxed'   => esc_html__( 'Boxed', 'Avada' ),
		'unboxed' => esc_html__( 'Unboxed', 'Avada' )
	),
	esc_html__( 'Select if the portfolio text layouts are boxed or unboxed.', 'Avada' )
);

$this->select(
	'portfolio_featured_image_size',
	esc_html__( 'Portfolio Featured Image Size', 'Avada' ),
	array(
		'default' => esc_html__( 'Default', 'Avada' ),
		'cropped' => esc_html__( 'Fixed', 'Avada' ),
		'full'    => esc_html__( 'Auto', 'Avada' )
	),
	esc_html__( 'Choose if the featured images are fixed (cropped) or auto (full image ratio) for all portfolio column page templates. IMPORTANT: Fixed images work best with smaller site widths. Auto images work best with larger site widths.', 'Avada' )
);

$this->text(
	'portfolio_column_spacing',
	esc_html__( 'Column Spacing', 'Avada' ),
	esc_html__( 'Insert the amount of spacing between portfolio items. ex: 7px', 'Avada' )
);

// Omit closing PHP tag to avoid "Headers already sent" issues.
