<?php

/**
 * Page Title Bar
 *
 * @var  array  	any existing settings
 * @return array 	existing sections + page-title-bar
 *
 */
function avada_options_section_page_title_bar( $sections ) {

	$sections['page_title_bar'] = array(
		'label'    => esc_html__( 'Page Title Bar', 'Avada' ),
		'id'       => 'heading_page_title_bar',
		'priority' => 7,
		'icon'     => 'el-icon-adjust-alt',
		'class'    => 'hidden-section-heading',
		'fields'   => array(
			'heading_page_title_bar_info_1' => array(
				'label'       => esc_html__( 'Page Title Bar', 'Avada' ),
				'id'          => 'heading_page_title_bar_info_1',
				'icon'        => true,
				'type'        => 'sub-section',
				'fields'      => array(
					'page_title_bar' => array(
						'label'       => esc_html__( 'Page Title Bar', 'Avada' ),
						'description' => esc_html__( 'Controls how the page title bar displays. ', 'Avada' ),
						'id'          => 'page_title_bar',
						'default'     => 'bar_and_content',
						'choices'     => array(
							'bar_and_content' => esc_html__( 'Show Bar and Content', 'Avada' ),
							'content_only'    => esc_html__( 'Show Content Only', 'Avada' ),
							'hide'            => esc_html__( 'Hide', 'Avada' ),
						),
						'type'        => 'select'
					),
					'page_title_bar_text' => array(
						'label'       => esc_html__( 'Page Title Bar Text', 'Avada' ),
						'description' => esc_html__( 'Turn on to display the page title bar text.', 'Avada' ),
						'id'          => 'page_title_bar_text',
						'default'     => '1',
						'type'        => 'switch',
						'required'    => array(
							array(
								'setting'  => 'page_title_bar',
								'operator' => '!=',
								'value'    => 'hide',
							),
						),
					),
					'page_title_bar_styling_title' => array(
						'label'       => esc_html__( 'Page Title Bar Styling', 'Avada' ),
						'description' => '',
						'id'          => 'page_title_bar_styling_title',
						'icon'        => true,
						'type'        => 'info'
					),
					'page_title_100_width' => array(
						'label'       => esc_html__( '100% Page Title Width', 'Avada' ),
						'description' => esc_html__( 'Turn on to have the page title area display at 100% width according to the window size. Turn off to follow site width.', 'Avada' ),
						'id'          => 'page_title_100_width',
						'default'     => '0',
						'type'        => 'switch',
					),
					'page_title_height' => array(
						'label'       => esc_html__( 'Page Title Bar Height', 'Avada' ),
						'description' => esc_html__( 'Controls the height of the page title bar on desktop.', 'Avada' ),
						'id'          => 'page_title_height',
						'default'     => '87px',
						'type'        => 'dimension',
					),
					'page_title_mobile_height' => array(
						'label'       => esc_html__( 'Page Title Bar Mobile Height', 'Avada' ),
						'description' => esc_html__( 'Controls the height of the page title bar on mobile.', 'Avada' ),
						'id'          => 'page_title_mobile_height',
						'default'     => '70px',
						'type'        => 'dimension',
					),
					'page_title_bg_color' => array(
						'label'       => esc_html__( 'Page Title Bar Background Color', 'Avada' ),
						'description' => esc_html__( 'Controls the background color of the page title bar.', 'Avada' ),
						'id'          => 'page_title_bg_color',
						'default'     => '#F6F6F6',
						'type'        => 'color-alpha',
					),
					'page_title_border_color' => array(
						'label'       => esc_html__( 'Page Title Bar Borders Color', 'Avada' ),
						'description' => esc_html__( 'Controls the border colors of the page title bar.', 'Avada' ),
						'id'          => 'page_title_border_color',
						'default'     => '#d2d3d4',
						'type'        => 'color-alpha',
					),
					'page_title_font_size' => array(
						'label'       => esc_html__( 'Page Title Font Size', 'Avada' ),
						'description' => esc_html__( 'Controls the font size for the page title heading.', 'Avada' ),
						'id'          => 'page_title_font_size',
						'default'     => '18px',
						'type'        => 'dimension',
						'choices'     => array(
							'units' => array( 'px', 'em' ),
						),
					),
					'page_title_color' => array(
						'label'       => esc_html__( 'Page Title Font Color', 'Avada' ),
						'description' => esc_html__( 'Controls the text color of the page title fonts.', 'Avada' ),
						'id'          => 'page_title_color',
						'default'     => '#333333',
						'type'        => 'color',
					),
					'page_title_subheader_font_size' => array(
						'label'       => esc_html__( 'Page Title Subheader Font Size', 'Avada' ),
						'description' => esc_html__( 'Controls the font size for the page title subheading.', 'Avada' ),
						'id'          => 'page_title_subheader_font_size',
						'default'     => '14px',
						'type'        => 'dimension',
						'choices'     => array(
							'units' => array( 'px', 'em' ),
						),
					),
					'page_title_alignment' => array(
						'label'       => esc_html__( 'Page Title Bar Text Alignment', 'Avada' ),
						'description' => esc_html__( 'Controls the page title bar text alignment.', 'Avada' ),
						'id'          => 'page_title_alignment',
						'default'     => 'left',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'left'    => esc_html__( 'Left', 'Avada' ),
							'center'  => esc_html__( 'Center', 'Avada' ),
							'right'   => esc_html__( 'Right', 'Avada' ),
						),
					),
					'page_title_bar_bg_image_title' => array(
						'label'       => esc_html__( 'Page Title Bar Background Image', 'Avada' ),
						'description' => '',
						'id'          => 'page_title_bar_bg_image_title',
						'icon'        => true,
						'type'        => 'info'
					),
					'page_title_bg' => array(
						'label'       => esc_html__( 'Page Title Bar Background Image', 'Avada' ),
						'description' => esc_html__( 'Select an image for the page title bar background. If left empty, the page title bar background color will be used.', 'Avada' ),
						'id'          => 'page_title_bg',
						'default'     => get_template_directory_uri() . '/assets/images/page_title_bg.png',
						'mod'         => '',
						'type'        => 'media',
					),
					'page_title_bg_retina' => array(
						'label'       => esc_html__( 'Retina Page Title Bar Background Image', 'Avada' ),
						'description' => esc_html__( 'Select an image for the retina version of the page title bar background. It should be exactly 2x the size of the page title bar background.', 'Avada' ),
						'id'          => 'page_title_bg_retina',
						'default'     => '',
						'mod'         => '',
						'type'        => 'media',
						'required'    => array(
							array(
								'setting'  => 'page_title_bg',
								'operator' => '!=',
								'value'    => '',
							),
							array(
								'setting'  => 'page_title_bg',
								'operator' => '!=',
								'value'    => array(
									'url'  => '',
								),
							),
							array(
								'setting'  => 'page_title_bg',
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
					'page_title_bg_full' => array(
						'label'       => esc_html__( '100% Background Image', 'Avada' ),
						'description' => esc_html__( 'Turn on to have the page title bar background image display at 100% in width and height according to the window size.', 'Avada' ),
						'id'          => 'page_title_bg_full',
						'default'     => '0',
						'type'        => 'switch',
						'required'    => array(
							array(
								'setting'  => 'page_title_bg',
								'operator' => '!=',
								'value'    => '',
							),
							array(
								'setting'  => 'page_title_bg',
								'operator' => '!=',
								'value'    => array(
									'url'  => '',
								),
							),
							array(
								'setting'  => 'page_title_bg',
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
					'page_title_bg_parallax' => array(
						'label'       => esc_html__( 'Parallax Background Image', 'Avada' ),
						'description' => esc_html__( 'Turn on to use a parallax scrolling effect on the background image.', 'Avada' ),
						'id'          => 'page_title_bg_parallax',
						'default'     => '0',
						'type'        => 'switch',
						'required'    => array(
							array(
								'setting'  => 'page_title_bg',
								'operator' => '!=',
								'value'    => '',
							),
							array(
								'setting'  => 'page_title_bg',
								'operator' => '!=',
								'value'    => array(
									'url'  => '',
								),
							),
							array(
								'setting'  => 'page_title_bg',
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
					'page_title_fading' => array(
						'label'       => esc_html__( 'Fading Animation', 'Avada' ),
						'description' => esc_html__( 'Turn on to have the page title text fade on scroll.', 'Avada' ),
						'id'          => 'page_title_fading',
						'default'     => '0',
						'type'        => 'switch',
					),
				),
			),
			'breadcrumb_options_header_info' => array(
				'label'       => esc_html__( 'Breadcrumbs', 'Avada' ),
				'id'          => 'breadcrumb_options_header_info',
				'icon'        => true,
				'type'        => 'sub-section',
				'fields'      => array(
					'page_title_bar_bs' => array(
						'label'       => esc_html__( 'Breadcrumbs Content Display', 'Avada' ),
						'description' => esc_html__( 'Controls what displays in the breadcrumbs area. ', 'Avada' ),
						'id'          => 'page_title_bar_bs',
						'default'     => 'Breadcrumbs',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'none'        => esc_html__( 'None', 'Avada' ),
							'Breadcrumbs' => esc_html__( 'Breadcrumbs', 'Avada' ),
							'Search Box'  => esc_html__( 'Search Box', 'Avada' ),
						),
					),

					'breadcrumb_mobile' => array(
						'label'       => esc_html__( 'Breadcrumbs on Mobile Devices', 'Avada' ),
						'description' => esc_html__( 'Turn on to display breadcrumbs on mobile devices.', 'Avada' ),
						'id'          => 'breadcrumb_mobile',
						'default'     => '0',
						'type'        => 'switch',
					),
					'breacrumb_prefix' => array(
						'label'       => esc_html__( 'Breadcrumbs Prefix', 'Avada' ),
						'description' => esc_html__( 'Controls the text before the breadcrumb menu.', 'Avada' ),
						'id'          => 'breacrumb_prefix',
						'default'     => '',
						'type'        => 'text',
					),
					'breadcrumb_separator' => array(
						'label'       => esc_html__( 'Breadcrumbs Separator', 'Avada' ),
						'description' => esc_html__( 'Controls the type of separator between each breadcrumb.', 'Avada' ),
						'id'          => 'breadcrumb_separator',
						'default'     => '/',
						'type'        => 'text',
					),
					'breadcrumbs_font_size' => array(
						'label'       => esc_html__( 'Breadcrumbs Font Size', 'Avada' ),
						'description' => esc_html__( 'Controls the font size for the breadcrumbs text.', 'Avada' ),
						'id'          => 'breadcrumbs_font_size',
						'default'     => '10px',
						'type'        => 'dimension',
						'choices'     => array(
							'units' => array( 'px', 'em' ),
						),
					),
					'breadcrumbs_text_color' => array(
						'label'       => esc_html__( 'Breadcrumbs Text Color', 'Avada' ),
						'description' => esc_html__( 'Controls the text color of the breadcrumbs font.', 'Avada' ),
						'id'          => 'breadcrumbs_text_color',
						'default'     => '#333333',
						'type'        => 'color',
					),
					'breadcrumb_show_categories' => array(
						'label'       => esc_html__( 'Post Categories on Breadcrumbs', 'Avada' ),
						'description' => esc_html__( 'Turn on to display the post categories in the breadcrumbs path.', 'Avada' ),
						'id'          => 'breadcrumb_show_categories',
						'default'     => '1',
						'type'        => 'switch',
					),
					'breadcrumb_show_post_type_archive' => array(
						'label'       => esc_html__( 'Custom Post Type Archives on Breadcrumbs', 'Avada' ),
						'description' => esc_html__( 'Turn on to display custom post type archives in the breadcrumbs path.', 'Avada' ),
						'id'          => 'breadcrumb_show_post_type_archive',
						'default'     => '0',
						'type'        => 'switch',
					),
				),
			),
		),
	);

	return $sections;

}
