<?php

/**
 * Mobile settings
 *
 * @var  array  	any existing settings
 * @return array 	existing sections + mobile
 *
 */
function avada_options_section_responsive( $sections ) {

	$settings = get_option( Avada::get_option_name(), array() );

	$sections['mobile'] = array(
		'label'    => esc_html__( 'Responsive', 'Avada' ),
		'id'       => 'responsive',
		'priority' => 2,
		'icon'     => 'el-icon-resize-horizontal',
		'fields'   => array(
			'responsive' => array(
				'label'       => esc_html__( 'Responsive Design', 'Avada' ),
				'description' => esc_html__( 'Turn on to use the responsive design features. If set to off, the fixed layout is used.', 'Avada' ),
				'id'          => 'responsive',
				'default'     => '1',
				'type'        => 'switch',
				'choices'     => array(
					'on'  => esc_html__( 'On', 'Avada' ),
					'off' => esc_html__( 'Off', 'Avada' ),
				),
			),
			'grid_main_break_point' => array(
				'label'       => esc_html__( 'Grid Responsive Breakpoint', 'Avada' ),
				'description' => esc_html__( 'Controls when grid layouts (blog/portfolio) start to break into smaller columns. Further breakpoints are auto calculated.', 'Avada' ),
				'id'          => 'grid_main_break_point',
				'default'     => '1000',
				'type'        => 'slider',
				'choices'     => array(
					'min'  => '360',
					'max'  => '2000',
					'step' => '1',
				),
				'required'    => array(
					array(
						'setting'  => 'responsive',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'side_header_break_point' => array(
				'label'       => esc_html__( 'Header Responsive Breakpoint', 'Avada' ),
				'description' => esc_html__( 'Controls when the desktop header changes to the mobile header.', 'Avada' ),
				'id'          => 'side_header_break_point',
				'default'     => '800',
				'type'        => 'slider',
				'choices'     => array(
					'min'  => '0',
					'max'  => '2000',
					'step' => '1',
					'edit' => 'yes',
				),
				'required'    => array(
					array(
						'setting'  => 'responsive',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'content_break_point' => array(
				'label'       => esc_html__( 'Site Content Responsive Breakpoint', 'Avada' ),
				'description' => esc_html__( 'Controls when the site content area changes to the mobile layout. This includes all content below the header including the footer.', 'Avada' ),
				'id'          => 'content_break_point',
				'default'     => '800',
				'type'        => 'slider',
				'choices'     => array(
					'min'  => '0',
					'max'  => '2000',
					'step' => '1',
					'edit' => 'yes',
				),
				'required'    => array(
					array(
						'setting'  => 'responsive',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'mobile_zoom' => array(
				'label'       => esc_html__( 'Mobile Device Zoom', 'Avada' ),
				'description' => esc_html__( 'Turn on to enable pinch to zoom on mobile devices.', 'Avada' ),
				'id'          => 'mobile_zoom',
				'default'     => '1',
				'type'        => 'switch',
				'choices'     => array(
					'on'  => esc_html__( 'On', 'Avada' ),
					'off' => esc_html__( 'Off', 'Avada' ),
				),
				'required'    => array(
					array(
						'setting'  => 'responsive',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'typography_responsive' => array(
				'label'       => esc_html__( 'Responsive Heading Typography', 'Avada' ),
				'description' => esc_html__( 'Turn on for headings to change font size responsively.', 'Avada' ),
				'id'          => 'typography_responsive',
				'default'     => '0',
				'choices'     => array(
					'on'  => esc_html__( 'On', 'Avada' ),
					'off' => esc_html__( 'Off', 'Avada' ),
				),
				'type'        => 'switch',
				'required'    => array(
					array(
						'setting'  => 'responsive',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'typography_sensitivity' => array(
				'label'           => esc_html__( 'Responsive Typography Sensitivity', 'Avada' ),
				'description'     => esc_html__( 'Values below 1 decrease rate of resizing, values above 1 increase rate of resizing.', 'Avada' ),
				'id'              => 'typography_sensitivity',
				'default'         => '0.6',
				'type'            => 'slider',
				'required'        => array(
					array(
						'setting'  => 'responsive',
						'operator' => '==',
						'value'    => '1',
					),
					array(
						'setting'  => 'typography_responsive',
						'operator' => '!=',
						'value'    => 0,
					),
				),
				'choices'         => array(
					'min'  => '0',
					'max'  => '2',
					'step' => '.01'
				),
			),
			'typography_factor' => array(
				'label'       => esc_html__( 'Mininum Font Size Factor', 'Avada' ),
				'description' => esc_html__( 'Minimum font factor is used to determine the minimum distance between headings and body font by a multiplying value.', 'Avada' ),
				'id'          => 'typography_factor',
				'default'     => '1.5',
				'type'        => 'slider',
				'required'        => array(
					array(
						'setting'  => 'responsive',
						'operator' => '==',
						'value'    => '1',
					),
					array(
						'setting'  => 'typography_responsive',
						'operator' => '!=',
						'value'    => 0,
					),
				),
				'choices'     => array(
					'min'  => '0',
					'max'  => '4',
					'step' => '.01'
				),
			),
		),
	);

	return $sections;

}
