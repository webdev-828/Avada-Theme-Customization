<?php

/**
 * Logo
 *
 * @var  array  	any existing settings
 * @return array 	existing sections + logo
 *
 */
function avada_options_section_sliding_bar( $sections ) {

	$sections['sliding_bar'] = array(
		'label'    => esc_html__( 'Sliding Bar', 'Avada' ),
		'id'       => 'heading_sliding_bar',
		'priority' => 8,
		'icon'     => 'el-icon-chevron-down',
		'fields'   => array(
			'slidingbar_widgets' => array(
				'label'       => esc_html__( 'Sliding Bar on Desktops', 'Avada' ),
				'description' => esc_html__( 'Turn on to display the sliding bar on desktops.', 'Avada' ),
				'id'          => 'slidingbar_widgets',
				'default'     => '1',
				'type'        => 'switch',
			),
			'mobile_slidingbar_widgets' => array(
				'label'       => esc_html__( 'Sliding Bar On Mobile', 'Avada' ),
				'description' => esc_html__( 'Turn on to display the sliding bar on mobiles.', 'Avada' ),
				'id'          => 'mobile_slidingbar_widgets',
				'default'     => '0',
				'type'        => 'switch',
				'required'    => array(
					array(
						'setting'  => 'slidingbar_widgets',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'slidingbar_open_on_load' => array(
				'label'       => esc_html__( 'Sliding Bar Open On Page Load', 'Avada' ),
				'description' => esc_html__( 'Turn on to have the sliding bar open when the page loads.', 'Avada' ),
				'id'          => 'slidingbar_open_on_load',
				'default'     => '0',
				'type'        => 'switch',
				'required'    => array(
					array(
						'setting'  => 'slidingbar_widgets',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'slidingbar_widgets_columns' => array(
				'label'       => esc_html__( 'Number of Sliding Bar Columns', 'Avada' ),
				'description' => esc_html__( 'Controls the number of columns in the sliding bar.', 'Avada' ),
				'id'          => 'slidingbar_widgets_columns',
				'default'     => '2',
				'type'        => 'slider',
				'choices'     => array(
					'min'  => '1',
					'max'  => '6',
					'step' => '1',
				),
				'required'    => array(
					array(
						'setting'  => 'slidingbar_widgets',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'sliding_bar_styling_title' => array(
				'label'       => '',
				'description' => esc_html__( 'Sliding Bar Styling', 'Avada' ),
				'id'          => 'sliding_bar_styling_title',
				'type'        => 'custom',
				'style'       => 'heading',
				'required'    => array(
					array(
						'setting'  => 'slidingbar_widgets',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),			
			
			'slidingbar_bg_color' => array(
				'label'       => esc_html__( 'Sliding Bar Background Color', 'Avada' ),
				'description' => esc_html__( 'Controls the background color of the sliding bar.', 'Avada' ),
				'id'          => 'slidingbar_bg_color',
				'type'        => 'color-alpha',
				'default'     => '#363839',
				'required'    => array(
					array(
						'setting'  => 'slidingbar_widgets',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'slidingbar_divider_color' => array(
				'label'       => esc_html__( 'Sliding Bar Item Divider Color', 'Avada' ),
				'description' => esc_html__( 'Controls the divider color in the sliding bar.', 'Avada' ),
				'id'          => 'slidingbar_divider_color',
				'default'     => '#282A2B',
				'type'        => 'color-alpha',
				'required'    => array(
					array(
						'setting'  => 'slidingbar_widgets',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'slidingbar_toggle_icon_color' => array(
				'label'       => esc_html__( 'Sliding Bar Toggle Icon Color', 'Avada' ),
				'description' => esc_html__( 'Controls the color of the sliding bar toggle icon.', 'Avada' ),
				'id'          => 'slidingbar_toggle_icon_color',
				'default'     => '#ffffff',
				'type'        => 'color',
				'required'    => array(
					array(
						'setting'  => 'slidingbar_widgets',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'slidingbar_font_size' => array(
				'label'       => esc_html__( 'Sliding Bar Heading Font Size', 'Avada' ),
				'description' => esc_html__( 'Controls the font size for the sliding bar heading text.', 'Avada' ),
				'id'          => 'slidingbar_font_size',
				'default'     => '13px',
				'type'        => 'dimension',
				'choices'     => array(
					'units' => array( 'px', 'em' ),
				),
				'required'    => array(
					array(
						'setting'  => 'slidingbar_widgets',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),


			'slidingbar_headings_color' => array(
				'label'       => esc_html__( 'Sliding Bar Headings Color', 'Avada' ),
				'description' => esc_html__( 'Controls the text color of the sliding bar heading font.', 'Avada' ),
				'id'          => 'slidingbar_headings_color',
				'default'     => '#dddddd',
				'type'        => 'color',
				'required'    => array(
					array(
						'setting'  => 'slidingbar_widgets',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'slidingbar_text_color' => array(
				'label'       => esc_html__( 'Sliding Bar Font Color', 'Avada' ),
				'description' => esc_html__( 'Controls the text color of the sliding bar font.', 'Avada' ),
				'id'          => 'slidingbar_text_color',
				'default'     => '#8C8989',
				'type'        => 'color',
				'required'    => array(
					array(
						'setting'  => 'slidingbar_widgets',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'slidingbar_link_color' => array(
				'label'       => esc_html__( 'Sliding Bar Link Color', 'Avada' ),
				'description' => esc_html__( 'Controls the text color of the sliding bar link font.', 'Avada' ),
				'id'          => 'slidingbar_link_color',
				'default'     => '#bfbfbf',
				'type'        => 'color',
				'required'    => array(
					array(
						'setting'  => 'slidingbar_widgets',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'slidingbar_top_border' => array(
				'label'       => esc_html__( 'Top Border on Sliding Bar', 'Avada' ),
				'description' => esc_html__( 'Turn on to display a top border line on the sliding bar.', 'Avada' ),
				'id'          => 'slidingbar_top_border',
				'default'     => '0',
				'type'        => 'switch',
				'required'    => array(
					array(
						'setting'  => 'slidingbar_widgets',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
		),
	);

	return $sections;
}
