<?php

/**
 * Layout
 *
 * @var  array  	any existing settings
 * @return array 	existing sections + layout
 *
 */
function avada_options_section_layout( $sections ) {

	$settings = get_option( Avada::get_option_name(), array() );

	$sections['layout'] = array(
		'label'    => esc_html__( 'Layout', 'Avada' ),
		'id'       => 'heading_layout',
		'priority' => 1,
		'icon'     => 'el-icon-website',
		'fields'   => array(
			'layout' => array(
				'label'       => esc_html__( 'Layout', 'Avada' ),
				'description' => esc_html__( 'Controls the site layout.', 'Avada' ),
				'id'          => 'layout',
				'default'     => 'Wide',
				'type'        => 'radio-buttonset',
				'choices'     => array(
					'Boxed'   => esc_html__( 'Boxed', 'Avada' ),
					'Wide'    => esc_html__( 'Wide', 'Avada' ),
				),
			),
			'site_width' => array(
				'label'       => esc_html__( 'Site Width', 'Avada' ),
				'description' => esc_html__( 'Controls the overall site width.', 'Avada' ),
				'id'          => 'site_width',
				'default'     => '1100px',
				'type'        => 'dimension',
				'choices'     => array( 'px', '%' ),
				'transport'   => 'postMessage',
			),
			'main_padding' => array(
				'label'       => esc_html__( 'Page Content Padding', 'Avada' ),
				'description' => esc_html__( 'Controls the top/bottom padding for page content.', 'Avada' ),
				'id'          => 'main_padding',
				'choices'     => array(
					'top'     => true,
					'bottom'  => true,
					'units'   => array( 'px', '%' ),
				),
				'default'     => array(
					'top'     => '55px',
					'bottom'  => '40px',
				),
				'type'        => 'spacing',
			),
			'hundredp_padding' => array(
				'label'       => esc_html__( '100% Width Left/Right Padding', 'Avada' ),
				'description' => esc_html__( 'Controls the left/right padding for page content when using 100% site width or 100% width page template.', 'Avada' ),
				'id'          => 'hundredp_padding',
				'default'     => '30px',
				'type'        => 'dimension',
				'choices'     => array( 'px', '%' ),
			),
			'col_margin' => array(
				'label'       => esc_html__( 'Column Margins', 'Avada' ),
				'description' => esc_html__( 'Controls the top/bottom margins for all column sizes.', 'Avada' ),
				'id'          => 'col_margin',
				'type'        => 'spacing',
				'choices'     => array(
					'top'     => true,
					'bottom'  => true,
					'units'   => array( 'px', '%' ),
				),
				'default'     => array(
					'top'     => '0px',
					'bottom'  => '20px',
				),
			),
			'single_sidebar_layouts_info' => array(
				'label'           => esc_html__( 'Single Sidebar Layouts', 'Avada' ),
				'description'     => '',
				'id'              => 'single_sidebar_layouts_info',
				'type'            => 'info',
			),
			'sidebar_width' => array(
				'label'       => esc_html__( 'Single Sidebar Width', 'Avada' ),
				'description' => esc_html__( 'Controls the width of the sidebar when only one sidebar is present.', 'Avada' ),
				'id'          => 'sidebar_width',
				'default'     => '23%',
				'type'        => 'dimension',
				'choices'     => array( 'px', '%' ),
			),
			'dual_sidebar_layouts_info' => array(
				'label'           => esc_html__( 'Dual Sidebar Layouts', 'Avada' ),
				'description'     => '',
				'id'              => 'dual_sidebar_layouts_info',
				'type'            => 'info',
			),
			'sidebar_2_1_width' => array(
				'label'       => esc_html__( 'Dual Sidebar Width 1', 'Avada' ),
				'description' => esc_html__( 'Controls the width of sidebar 1 when dual sidebars are present.', 'Avada' ),
				'id'          => 'sidebar_2_1_width',
				'default'     => '21%',
				'type'        => 'dimension',
				'choices'     => array( 'px', '%' ),
			),
			'sidebar_2_2_width' => array(
				'label'       => esc_html__( 'Dual Sidebar Width 2', 'Avada' ),
				'description' => esc_html__( 'Controls the width of sidebar 2 when dual sidebars are present.', 'Avada' ),
				'id'          => 'sidebar_2_2_width',
				'default'     => '21%',
				'type'        => 'dimension',
				'choices'     => array( 'px', '%' ),
			),
			'ec_sidebar_layouts_info' => ( Avada::$is_updating || class_exists( 'Tribe__Events__Main' ) ) ? array(
				'label'           => esc_html__( 'Events Calendar Single Sidebar Layout', 'Avada' ),
				'description'     => '',
				'id'              => 'ec_sidebar_layouts_info',
				'type'            => 'info',
			) : array(),
			'ec_sidebar_width' => ( Avada::$is_updating || class_exists( 'Tribe__Events__Main' ) ) ? array(
				'label'       => esc_html__( 'Events Calendar Single Sidebar Width', 'Avada' ),
				'description' => esc_html__( 'Controls the width of the sidebar when only one sidebar is present.', 'Avada' ),
				'id'          => 'ec_sidebar_width',
				'default'     => '32%',
				'type'        => 'dimension',
				'choices'     => array( 'px', '%' ),
			) : array(),
			'ec_dual_sidebar_layouts_info' => ( Avada::$is_updating || class_exists( 'Tribe__Events__Main' ) ) ? array(
				'label'           => esc_html__( 'Events Calendar Dual Sidebar Layout', 'Avada' ),
				'description'     => '',
				'id'              => 'ec_dual_sidebar_layouts_info',
				'type'            => 'info',
			) : array(),
			'ec_sidebar_2_1_width' => ( Avada::$is_updating || class_exists( 'Tribe__Events__Main' ) ) ? array(
				'label'       => esc_html__( 'Events Calendar Dual Sidebar Width 1', 'Avada' ),
				'description' => esc_html__( 'Controls the width of sidebar 1 when dual sidebars are present.', 'Avada' ),
				'id'          => 'ec_sidebar_2_1_width',
				'default'     => '21%',
				'type'        => 'dimension',
				'choices'     => array( 'px', '%' ),
			) : array(),
			'ec_sidebar_2_2_width' => ( Avada::$is_updating || class_exists( 'Tribe__Events__Main' ) ) ? array(
				'label'       => esc_html__( 'Events Calendar Dual Sidebar Width 2', 'Avada' ),
				'description' => esc_html__( 'Controls the width of sidebar 2 when dual sidebars are present.', 'Avada' ),
				'id'          => 'ec_sidebar_2_2_width',
				'default'     => '21%',
				'type'        => 'dimension',
				'choices'     => array( 'px', '%' ),
			) : array(),
		),
	);

	return $sections;

}
