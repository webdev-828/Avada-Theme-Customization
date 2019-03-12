<?php

/**
 * Sidebar settings
 *
 * @var  array  	any existing settings
 * @return array 	existing sections + sidebars
 *
 */
function avada_options_section_sidebars( $sections ) {

	/**
	 * Register sidebar options for blog/portfolio/woocommerce archive pages
	 */
	global $wp_registered_sidebars;
	$sidebar_options[] = 'None';

	for ( $i =0; $i < 1; $i++ ) {

		// sidebar_generator::get_sidebars();
		$sidebars = $wp_registered_sidebars;
		// var_dump( $sidebars );
		if ( is_array( $sidebars ) && ! empty( $sidebars ) ) {
			foreach( $sidebars as $sidebar ) {
				$sidebar_options[] = $sidebar['name'];
			}
		}

		$sidebars = sidebar_generator::get_sidebars();
		if ( is_array( $sidebars ) && ! empty( $sidebars ) ) {
			foreach ( $sidebars as $key => $value ) {
				$sidebar_options[] = $value;
			}
		}
	}
	$sidebars_array = array();
	foreach( $sidebar_options as $sidebar_option ) {
		$sidebars_array[ $sidebar_option ] = $sidebar_option;
	}
	$sidebar_options = $sidebars_array;

	$sections['sidebars'] = array(
		'label'    => esc_html__( 'Sidebars', 'Avada' ),
		'id'       => 'heading_sidebars',
		'is_panel' => true,
		'priority' => 10,
		'icon'     => 'el-icon-website',
		'fields'   => array(
			'sidebars_styling' => array(
				'label'       => esc_html__( 'Sidebar Styling', 'Avada' ),
				'id'          => 'sidebars_styling',
				'icon'        => true,
				'type'        => 'sub-section',
				'fields'      => array(
					'sidebar_padding' => array(
						'label'       => esc_html__( 'Sidebar Padding', 'Avada' ),
						'description' => esc_html__( 'Controls the sidebar padding.', 'Avada' ),
						'id'          => 'sidebar_padding',
						'default'     => '0px',
						'type'        => 'dimension',
						'choices'     => array( 'px', '%' ),
					),
					'sidebar_bg_color' => array(
						'label'       => esc_html__( 'Sidebar Background Color', 'Avada' ),
						'description' => esc_html__( 'Controls the background color of the sidebar.', 'Avada' ),
						'id'          => 'sidebar_bg_color',
						'default'     => 'rgba(255,255,255,0)',
						'type'        => 'color-alpha',
					),
					'sidebar_widget_bg_color' => array(
						'label'       => esc_html__( 'Sidebar Widget Title Background Color', 'Avada' ),
						'description' => esc_html__( 'Controls the background color of the widget title box. If left transparent the widget title will be unboxed.', 'Avada' ),
						'id'          => 'sidebar_widget_bg_color',
						'default'     => 'rgba(255,255,255,0)',
						'type'        => 'color-alpha',
					),
					'sidew_font_size' => array(
						'label'       => esc_html__( 'Sidebar Widget Heading Font Size', 'Avada' ),
						'description' => esc_html__( 'Controls the font size of the widget heading text.', 'Avada' ),
						'id'          => 'sidew_font_size',
						'default'     => '13px',
						'type'        => 'dimension',
					),
					'sidebar_heading_color' => array(
						'label'       => esc_html__( 'Sidebar Widget Headings Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the sidebar widget heading text.', 'Avada' ),
						'id'          => 'sidebar_heading_color',
						'default'     => '#333333',
						'type'        => 'color',
					),
				),
			),
			'pages_sidebars_section' => array(
				'label'       => esc_html__( 'Pages', 'Avada' ),
				'id'          => 'pages_sidebars_section',
				'icon'        => true,
				'type'        => 'sub-section',
				'fields'      => array(
					'pages_global_sidebar' => array(
						'label'       => esc_html__( 'Activate Global Sidebar For Pages', 'Avada' ),
						'description' => esc_html__( 'Turn on if you want to use the same sidebars on all pages. This option overrides the page options.', 'Avada' ),
						'id'          => 'pages_global_sidebar',
						'default'     => '0',
						'type'        => 'switch'
					),
					'pages_sidebar' => array(
						'label'       => esc_html__( 'Global Page Sidebar 1', 'Avada' ),
						'description' => esc_html__( 'Select sidebar 1 that will display on all pages.', 'Avada' ),
						'id'          => 'pages_sidebar',
						'default'     => 'None',
						'type'        => 'select',
						'choices'     => $sidebar_options,
					),
					'pages_sidebar_2' => array(
						'label'       => esc_html__( 'Global Page Sidebar 2', 'Avada' ),
						'description' => esc_html__( 'Select sidebar 2 that will display on all pages. Sidebar 2 can only be used if sidebar 1 is selected.', 'Avada' ),
						'id'          => 'pages_sidebar_2',
						'default'     => 'None',
						'type'        => 'select',
						'choices'     => $sidebar_options
					),
					'default_sidebar_pos' => array(
						'label'       => esc_html__( 'Global Page Sidebar Position', 'Avada' ),
						'description' => esc_html__( 'Controls the position of sidebar 1 for all pages. If sidebar 2 is selected, it will display on the opposite side.', 'Avada' ),
						'id'          => 'default_sidebar_pos',
						'default'     => 'Right',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'Right' => esc_html__( 'Right', 'Avada' ),
							'Left'  => esc_html__( 'Left', 'Avada' ),
						),
					),
				),
			),
			'portfolio_posts_sidebars_section' => array(
				'label'       => esc_html__( 'Portfolio Posts', 'Avada' ),
				'description' => '',
				'id'          => 'portfolio_posts_sidebars_section',
				'icon'        => true,
				'type'        => 'sub-section',
				'fields'      => array(
					'portfolio_global_sidebar' => array(
						'label'       => esc_html__( 'Activate Global Sidebar For Portfolio Posts', 'Avada' ),
						'description' => esc_html__( 'Turn on if you want to use the same sidebars on all portfolio posts. This option overrides the portfolio post options.', 'Avada' ),
						'id'          => 'portfolio_global_sidebar',
						'default'     => '0',
						'type'        => 'switch'
					),
					'portfolio_sidebar' => array(
						'label'       => esc_html__( 'Global Portfolio Post Sidebar 1', 'Avada' ),
						'description' => esc_html__( 'Select sidebar 1 that will display on all portfolio posts.', 'Avada' ),
						'id'          => 'portfolio_sidebar',
						'default'     => 'None',
						'type'        => 'select',
						'choices'     => $sidebar_options
					),
					'portfolio_sidebar_2' => array(
						'label'       => esc_html__( 'Global Portfolio Post Sidebar 2', 'Avada' ),
						'description' => esc_html__( 'Select sidebar 2 that will display on all portfolio posts. Sidebar 2 can only be used if sidebar 1 is selected.', 'Avada' ),
						'id'          => 'portfolio_sidebar_2',
						'default'     => 'None',
						'type'        => 'select',
						'choices'     => $sidebar_options
					),
					'portfolio_sidebar_position' => array(
						'label'       => esc_html__( 'Global Portfolio Sidebar Position', 'Avada' ),
						'description' => esc_html__( 'Controls the position of sidebar 1 for all portfolio posts and archive pages. If sidebar 2 is selected, it will display on the opposite side.', 'Avada' ),
						'id'          => 'portfolio_sidebar_position',
						'default'     => 'Right',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'Right' => esc_html__( 'Right', 'Avada' ),
							'Left'  => esc_html__( 'Left', 'Avada' ),
						)
					),
				),
			),
			'portfolio_archive_category_pages_sidebars_section' => array(
				'label'       => esc_html__( 'Portfolio Archive', 'Avada' ),
				'description' => '',
				'id'          => 'portfolio_archive_category_pages_sidebars_section',
				'icon'        => true,
				'type'        => 'sub-section',
				'fields'      => array(
					'portfolio_archive_sidebar' => array(
						'label'       => esc_html__( 'Portfolio Archive Sidebar 1', 'Avada' ),
						'description' => esc_html__( 'Select sidebar 1 that will display on the portfolio archive pages.', 'Avada' ),
						'id'          => 'portfolio_archive_sidebar',
						'default'     => 'None',
						'type'        => 'select',
						'choices'     => $sidebar_options
					),
					'portfolio_archive_sidebar_2' => array(
						'label'       => esc_html__( 'Portfolio Archive Sidebar 2', 'Avada' ),
						'description' => esc_html__( 'Select sidebar 2 that will display on the portfolio archive pages. Sidebar 2 can only be used if sidebar 1 is selected.', 'Avada' ),
						'id'          => 'portfolio_archive_sidebar_2',
						'default'     => 'None',
						'type'        => 'select',
						'choices'     => $sidebar_options
					),
				),
			),
			'blog_posts_sidebars_section' => array(
				'label'       => esc_html__( 'Blog Posts', 'Avada' ),
				'description' => '',
				'id'          => 'blog_posts_sidebars_section',
				'icon'        => true,
				'type'        => 'sub-section',
				'fields'      => array(
					'posts_global_sidebar' => array(
						'label'       => esc_html__( 'Activate Global Sidebar For Blog Posts', 'Avada' ),
						'description' => esc_html__( 'Turn on if you want to use the same sidebars on all blog posts. This option overrides the blog post options.', 'Avada' ),
						'id'          => 'posts_global_sidebar',
						'default'     => '0',
						'type'        => 'switch'
					),
					'posts_sidebar' => array(
						'label'       => esc_html__( 'Global Blog Post Sidebar 1', 'Avada' ),
						'description' => esc_html__( 'Select sidebar 1 that will display on all blog posts.', 'Avada' ),
						'id'          => 'posts_sidebar',
						'default'     => 'None',
						'type'        => 'select',
						'choices'     => $sidebar_options
					),
					'posts_sidebar_2' => array(
						'label'       => esc_html__( 'Global Blog Post Sidebar 2', 'Avada' ),
						'description' => esc_html__( 'Select sidebar 2 that will display on all blog posts. Sidebar 2 can only be used if sidebar 1 is selected.', 'Avada' ),
						'id'          => 'posts_sidebar_2',
						'default'     => 'None',
						'type'        => 'select',
						'choices'     => $sidebar_options
					),
					'blog_sidebar_position' => array(
						'label'       => esc_html__( 'Global Blog Sidebar Position', 'Avada' ),
						'description' => esc_html__( 'Controls the position of sidebar 1 for all blog posts and archive pages. If sidebar 2 is selected, it will display on the opposite side.', 'Avada' ),
						'id'          => 'blog_sidebar_position',
						'default'     => 'Right',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'Right' => esc_html__( 'Right', 'Avada' ),
							'Left'  => esc_html__( 'Left', 'Avada' ),
						)
					),
				),
			),
			'blog_archive_category_pages_sidebars_section' => array(
				'label'       => esc_html__( 'Blog Archive', 'Avada' ),
				'description' => '',
				'id'          => 'blog_archive_category_pages_sidebars_section',
				'icon'        => true,
				'type'        => 'sub-section',
				'fields'      => array(
					'blog_archive_sidebar' => array(
						'label'       => esc_html__( 'Blog Archive Sidebar 1', 'Avada' ),
						'description' => esc_html__( 'Select sidebar 1 that will display on the blog archive pages.', 'Avada' ),
						'id'          => 'blog_archive_sidebar',
						'default'     => 'None',
						'type'        => 'select',
						'choices'     => $sidebar_options
					),
					'blog_archive_sidebar_2' => array(
						'label'       => esc_html__( 'Blog Archive Sidebar 2', 'Avada' ),
						'description' => esc_html__( 'Select sidebar 2 that will display on the blog archive pages. Sidebar 2 can only be used if sidebar 1 is selected.', 'Avada' ),
						'id'          => 'blog_archive_sidebar_2',
						'default'     => 'None',
						'type'        => 'select',
						'choices'     => $sidebar_options
					),
				),
			),
			'search_sidebars_section' => array(
				'label'       => esc_html__( 'Search Page', 'Avada' ),
				'description' => '',
				'id'          => 'search_only',
				'icon'        => true,
				'type'        => 'sub-section',
				'fields'      => array(
					'search_sidebar' => array(
						'label'       => esc_html__( 'Search Page Sidebar 1', 'Avada' ),
						'description' => esc_html__( 'Select sidebar 1 that will display on the search results page.', 'Avada' ),
						'id'          => 'search_sidebar',
						'default'     => 'Blog Sidebar',
						'type'        => 'select',
						'choices'     => $sidebar_options
					),
					'search_sidebar_2' => array(
						'label'       => esc_html__( 'Search Page Sidebar 2', 'Avada' ),
						'description' => esc_html__( 'Select sidebar 2 that will display on the search results page. Sidebar 2 can only be used if sidebar 1 is selected.', 'Avada' ),
						'id'          => 'search_sidebar_2',
						'default'     => 'None',
						'type'        => 'select',
						'choices'     => $sidebar_options
					),
					'search_sidebar_position' => array(
						'label'       => esc_html__( 'Search Sidebar Position', 'Avada' ),
						'description' => esc_html__( 'Controls the position of sidebar 1 for the search results page. If sidebar 2 is selected, it will display on the opposite side.', 'Avada' ),
						'id'          => 'search_sidebar_position',
						'default'     => 'Right',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'Right' => esc_html__( 'Right', 'Avada' ),
							'Left'  => esc_html__( 'Left', 'Avada' ),
						)
					),
				),
			),			
			'woocommerce_products_sidebars_section' => ( Avada::$is_updating || class_exists( 'WooCommerce' ) ) ? array(
				'label'           => esc_html__( 'Woocommerce Products', 'Avada' ),
				'id'              => 'woocommerce_products_sidebars_section',
				'icon'            => true,
				'type'            => 'sub-section',
				'active_callback' => array( 'Avada_Options_Conditionals', 'is_woo' ),
				'fields'          => array(
					'woo_global_sidebar' => array(
						'label'           => esc_html__( 'Activate Global Sidebar For WooCommerce Products', 'Avada' ),
						'description'     => esc_html__( 'Turn on if you want to use the same sidebars on all WooCommerce products. This option overrides the WooCommerce post options.', 'Avada' ),
						'id'              => 'woo_global_sidebar',
						'default'         => '0',
						'type'            => 'switch',
						'active_callback' => array( 'Avada_Options_Conditionals', 'is_woo' ),
					),
					'woo_sidebar' => array(
						'label'           => esc_html__( 'Global WooCommerce Product Sidebar 1', 'Avada' ),
						'description'     => esc_html__( 'Select sidebar 1 that will display on all WooCommerce products.', 'Avada' ),
						'id'              => 'woo_sidebar',
						'default'         => 'None',
						'type'            => 'select',
						'choices'         => $sidebar_options,
						'active_callback' => array( 'Avada_Options_Conditionals', 'is_woo' ),
					),
					'woo_sidebar_2' => array(
						'label'           => esc_html__( 'Global WooCommerce Product Sidebar 2', 'Avada' ),
						'description'     => esc_html__( 'Select sidebar 2 that will display on all WooCommerce products. Sidebar 2 can only be used if sidebar 1 is selected.', 'Avada' ),
						'id'              => 'woo_sidebar_2',
						'default'         => 'None',
						'type'            => 'select',
						'choices'         => $sidebar_options,
						'active_callback' => array( 'Avada_Options_Conditionals', 'is_woo' ),
					),
					'woo_sidebar_position' => array(
						'label'           => esc_html__( 'Global Woocommerce Sidebar Position', 'Avada' ),
						'description'     => esc_html__( 'Controls the position of sidebar 1 for all WooCommerce products and archive pages. If sidebar 2 is selected, it will display on the opposite side.', 'Avada' ),
						'id'              => 'woo_sidebar_position',
						'default'         => 'Right',
						'type'            => 'radio-buttonset',
						'active_callback' => array( 'Avada_Options_Conditionals', 'is_woo' ),
						'choices'     => array(
							'Right' => esc_html__( 'Right', 'Avada' ),
							'Left'  => esc_html__( 'Left', 'Avada' ),
						)
					),
				),
			) : array(),
			'woocommerce_archive_category_pages_sidebars_section' => ( Avada::$is_updating || class_exists( 'WooCommerce' ) ) ? array(
				'label'           => esc_html__( 'WooCommerce Archive', 'Avada' ),
				'description'     => '',
				'id'              => 'woocommerce_archive_category_pages_sidebars_section',
				'icon'            => true,
				'type'            => 'sub-section',
				'active_callback' => array( 'Avada_Options_Conditionals', 'is_woo' ),
				'fields'          => array(
					'woocommerce_archive_sidebar' => array(
						'label'           => esc_html__( 'Woocommerce Archive Sidebar 1', 'Avada' ),
						'description'     => esc_html__( 'Select sidebar 1 that will display on the WooCommerce archive pages.', 'Avada' ),
						'id'              => 'woocommerce_archive_sidebar',
						'default'         => 'None',
						'type'            => 'select',
						'choices'         => $sidebar_options,
						'active_callback' => array( 'Avada_Options_Conditionals', 'is_woo' ),
					),
					'woocommerce_archive_sidebar_2' => array(
						'label'           => esc_html__( 'Woocommerce Archive Sidebar 2', 'Avada' ),
						'description'     => esc_html__( 'Select sidebar 2 that will display on the WooCommerce archive pages. Sidebar 2 can only be used if sidebar 1 is selected.', 'Avada' ),
						'id'              => 'woocommerce_archive_sidebar_2',
						'default'         => 'None',
						'type'            => 'select',
						'choices'         => $sidebar_options,
						'active_callback' => array( 'Avada_Options_Conditionals', 'is_woo' ),
					),
				),
			) : array(),
			'ec_global_sidebar_heading' => ( Avada::$is_updating || class_exists( 'Tribe__Events__Main' ) ) ? array(
				'label'  => esc_html__( 'Events Calendar Posts', 'Avada' ),
				'id'     => 'ec_global_sidebar_heading',
				'type'   => 'sub-section',
				'fields' => array(
					'ec_global_sidebar' => array(
						'label'       => esc_html__( 'Activate Global Sidebar For Events Calendar Posts', 'Avada' ),
						'description' => esc_html__( 'Turn on if you want to use the same sidebars on all Events Calendar posts. This option overrides the Events Calendar post options.', 'Avada' ),
						'id'          => 'ec_global_sidebar',
						'default'     => 0,
						'type'        => 'switch',
					),
					'ec_sidebar' => array(
						'label'       => esc_html__( 'Global Events Calendar Post Sidebar 1', 'Avada' ),
						'description' => esc_html__( 'Select sidebar 1 that will display on all Events Calendar posts.', 'Avada' ),
						'id'          => 'ec_sidebar',
						'default'     => 'None',
						'type'        => 'select',
						'choices'     => $sidebar_options,
					),
					'ec_sidebar_2' => array(
						'label'       => esc_html__( 'Global Events Calendar Post Sidebar 2', 'Avada' ),
						'description' => esc_html__( 'Select sidebar 2 that will display on all all Events Calendar posts. Sidebar 2 can only be used if sidebar 1 is selected.', 'Avada' ),
						'id'          => 'ec_sidebar_2',
						'default'     => 'None',
						'type'        => 'select',
						'choices'     => $sidebar_options,
					),
					'ec_sidebar_pos' => array(
						'label'       => esc_html__( 'Global Events Calendar Sidebar Position ', 'Avada' ),
						'description' => esc_html__( 'Controls the position of sidebar 1 for all Events Calendar posts and archive pages. If sidebar 2 is selected, it will display on the opposite side.', 'Avada' ),
						'id'          => 'ec_sidebar_pos',
						'default'     => 'Right',
						'type'        => 'select',
						'choices'     => array(
							'Right' => esc_html__( 'Right', 'Avada' ),
							'Left'  => esc_html__( 'Left', 'Avada' ),
						),
					),
				),
			) : array(),			
			'bbpress_sidebars_section' =>  ( Avada::$is_updating || class_exists( 'bbPress' ) || class_exists( 'BuddyPress' ) ) ? array(
				'label'           => esc_html__( 'bbPress/BuddyPress', 'Avada' ),
				'description'     => '',
				'id'              => 'bbpress_sidebars_section',
				'icon'            => true,
				'type'            => 'sub-section',
				'fields'          => array(
					'bbpress_global_sidebar' => array(
						'label'       => esc_html__( 'Activate Global Sidebar For bbpress/BuddyPress', 'Avada' ),
						'description' => esc_html__( 'Turn on if you want to use the same sidebars on all bbPress/BuddyPress pages. Forums index page, profile page and search page does not need this option checked to display the sidebars selected below.', 'Avada' ),
						'id'          => 'bbpress_global_sidebar',
						'default'     => '0',
						'type'        => 'switch'
					),
					'ppbress_sidebar' => array(
						'label'       => esc_html__( 'Global bbPress/BuddyPress Sidebar 1', 'Avada' ),
						'description' => esc_html__( 'Select sidebar 1 that will display on all bbPress/BuddyPress pages.', 'Avada' ),
						'id'          => 'ppbress_sidebar',
						'default'     => 'None',
						'type'        => 'select',
						'choices'     => $sidebar_options
					),
					'ppbress_sidebar_2' => array(
						'label'       => esc_html__( 'Global bbPress/BuddyPress Sidebar 2', 'Avada' ),
						'description' => esc_html__( 'Select sidebar 2 that will display on all bbPress/BuddyPress pages. Sidebar 2 can only be used if sidebar 1 is selected.', 'Avada' ),
						'id'          => 'ppbress_sidebar_2',
						'default'     => 'None',
						'type'        => 'select',
						'choices'     => $sidebar_options
					),
					'bbpress_sidebar_position' => array(
						'label'       => esc_html__( 'Global bbPress/BuddyPress Sidebar Position', 'Avada' ),
						'description' => esc_html__( 'Controls the position of sidebar 1 for all bbPress/BuddyPress pages. If sidebar 2 is selected, it will display on the opposite side.', 'Avada' ),
						'id'          => 'bbpress_sidebar_position',
						'default'     => 'Right',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'Right' => esc_html__( 'Right', 'Avada' ),
							'Left'  => esc_html__( 'Left', 'Avada' ),
						)
					),
				),
			) : array(),
		),
	);

	return $sections;

}
