<?php

/**
 * Search Page
 *
 * @var  array  	any existing settings
 * @return array 	existing sections + search_page
 *
 */
function avada_options_section_search_page( $sections ) {

	$sections['search_page'] = array(
		'label'    => esc_html__( 'Search Page', 'Avada' ),
		'id'       => 'heading_search_page',
		'priority' => 23,
		'icon'     => 'el-icon-search',
		'fields'   => array(
			'search_layout' => array(
				'label'       => esc_html__( 'Search Results Layout', 'Avada' ),
				'description' => esc_html__( 'Controls the layout for the search results page.', 'Avada' ),
				'id'          => 'search_layout',
				'default'     => 'Grid',
				'type'        => 'select',
				'choices'     => array(
					'Large'            => esc_html__( 'Large', 'Avada' ),
					'Medium'           => esc_html__( 'Medium', 'Avada' ),
					'Large Alternate'  => esc_html__( 'Large Alternate', 'Avada' ),
					'Medium Alternate' => esc_html__( 'Medium Alternate', 'Avada' ),
					'Grid'             => esc_html__( 'Grid', 'Avada' ),
					'Timeline'         => esc_html__( 'Timeline', 'Avada' ),
				),
			),
			'search_content' => array(
				'label'       => esc_html__( 'Search Results Content', 'Avada' ),
				'description' => esc_html__( 'Controls the type of content that displays in search results.', 'Avada' ),
				'id'          => 'search_content',
				'default'     => 'Posts and Pages',
				'type'        => 'select',
				'choices'     => array(
					'Posts and Pages' => esc_html__( 'Posts and Pages', 'Avada' ),
					'Only Posts'      => esc_html__( 'Only Posts', 'Avada' ),
					'Only Pages'      => esc_html__( 'Only Pages', 'Avada' ),
				)
			),
			'search_excerpt' => array(
				'label'       => esc_html__( 'Search Results Excerpt', 'Avada' ),
				'description' => esc_html__( 'Turn on to display the excerpt for search results.', 'Avada' ),
				'id'          => 'search_excerpt',
				'default'     => '1',
				'type'        => 'switch'
			),
			'search_results_per_page' => array(
				'label'       => esc_html__( 'Number of Search Results Per Page', 'Avada' ),
				'description' => esc_html__( 'Controls the number of search results per page.', 'Avada' ),
				'id'          => 'search_results_per_page',
				'default'     => '10',
				'type'        => 'slider',
				'choices'     => array(
					'min'  => '1',
					'max'  => '100',
					'step' => '1',
				),
			),
			'search_featured_images' => array(
				'label'       => esc_html__( 'Featured Images for Search Results', 'Avada' ),
				'description' => esc_html__( 'Turn on to display featured images for search results.', 'Avada' ),
				'id'          => 'search_featured_images',
				'default'     => '1',
				'type'        => 'switch'
			),
			'search_new_search_position' => array(
				'label'       => esc_html__( 'Search Field Position', 'Avada' ),
				'description' => esc_html__( 'Controls the position of the search bar on the search results page.', 'Avada' ),
				'id'          => 'search_new_search_position',
				'default'     => 'top',
				'type'        => 'radio-buttonset',
				'choices'     => array(
					'top'    => esc_html__( 'Above Results', 'Avada' ),
					'bottom' => esc_html__( 'Below Results', 'Avada' ),
					'hidden' => esc_html__( 'Hide', 'Avada' )
				)
			),
		),
	);

	return $sections;

}
