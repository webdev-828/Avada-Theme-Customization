<?php

/**
 * Header
 *
 * @var  array  	any existing settings
 * @return array 	existing sections + header
 *
 */
function avada_options_section_header( $sections ) {

	$settings = get_option( Avada::get_option_name(), array() );

	if ( ! isset( $settings['side_header_break_point'] ) ) {
		$settings['side_header_break_point'] = 800;
	}

	$sections['header'] = array(
		'label'    => esc_html__( 'Header', 'Avada' ),
		'id'       => 'heading_header',
		'is_panel' => true,
		'priority' => 3,
		'icon'     => 'el-icon-arrow-up',
		'fields'   => array(
			'header_info_1' => array(
				'label'       => esc_html__( 'Header Content', 'Avada' ),
				'description' => '',
				'id'          => 'header_info_1',
				'default'     => '',
				'icon'        => true,
				'type'        => 'sub-section',
				'fields'      => array(
					'header_position' => array(
						'label'       => esc_html__( 'Header Position', 'Avada' ),
						'description' => esc_html__( 'Controls the position of the header to be in the top, left or right of the site. The main menu height, header padding and logo margin options will auto adjust based off your selection for ideal aesthetics.', 'Avada' ),
						'id'          => 'header_position',
						'default'     => 'Top',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'Top'   => esc_html__( 'Top', 'Avada' ),
							'Left'  => esc_html__( 'Left', 'Avada' ),
							'Right' => esc_html__( 'Right', 'Avada' )
						)
					),
					'header_layout' => array(
						'label'       => esc_html__( 'Select a Header Layout', 'Avada' ),
						'description' => esc_html__( 'Controls the general layout of the header. Headers 2-5 allow additional content areas via the header content options 1-3. Header 6 only allows parent level menu items, no child levels will display. The main menu height, header padding and logo margin options will auto adjust based off your selection for ideal aesthetics.', 'Avada' ),
						'id'          => 'header_layout',
						'default'     => 'v1',
						'type'        => 'radio-image',
						'choices' => array(
							'v1' => get_template_directory_uri().'/assets/images/patterns/header1.png',
							'v2' => get_template_directory_uri().'/assets/images/patterns/header2.png',
							'v3' => get_template_directory_uri().'/assets/images/patterns/header3.png',
							'v4' => get_template_directory_uri().'/assets/images/patterns/header4.png',
							'v5' => get_template_directory_uri().'/assets/images/patterns/header5.png',
							'v6' => get_template_directory_uri().'/assets/images/patterns/header6.png',
						),
						'required'    => array(
							array(
								'setting'  => 'header_position',
								'operator' => '==',
								'value'    => 'Top',
							),
						),
					),
					'slider_position' => array(
						'label'       => esc_html__( 'Slider Position', 'Avada' ),
						'description' => esc_html__( 'Controls if the slider displays below or above the header.', 'Avada' ),
						'id'          => 'slider_position',
						'default'     => 'Below',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'Below'   => esc_html__( 'Below', 'Avada' ),
							'Above'   => esc_html__( 'Above', 'Avada' ),
						),
						'required'    => array(
							array(
								'setting'  => 'header_position',
								'operator' => '==',
								'value'    => 'Top',
							),
						),
					),
					'header_left_content' => array(
						'label'       => esc_html__( 'Header Content 1', 'Avada' ),
						'description' => esc_html__( 'Controls the content that displays in the top left section.', 'Avada' ),
						'id'          => 'header_left_content',
						'default'     => 'Contact Info',
						'type'        => 'select',
						'choices'     => array(
							'Contact Info' => esc_html__( 'Contact Info', 'Avada' ),
							'Social Links' => esc_html__( 'Social Links', 'Avada' ),
							'Navigation'   => esc_html__( 'Navigation', 'Avada' ),
							'Leave Empty'  => esc_html__( 'Leave Empty', 'Avada' ),
						),
						'class'		  => 'avada-or-gutter',
						'required'    => array(
							array(
								'setting'  => 'header_position',
								'operator' => '!=',
								'value'    => 'Top',
							),
							array(
								'setting'  => 'header_layout',
								'operator' => '=',
								'value'    => 'v2',
							),
							array(
								'setting'  => 'header_layout',
								'operator' => '=',
								'value'    => 'v3',
							),
							array(
								'setting'  => 'header_layout',
								'operator' => '=',
								'value'    => 'v4',
							),
							array(
								'setting'  => 'header_layout',
								'operator' => '=',
								'value'    => 'v5',
							),
						),
					),
					'header_right_content' => array(
						'label'       => esc_html__( 'Header Content 2', 'Avada' ),
						'description' => esc_html__( 'Controls the content that displays in the top right section.', 'Avada' ),
						'id'          => 'header_right_content',
						'default'     => 'Navigation',
						'type'        => 'select',
						'choices'     => array(
							'Contact Info' => esc_html__( 'Contact Info', 'Avada' ),
							'Social Links' => esc_html__( 'Social Links', 'Avada' ),
							'Navigation'   => esc_html__( 'Navigation', 'Avada' ),
							'Leave Empty'  => esc_html__( 'Leave Empty', 'Avada' ),
						),
						'class'		  => 'avada-or-gutter',
						'required'    => array(
							array(
								'setting'  => 'header_position',
								'operator' => '!=',
								'value'    => 'Top',
							),
							array(
								'setting'  => 'header_layout',
								'operator' => '=',
								'value'    => 'v2',
							),
							array(
								'setting'  => 'header_layout',
								'operator' => '=',
								'value'    => 'v3',
							),
							array(
								'setting'  => 'header_layout',
								'operator' => '=',
								'value'    => 'v4',
							),
							array(
								'setting'  => 'header_layout',
								'operator' => '=',
								'value'    => 'v5',
							),
						),
					),
					'header_v4_content' => array(
						'label'       => esc_html__( 'Header Content 3', 'Avada' ),
						'description' => esc_html__( 'Controls the content that displays in the middle right section.', 'Avada' ),
						'id'          => 'header_v4_content',
						'default'     => 'Tagline And Search',
						'type'        => 'select',
						'choices'     => array(
							'Tagline'            => esc_html__( 'Tagline', 'Avada' ),
							'Search'             => esc_html__( 'Search', 'Avada' ),
							'Tagline And Search' => esc_html__( 'Tagline And Search', 'Avada' ),
							'Banner'             => esc_html__( 'Banner', 'Avada' ),
							'None'               => esc_html__( 'Leave Empty', 'Avada' ),
						),
						'class'		  => 'avada-or-gutter',
						'required'    => array(
							array(
								'setting'  => 'header_position',
								'operator' => '!=',
								'value'    => 'Top',
							),
							array(
								'setting'  => 'header_layout',
								'operator' => '==',
								'value'    => 'v4',
							),
						),
					),
					'header_number' => array(
						'label'       => esc_html__( 'Phone Number For Contact Info', 'Avada' ),
						'description' => esc_html__( 'This content will display if you have "Contact Info" selected for the Header Content 1 or 2 option above.', 'Avada' ),
						'id'          => 'header_number',
						'default'     => 'Call Us Today! 1.555.555.555',
						'type'        => 'text',
						'class'		  => 'avada-or-gutter',
						'required'    => array(
							array(
								'setting'  => 'header_position',
								'operator' => '!=',
								'value'    => 'Top',
							),
							array(
								'setting'  => 'header_layout',
								'operator' => '=',
								'value'    => 'v2',
							),
							array(
								'setting'  => 'header_layout',
								'operator' => '=',
								'value'    => 'v3',
							),
							array(
								'setting'  => 'header_layout',
								'operator' => '=',
								'value'    => 'v4',
							),
							array(
								'setting'  => 'header_layout',
								'operator' => '=',
								'value'    => 'v5',
							),
						),
					),
					'header_email' => array(
						'label'       => esc_html__( 'Email Address For Contact Info', 'Avada' ),
						'description' => esc_html__( 'This content will display if you have "Contact Info" selected for the Header Content 1 or 2 option above.', 'Avada' ),
						'id'          => 'header_email',
						'default'     => 'info@yourdomain.com',
						'type'        => 'text',
						'class'		  => 'avada-or-gutter',
						'required'    => array(
							array(
								'setting'  => 'header_position',
								'operator' => '!=',
								'value'    => 'Top',
							),
							array(
								'setting'  => 'header_layout',
								'operator' => '=',
								'value'    => 'v2',
							),
							array(
								'setting'  => 'header_layout',
								'operator' => '=',
								'value'    => 'v3',
							),
							array(
								'setting'  => 'header_layout',
								'operator' => '=',
								'value'    => 'v4',
							),
							array(
								'setting'  => 'header_layout',
								'operator' => '=',
								'value'    => 'v5',
							),
						),
					),
					'header_tagline' => array(
						'label'       => esc_html__( 'Tagline For Content 3', 'Avada' ),
						'description' => esc_html__( 'This content will display if you have "Tagline" selected for the Header Content 3 option above.', 'Avada' ),
						'id'          => 'header_tagline',
						'default'     => 'Insert Tagline Here',
						'type'        => 'textarea',
						'required'    => array(
							array(
								'setting'  => 'header_layout',
								'operator' => '=',
								'value'    => 'v4',
							),
							array(
								'setting'  => 'header_v4_content',
								'operator' => 'contains',
								'value'    => 'Tagline',
							),
						),
					),
					'header_banner_code' => array(
						'label'       => esc_html__( 'Banner Code For Content 3', 'Avada' ),
						'description' => esc_html__( 'This content will display if you have "Banner" selected for the Header Content 3 option above. Add HTML banner code for Header Content 3. Shortcodes, like buttons, can be used here also.', 'Avada' ),
						'id'          => 'header_banner_code',
						'default'     => '',
						'type'        => 'code',
						'choices'     => array(
							'language' => 'html',
							'theme'    => 'chrome',
						),
						'required'    => array(
							array(
								'setting'  => 'header_v4_content',
								'operator' => '==',
								'value'    => 'Banner',
							),
						),
					),
				),
			),
			'header_info_2' => array(
				'label'       => esc_html__( 'Header Background Image', 'Avada' ),
				'description' => '',
				'id'          => 'header_info_2',
				'default'     => '',
				'icon'        => true,
				'type'        => 'sub-section',
				'fields'      => array(
					'header_bg_image' => array(
						'label'       => esc_html__( 'Background Image For Header Area', 'Avada' ),
						'description' => esc_html__( 'Select an image for the header background. If left empty, the header background color will be used. For top headers the image displays on top of the header background color and will only display if header opacity is set to 1. For side headers the image displays behind the header background color so the header opacity must be set below 1 to see the image.', 'Avada' ),
						'id'          => 'header_bg_image',
						'default'     => '',
						'mod'         => '',
						'type'        => 'media'
					),
					'header_bg_full' => array(
						'label'       => esc_html__( '100% Background Image', 'Avada' ),
						'description' => esc_html__( 'Turn on to have the header background image display at 100% in width and height according to the window size.', 'Avada' ),
						'id'          => 'header_bg_full',
						'default'     => '0',
						'type'        => 'switch',
						'required'    => array(
							array(
								'setting'  => 'header_bg_image',
								'operator' => '!=',
								'value'    => '',
							),
							array(
								'setting'  => 'header_bg_image',
								'operator' => '!=',
								'value'    => array(
									'url'  => '',
								),
							),
							array(
								'setting'  => 'header_bg_image',
								'operator' => '!=',
								'value'    => array(
									'url'       => '',
									'id'        => '',
									'height'    => '',
									'width'     => '',
									'thumbnail' => '',
								),
							),
						),
					),
					'header_bg_parallax' => array(
						'label'       => esc_html__( 'Parallax Background Image', 'Avada' ),
						'description' => esc_html__( 'Turn on to use a parallax scrolling effect on the background image. Only works for top header position.', 'Avada' ),
						'id'          => 'header_bg_parallax',
						'default'     => '1',
						'type'        => 'switch',
						'required'    => array(
							array(
								'setting'  => 'header_position',
								'operator' => '==',
								'value'    => 'Top',
							),
							array(
								'setting'  => 'header_bg_image',
								'operator' => '!=',
								'value'    => '',
							),
							array(
								'setting'  => 'header_bg_image',
								'operator' => '!=',
								'value'    => array(
									'url'  => '',
								),
							),
							array(
								'setting'  => 'header_bg_image',
								'operator' => '!=',
								'value'    => array(
									'url'       => '',
									'id'        => '',
									'height'    => '',
									'width'     => '',
									'thumbnail' => '',
								),
							),
						),
					),
					'header_bg_repeat' => array(
						'label'       => esc_html__( 'Background Repeat', 'Avada' ),
						'description' => esc_html__( 'Controls how the background image repeats.', 'Avada' ),
						'id'          => 'header_bg_repeat',
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
								'setting'  => 'header_bg_image',
								'operator' => '!=',
								'value'    => '',
							),
							array(
								'setting'  => 'header_bg_image',
								'operator' => '!=',
								'value'    => array(
									'url'  => '',
								),
							),
							array(
								'setting'  => 'header_bg_image',
								'operator' => '!=',
								'value'    => array(
									'url'       => '',
									'id'        => '',
									'height'    => '',
									'width'     => '',
									'thumbnail' => '',
								),
							),
						),
					),
				),
			),
			'header_styling' => array(
				'label'       => esc_html__( 'Header Styling', 'Avada' ),
				'description' => '',
				'id'          => 'header_styling',
				'default'     => '',
				'icon'        => true,
				'type'        => 'sub-section',
				'fields'      => array(
					'side_header_width' => array(
						'label'       => esc_html__( 'Header Width For Left/Right Position', 'Avada' ),
						'description' => esc_html__( 'Controls the width of the left or right side header. In pixels.', 'Avada' ),
						'id'          => 'side_header_width',
						'default'     => '280',
						'type'        => 'slider',
						'choices'     => array(
							'min'  => '0',
							'max'  => '800',
							'step' => '1',
						),
						'required'    => array(
							array(
								'setting'  => 'header_position',
								'operator' => '!=',
								'value'    => 'Top',
							),
						),
					),
					'header_padding' => array(
						'label'       => esc_html__( 'Header Padding', 'Avada' ),
						'description' => esc_html__( 'Controls the top/right/bottom/left padding for the header.', 'Avada' ),
						'id'          => 'header_padding',
						'choices'     => array(
							'top'     => true,
							'bottom'  => true,
							'left'    => true,
							'right'   => true,
						),
						'default'     => array(
							'top'     => '0px',
							'bottom'  => '0px',
							'left'    => '0px',
							'right'   => '0px',
						),
						'type'        => 'spacing',
					),
					'header_shadow' => array(
						'label'       => esc_html__( 'Header Shadow', 'Avada' ),
						'description' => esc_html__( 'Turn on to display a header drop shadow. This option is incompatible with Internet Explorer versions older than IE11.', 'Avada' ),
						'id'          => 'header_shadow',
						'default'     => '0',
						'type'        => 'switch',
					),
					'header_100_width' => array(
						'label'       => esc_html__( '100% Header Width', 'Avada' ),
						'description' => esc_html__( 'Turn on to have the header area display at 100% width according to the window size. Turn off to follow site width.', 'Avada' ),
						'id'          => 'header_100_width',
						'default'     => '0',
						'type'        => 'switch',
						'required'    => array(
							array(
								'setting'  => 'layout',
								'operator' => '==',
								'value'    => 'Wide',
							),
							array(
								'setting'  => 'header_position',
								'operator' => '==',
								'value'    => 'Top',
							),
						),
					),
					'header_bg_color' => array(
						'label'       => esc_html__( 'Header Background Color', 'Avada' ),
						'description' => esc_html__( 'Controls the background color and opacity for the header. For top headers, opacity set below 1 will remove the header height completely. For side headers, opacity set below 1 will display a color overlay.', 'Avada' ),
						'id'          => 'header_bg_color',
						'type'        => 'color-alpha',
						'default'     => '#ffffff',
					),
					'header_border_color' => array(
						'label'       => esc_html__( 'Header Border Color', 'Avada' ),
						'description' => esc_html__( 'Controls the border colors for the header. If using left or right header position it controls the menu divider lines.', 'Avada' ),
						'id'          => 'header_border_color',
						'default'     => '#e5e5e5',
						'type'        => 'color-alpha',
						'class'		  => 'avada-or-gutter',
						'required'    => array(
							array(
								'setting'  => 'header_position',
								'operator' => '!=',
								'value'    => 'Top',
							),
							array(
								'setting'  => 'header_layout',
								'operator' => '=',
								'value'    => 'v2',
							),
							array(
								'setting'  => 'header_layout',
								'operator' => '=',
								'value'    => 'v3',
							),
							array(
								'setting'  => 'header_layout',
								'operator' => '=',
								'value'    => 'v4',
							),
							array(
								'setting'  => 'header_layout',
								'operator' => '=',
								'value'    => 'v5',
							),
						),
					),
					'header_top_bg_color' => array(
						'label'       => esc_html__( 'Header Top Background Color', 'Avada' ),
						'description' => esc_html__( 'Controls the background color of the top header section used in Headers 2-5.', 'Avada' ),
						'id'          => 'header_top_bg_color',
						'default'     => '#a0ce4e',
						'type'        => 'color-alpha',
						'required'    => array(
							array(
								'setting'  => 'header_position',
								'operator' => '=',
								'value'    => 'Top',
							),
							array(
								'setting'  => 'header_layout',
								'operator' => '!=',
								'value'    => 'v1',
							),
							array(
								'setting'  => 'header_layout',
								'operator' => '!=',
								'value'    => 'v6',
							),
						),
					),
					'tagline_font_size' => array(
						'label'       => esc_html__( 'Header Tagline Font Size', 'Avada' ),
						'description' => esc_html__( 'Controls the font size for the tagline text when using header 4.', 'Avada' ),
						'id'          => 'tagline_font_size',
						'default'     => '16px',
						'type'        => 'dimension',
						'choices'     => array(
							'units' => array( 'px', 'em' ),
						),
						'class'		  => 'avada-or-gutter',
						'required'    => array(
							array(
								'setting'  => 'header_position',
								'operator' => '!=',
								'value'    => 'Top',
							),
							array(
								'setting'  => 'header_layout',
								'operator' => '==',
								'value'    => 'v4',
							),
						),
					),
					'tagline_font_color' => array(
						'label'       => esc_html__( 'Header Tagline Font Color', 'Avada' ),
						'description' => esc_html__( 'Controls the font color for the tagline text when using header 4.', 'Avada' ),
						'id'          => 'tagline_font_color',
						'default'     => '#747474',
						'type'        => 'color',
						'class'		  => 'avada-or-gutter',
						'required'    => array(
							array(
								'setting'  => 'header_position',
								'operator' => '!=',
								'value'    => 'Top',
							),
							array(
								'setting'  => 'header_layout',
								'operator' => '==',
								'value'    => 'v4',
							),
						),
					),
				),
			),
			'sticky_header' => array(
				'label'       => esc_html__( 'Sticky Header', 'Avada' ),
				'description' => '',
				'id'          => 'sticky_header',
				'default'     => '',
				'icon'        => true,
				'type'        => 'sub-section',
				'fields'   => array(
					'header_sticky' => array(
						'label'       => esc_html__( 'Sticky Header', 'Avada' ),
						'description' => esc_html__( 'Turn on to enable a sticky header.', 'Avada' ),
						'id'          => 'header_sticky',
						'default'     => 1,
						'type'        => 'switch',
					),
					'header_sticky_tablet' => array(
						'label'       => esc_html__( 'Sticky Header on Tablets', 'Avada' ),
						'description' => esc_html__( 'Turn on to enable a sticky header when scrolling on tablets.', 'Avada' ),
						'id'          => 'header_sticky_tablet',
						'default'     => '0',
						'type'        => 'switch',
						'required'    => array(
							array(
								'setting'  => 'header_sticky',
								'operator' => '!=',
								'value'    => 0,
							),
							array(
								'setting'  => 'responsive',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'header_sticky_mobile' => array(
						'label'       => esc_html__( 'Sticky Header on Mobiles', 'Avada' ),
						'description' => esc_html__( 'Turn on to enable a sticky header when scrolling on mobiles.', 'Avada' ),
						'id'          => 'header_sticky_mobile',
						'default'     => '0',
						'type'        => 'switch',
						'required'    => array(
							array(
								'setting'  => 'header_sticky',
								'operator' => '!=',
								'value'    => 0,
							),
							array(
								'setting'  => 'responsive',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'header_sticky_shrinkage' => array(
						'label'       => esc_html__( 'Sticky Header Animation', 'Avada' ),
						'description' => esc_html__( 'Turn on to allow the sticky header to animate to a smaller height when activated. Only works with header v1 - v3 and v6.', 'Avada' ),
						'id'          => 'header_sticky_shrinkage',
						'default'     => '1',
						'type'        => 'switch',
						'required'    => array(
							array(
								'setting'  => 'header_sticky',
								'operator' => '!=',
								'value'    => 0,
							),
							array(
								'setting'  => 'header_layout',
								'operator' => '!=',
								'value'    => 'v4',
							),
							array(
								'setting'  => 'header_layout',
								'operator' => '!=',
								'value'    => 'v5',
							),
						),
					),
					'header_sticky_type2_layout' => array(
						'label'       => esc_html__( 'Sticky Header Display For Headers 4 - 5 ', 'Avada' ),
						'description' => esc_html__( 'Controls what displays in the sticky header when using header v4 - v5.', 'Avada' ),
						'id'          => 'header_sticky_type2_layout',
						'default'     => 'menu_only',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'menu_only'     => esc_html__( 'Menu Only', 'Avada' ),
							'menu_and_logo' => esc_html__( 'Menu + Logo Area', 'Avada' ),
						),
						'required'    => array(
							array(
								'setting'  => 'header_sticky',
								'operator' => '!=',
								'value'    => 0,
							),
							array(
								'setting'  => 'header_position',
								'operator' => '=',
								'value'    => 'Top',
							),							
							array(
								'setting'  => 'header_layout',
								'operator' => '!=',
								'value'    => 'v1',
							),
							array(
								'setting'  => 'header_layout',
								'operator' => '!=',
								'value'    => 'v2',
							),
							array(
								'setting'  => 'header_layout',
								'operator' => '!=',
								'value'    => 'v3',
							),
							array(
								'setting'  => 'header_layout',
								'operator' => '!=',
								'value'    => 'v6',
							),
						),
					),
					'header_sticky_bg_color' => array(
						'label'       => esc_html__( 'Sticky Header Background Color', 'Avada' ),
						'description' => esc_html__( 'Controls the background color for the sticky header.', 'Avada' ),
						'id'          => 'header_sticky_bg_color',
						'type'        => 'color-alpha',
						'default'     => '#ffffff',
						'required'    => array(
							array(
								'setting'  => 'header_sticky',
								'operator' => '!=',
								'value'    => 0,
							),
						),
					),
					'header_sticky_nav_padding' => array(
						'label'       => esc_html__( 'Sticky Header Menu Item Padding', 'Avada' ),
						'description' => esc_html__( 'Controls the space between each menu item in the sticky header.', 'Avada' ),
						'id'          => 'header_sticky_nav_padding',
						'default'     => ( isset( $settings['nav_padding'] ) && ! empty( $settings['nav_padding'] ) ) ? $settings['nav_padding'] : '35',
						'type'        => 'slider',
						'choices'     => array(
							'min'  => '0',
							'max'  => '200',
							'step' => '1',
						),
						'required'    => array(
							array(
								'setting'  => 'header_sticky',
								'operator' => '!=',
								'value'    => '0',
							),
							array(
								'setting'  => 'header_layout',
								'operator' => '!=',
								'value'    => 'v6',
							),
						),
					),
					'header_sticky_nav_font_size' => array(
						'label'       => esc_html__( 'Sticky Header Navigation Font Size', 'Avada' ),
						'description' => esc_html__( 'Controls the font size of the menu items in the sticky header.', 'Avada' ),
						'id'          => 'header_sticky_nav_font_size',
						'default'     => ( isset( $settings['nav_font_size'] ) && ! empty( $settings['nav_font_size'] ) ) ? $settings['nav_font_size'] : '14px',
						'type'        => 'dimension',
						'choices'     => array(
							'units' => array( 'px', 'em' ),
						),
						'required'    => array(
							array(
								'setting'  => 'header_sticky',
								'operator' => '!=',
								'value'    => 0,
							),
							array(
								'setting'  => 'header_layout',
								'operator' => '!=',
								'value'    => 'v6',
							),
						),
					),
				),
			),
		),
	);

	return $sections;

}
