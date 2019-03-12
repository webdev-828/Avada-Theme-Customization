<?php

/**
 * Footer settings
 *
 * @var  array  	any existing settings
 * @return array 	existing sections + footer
 *
 */
function avada_options_section_footer( $sections ) {

	$settings = get_option( Avada::get_option_name(), array() );

	$sections['footer'] = array(
		'label'    => esc_html__( 'Footer', 'Avada' ),
		'id'       => 'heading_footer',
		'priority' => 9,
		'icon'     => 'el-icon-arrow-down',
		'class'    => 'hidden-section-heading',
		'fields'   => array(
			'footer_content_options_subsection' => array(
				'label'       => esc_html__( 'Footer Content', 'Avada' ),
				'id'          => 'footer_content_options_subsection',
				'icon'        => true,
				'type'        => 'sub-section',
				'fields'      => array(
					'footer_widgets' => array(
						'label'       => esc_html__( 'Footer Widgets', 'Avada' ),
						'description' => esc_html__( 'Turn on to display footer widgets.', 'Avada' ),
						'id'          => 'footer_widgets',
						'default'     => '1',
						'type'        => 'switch'
					),
					'footer_widgets_columns' => array(
						'label'       => esc_html__( 'Number of Footer Columns', 'Avada' ),
						'description' => esc_html__( 'Controls the number of columns in the footer.', 'Avada' ),
						'id'          => 'footer_widgets_columns',
						'default'     => '4',
						'choices'     => array(
							'min'  => '1',
							'max'  => '6',
							'step' => '1',
						),
						'type' => 'slider',
						'required'    => array(
							array(
								'setting'  => 'footer_widgets',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'footer_widgets_center_content' => array(
						'label'       => esc_html__( 'Center Footer Widgets Content', 'Avada' ),
						'description' => esc_html__( 'Turn on to center the footer widget content.', 'Avada' ),
						'id'          => 'footer_widgets_center_content',
						'default'     => '0',
						'type'        => 'switch',
						'required'    => array(
							array(
								'setting'  => 'footer_widgets',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'footer_special_effects' => array(
						'label'       => 'Footer Special Effects',
						'description' => esc_html__( 'Select a special effect for the footer area.', 'Avada' ),
						'id'          => 'footer_special_effects',
						'default'     => 'none',
						'type'        => 'radio',
						'choices'     => array(
							'none'                                 => esc_html__( 'None', 'Avada' ),
							'footer_parallax_effect'               => array(
								esc_html__( 'Footer Parallax Effect', 'Avada' ),
								esc_html__( 'This enables a fixed footer with parallax scrolling effect.', 'Avada' ),
							),
							'footer_area_bg_parallax'              => array(
								esc_html__( 'Parallax Background Image', 'Avada' ),
								esc_html__( 'This enables a parallax effect on the background image selected in "Background Image For Footer Widget Area" field.', 'Avada' ),
							),
							'footer_sticky'                        => array(
								esc_html__( 'Sticky Footer', 'Avada' ),
								esc_html__( 'This enables a sticky footer. The entire footer area will always be "below the fold". On very short pages, it makes sure that the footer sticks at the bottom, just above the fold. IMPORTANT: "Sticky Footer Height" field must be filled in.', 'Avada' ),
							),
							'footer_sticky_with_parallax_bg_image' => array(
								esc_html__( 'Sticky Footer and Parallax Background Image', 'Avada' ),
								esc_html__( 'This enables a sticky footer together with a parallax effect on the background image. The entire footer area will always be "below the fold". IMPORTANT: "Sticky Footer Height" field must be filled in.', 'Avada' ),
							),
						),
					),
					'footer_sticky_height' => array(
						'label'       => esc_html__( 'Sticky Footer Height', 'Avada' ),
						'description' => sprintf( esc_html__( 'The entire height of the footer area (widgets + copyright) %1s View tutorial here %2s. Set a static height in px to enable sticky footer effect. Set to 0 to disable.', 'Avada' ), '<a href="https://theme-fusion.com/avada-doc/footer-special-effects/" target="_blank">', '</a>' ),
						'id'          => 'footer_sticky_height',
						'default'     => '0',
						'type'        => 'slider',
						'choices'     => array(
							'min'  => '0',
							'step' => '1',
							'max'  => '700',
							'edit' => 'yes',
						),
						'required'    => array(
							array(
								'setting'  => 'footer_special_effects',
								'operator' => '!=',
								'value'    => 'none',
							),
							array(
								'setting'  => 'footer_special_effects',
								'operator' => '!=',
								'value'    => 'footer_parallax_effect',
							),
							array(
								'setting'  => 'footer_special_effects',
								'operator' => '!=',
								'value'    => 'footer_area_bg_parallax',
							),
						),
					),
					'footer_copyright' => array(
						'label'       => esc_html__( 'Copyright Bar', 'Avada' ),
						'description' => esc_html__( 'Turn on to display the copyright bar.', 'Avada' ),
						'id'          => 'footer_copyright',
						'default'     => '1',
						'type'        => 'switch'
					),
					'footer_copyright_center_content' => array(
						'label'       => esc_html__( 'Center Copyright Content', 'Avada' ),
						'description' => esc_html__( 'Turn on to center the copyright bar content.', 'Avada' ),
						'id'          => 'footer_copyright_center_content',
						'default'     => '0',
						'type'        => 'switch',
						'required'    => array(
							array(
								'setting'  => 'footer_copyright',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'footer_text' => array(
						'label'             => esc_html__( 'Copyright Text', 'Avada' ),
						'description'       => esc_html__( 'Enter the text that displays in the copyright bar. HTML markup can be used.', 'Avada' ),
						'id'                => 'footer_text',
						'default'           => sprintf( esc_html__( 'Copyright %1s Avada | All Rights Reserved | Powered by %2s | %3s', 'Avada' ), '2012 - ' . date( 'Y' ), '<a href="http://wordpress.org">WordPress</a>', '<a href="http://theme-fusion.com">Theme Fusion</a>' ),
						'type'              => 'textarea',
						'sanitize_callback' => array( 'Kirki_Sanitize', 'unfiltered' ),
						'required'    => array(
							array(
								'setting'  => 'footer_copyright',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
				),
			),
			'footer_background_image_options_subsection' => array(
				'label'       => esc_html__( 'Footer Background Image', 'Avada' ),
				'id'          => 'footer_background_image_options_subsection',
				'icon'        => true,
				'type'        => 'sub-section',
				'fields'      => array(
					'footerw_bg_image' => array(
						'label'       => esc_html__( 'Background Image For Footer Widget Area', 'Avada' ),
						'description' => esc_html__( 'Select an image for the footer widget background. If left empty, the footer background color will be used.', 'Avada' ),
						'id'          => 'footerw_bg_image',
						'default'     => '',
						'mod'         => '',
						'type'        => 'media',
						'required'    => array(
							array(
								'setting'  => 'footer_widgets',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'footerw_bg_full' => array(
						'label'       => esc_html__( '100% Background Image', 'Avada' ),
						'description' => esc_html__( 'Turn on to have the footer background image display at 100% in width and height according to the window size.', 'Avada' ),
						'id'          => 'footerw_bg_full',
						'default'     => '0',
						'type'        => 'switch',
						'required'    => array(
							array(
								'setting'  => 'footerw_bg_image',
								'operator' => '!=',
								'value'    => '',
							),
							array(
								'setting'  => 'footerw_bg_image',
								'operator' => '!=',
								'value'    => array(
									'url'  => '',
								),
							),
							array(
								'setting'  => 'footerw_bg_image',
								'operator' => '!=',
								'value'    => array(
									'url'       => '',
									'id'        => '',
									'height'    => '',
									'width'     => '',
									'thumbnail' => '',
								),
							),
							array(
								'setting'  => 'footer_widgets',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'footerw_bg_repeat' => array(
						'label'       => esc_html__( 'Background Repeat', 'Avada' ),
						'description' => esc_html__( 'Controls how the background image repeats.', 'Avada' ),
						'id'          => 'footerw_bg_repeat',
						'default'     => 'no-repeat',
						'type'        => 'select',
						'choices'     => array(
							'repeat'    => esc_html__( 'Repeat All', 'Avada' ),
							'repeat-x'  => esc_html__( 'Repeat Horizontally', 'Avada' ),
							'repeat-y'  => esc_html__( 'Repeat Vertically', 'Avada' ),
							'no-repeat' => esc_html__( 'No Repeat', 'Avada' ),
						),
						'required'    => array(
							array(
								'setting'  => 'footerw_bg_image',
								'operator' => '!=',
								'value'    => '',
							),
							array(
								'setting'  => 'footerw_bg_image',
								'operator' => '!=',
								'value'    => array(
									'url'  => '',
								),
							),
							array(
								'setting'  => 'footerw_bg_image',
								'operator' => '!=',
								'value'    => array(
									'url'       => '',
									'id'        => '',
									'height'    => '',
									'width'     => '',
									'thumbnail' => '',
								),
							),
							array(
								'setting'  => 'footer_widgets',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'footerw_bg_pos' => array(
						'label'       => esc_html__( 'Background Position', 'Avada' ),
						'description' => esc_html__( 'Controls how the background image is positioned.', 'Avada' ),
						'id'          => 'footerw_bg_pos',
						'default'     => 'center center',
						'type'        => 'select',
						'choices'     => array(
							'top left'      => esc_html__( 'top left', 'Avada' ),
							'top center'    => esc_html__( 'top center', 'Avada' ),
							'top right'     => esc_html__( 'top right', 'Avada' ),
							'center left'   => esc_html__( 'center left', 'Avada' ),
							'center center' => esc_html__( 'center center', 'Avada' ),
							'center right'  => esc_html__( 'center right', 'Avada' ),
							'bottom left'   => esc_html__( 'bottom left', 'Avada' ),
							'bottom center' => esc_html__( 'bottom center', 'Avada' ),
							'bottom right'  => esc_html__( 'bottom right', 'Avada' )
						),
						'required'    => array(
							array(
								'setting'  => 'footerw_bg_image',
								'operator' => '!=',
								'value'    => '',
							),
							array(
								'setting'  => 'footerw_bg_image',
								'operator' => '!=',
								'value'    => array(
									'url'  => '',
								),
							),
							array(
								'setting'  => 'footerw_bg_image',
								'operator' => '!=',
								'value'    => array(
									'url'       => '',
									'id'        => '',
									'height'    => '',
									'width'     => '',
									'thumbnail' => '',
								),
							),
							array(
								'setting'  => 'footer_widgets',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
				),
			),
			'footer_styling_options_subsection' => array(
				'label'       => esc_html__( 'Footer Styling', 'Avada' ),
				'id'          => 'footer_styling_options_subsection',
				'icon'        => true,
				'type'        => 'sub-section',
				'fields'      => array(
					'footer_100_width' => array(
						'label'       => esc_html__( '100% Footer Width', 'Avada' ),
						'description' => esc_html__( 'Turn on to have the footer area display at 100% width according to the window size. Turn off to follow site width.', 'Avada' ),
						'id'          => 'footer_100_width',
						'default'     => '0',
						'type'        => 'switch'
					),
					'footer_area_padding' => array(
						'label'       => esc_html__( 'Footer Padding', 'Avada' ),
						'description' => esc_html__( 'Controls the top/right/bottom/left padding for the footer.', 'Avada' ),
						'id'          => 'footer_area_padding',
						'choices'     => array(
							'top'     => true,
							'bottom'  => true,
							'left'    => true,
							'right'   => true,
							'units'   => array( 'px', '%' ),
						),
						'default'     => array(
							'top'     => '43px',
							'bottom'  => '40px',
							'left'    => '0px',
							'right'   => '0px',
						),
						'type'        => 'spacing',
					),
					'footer_bg_color' => array(
						'label'       => esc_html__( 'Footer Background Color', 'Avada' ),
						'description' => esc_html__( 'Controls the background color of the footer.', 'Avada' ),
						'id'          => 'footer_bg_color',
						'default'     => '#363839',
						'type'        => 'color-alpha',
					),
					'footer_border_size' => array(
						'label'       => esc_html__( 'Footer Border Size', 'Avada' ),
						'description' => esc_html__( 'Controls the size of the top footer border.', 'Avada' ),
						'id'          => 'footer_border_size',
						'default'     => '12',
						'type'        => 'slider',
						'choices'     => array(
							'min'  => '0',
							'max'  => '50',
							'step' => '1',
						),
					),
					'footer_border_color' => array(
						'label'       => esc_html__( 'Footer Border Color', 'Avada' ),
						'description' => esc_html__( 'Controls the border colors of the footer.', 'Avada' ),
						'id'          => 'footer_border_color',
						'default'     => '#e9eaee',
						'type'        => 'color-alpha',
					),
					'footer_divider_color' => array(
						'label'       => esc_html__( 'Footer Widget Divider Color', 'Avada' ),
						'description' => esc_html__( 'Controls the divider color in the footer widgets.', 'Avada' ),
						'id'          => 'footer_divider_color',
						'default'     => '#505152',
						'type'        => 'color-alpha',
					),
					'copyright_padding' => array(
						'label'       => esc_html__( 'Copyright Padding', 'Avada' ),
						'description' => esc_html__( 'Controls the top/bottom padding for the copyright area.', 'Avada' ),
						'id'          => 'copyright_padding',
						'default'     => array(
							'top'     => '18px',
							'bottom'  => '16px',
						),
						'choices'     => array(
							'top'     => true,
							'bottom'  => true,
						),
						'type'        => 'spacing',
						'required'    => array(
							array(
								'setting'  => 'footer_copyright',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'copyright_bg_color' => array(
						'label'       => esc_html__( 'Copyright Background Color', 'Avada' ),
						'description' => esc_html__( 'Controls the background color of the footer copyright area.', 'Avada' ),
						'id'          => 'copyright_bg_color',
						'default'     => '#282a2b',
						'type'        => 'color-alpha',
						'required'    => array(
							array(
								'setting'  => 'footer_copyright',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'copyright_border_size' => array(
						'label'       => esc_html__( 'Copyright Border Size', 'Avada' ),
						'description' => esc_html__( 'Controls the size of the top copyright border.', 'Avada' ),
						'id'          => 'copyright_border_size',
						'default'     => '1',
						'type'        => 'slider',
						'choices'     => array(
							'min'  => '0',
							'max'  => '50',
							'step' => '1',
						),
					),
					'copyright_border_color' => array(
						'label'       => esc_html__( 'Copyright Border Color', 'Avada' ),
						'description' => esc_html__( 'Controls the border colors for the footer copyright area.', 'Avada' ),
						'id'          => 'copyright_border_color',
						'default'     => '#4b4c4d',
						'type'        => 'color-alpha',
						'required'    => array(
							array(
								'setting'  => 'footer_copyright',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'footer_typography_info' => array(
						'label'           => esc_html__( 'Footer Typography', 'Avada' ),
						'description'     => '',
						'id'              => 'footer_typography_info',
						'type'            => 'info',
					),
					'footer_headings_typography' => array(
						'id'          => 'footer_headings_typography',
						'label'       => esc_html__( 'Footer Headings Typography', 'Avada' ),
						'description' => esc_html__( 'These settings control the typography for the footer headings.', 'Avada' ),
						'type'        => 'typography',
						'choices'     => array(
							'font-family'    => true,
							'font-size'      => true,
							'font-weight'    => true,
							'line-height'    => true,
							'letter-spacing' => true,
							'color'          => true,
						),
						'default'     => array(
							'font-family'    => 'PT Sans',
							'font-size'      => '13px',
							'font-weight'    => '400',
							'line-height'    => '1.5',
							'letter-spacing' => '0',
							'color'          => '#dddddd',
						),
					),
					'footer_text_color' => array(
						'label'       => esc_html__( 'Footer Font Color', 'Avada' ),
						'description' => esc_html__( 'Controls the text color of the footer font.', 'Avada' ),
						'id'          => 'footer_text_color',
						'default'     => '#8C8989',
						'type'        => 'color',
					),
					'footer_link_color' => array(
						'label'       => esc_html__( 'Footer Link Color', 'Avada' ),
						'description' => esc_html__( 'Controls the text color of the footer link font.', 'Avada' ),
						'id'          => 'footer_link_color',
						'default'     => '#BFBFBF',
						'type'        => 'color',
					),
					'copyright_font_size' => array(
						'label'       => esc_html__( 'Copyright Font Size', 'Avada' ),
						'description' => esc_html__( 'Controls the font size for the copyright text.', 'Avada' ),
						'id'          => 'copyright_font_size',
						'default'     => '12px',
						'type'        => 'dimension',
						'choices'     => array(
							'units' => array( 'px', 'em' ),
						),
					),
				),
			),
		),
	);

	return $sections;

}
