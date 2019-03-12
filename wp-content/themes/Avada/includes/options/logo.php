<?php

/**
 * Logo
 *
 * @var  array  	any existing settings
 * @return array 	existing sections + logo
 *
 */
function avada_options_section_logo( $sections ) {

	$sections['logo'] = array(
		'label'    => esc_html__( 'Logo', 'Avada' ),
		'id'       => 'heading_logo',
		'is_panel' => true,
		'logo'     => 5,
		'icon'     => 'el-icon-plus-sign',
		'fields'   => array(
			'logo_options_wrapper' => array(
				'label'       => esc_html__( 'Logo', 'Avada' ),
				'description' => '',
				'id'          => 'logo_options_wrapper',
				'icon'        => true,
				'position'    => 'start',
				'type'        => 'sub-section',
				'fields'      => array(
					'logo_alignment' => array(
						'label'       => esc_html__( 'Logo Alignment', 'Avada' ),
						'description' => esc_html__( 'Controls the logo alignment. "Center" only works on Header 5 and Side Headers.', 'Avada' ),
						'id'          => 'logo_alignment',
						'default'     => 'Left',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'Left'    => esc_html__( 'Left', 'Avada' ),
							'Center'  => esc_html__( 'Center', 'Avada' ),
							'Right'   => esc_html__( 'Right', 'Avada' ),
						),
						'required'    => array(
							array(
								'setting'  => 'logo',
								'operator' => '!=',
								'value'    => '',
							),
							array(
								'setting'  => 'logo',
								'operator' => '!=',
								'value'    => array(
									'url'  => '',
								),
							),
							array(
								'setting'  => 'logo',
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
					'logo_margin' => array(
						'label'       => esc_html__( 'Logo Margins', 'Avada' ),
						'description' => esc_html__( 'Controls the top/right/bottom/left margins for the logo.', 'Avada' ),
						'id'          => 'logo_margin',
						'default'     => array(
							'top'     => '31px',
							'bottom'  => '31px',
							'left'    => '0px',
							'right'   => '0px',
						),
						'choices'     => array(
							'top'     => true,
							'bottom'  => true,
							'left'    => true,
							'right'   => true,
						),
						'type'        => 'spacing',
						'required'    => array(
							array(
								'setting'  => 'logo',
								'operator' => '!=',
								'value'    => '',
							),
							array(
								'setting'  => 'logo',
								'operator' => '!=',
								'value'    => array(
									'url'  => '',
								),
							),
							array(
								'setting'  => 'logo',
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
					'default_logo_info_title' => array(
						'label'       => esc_html__( 'Default Logo', 'Avada' ),
						'description' => '',
						'id'          => 'default_logo_info_title',
						'icon'        => true,
						'type'        => 'info'
					),
					'logo' => array(
						'label'       => esc_html__( 'Default Logo', 'Avada' ),
						'description' => esc_html__( 'Select an image file for your logo.', 'Avada' ),
						'id'          => 'logo',
						'default'     => get_template_directory_uri() . '/assets/images/logo.png',
						'mod'         => 'min',
						'type'        => 'media'
					),
					'logo_retina' => array(
						'label'       => esc_html__( 'Retina Default Logo', 'Avada' ),
						'description' => esc_html__( 'Select an image file for the retina version of the logo. It should be exactly 2x the size of the main logo.', 'Avada' ),
						'id'          => 'logo_retina',
						'default'     => '',
						'mod'         => 'min',
						'type'        => 'media',
						'required'    => array(
							array(
								'setting'  => 'logo',
								'operator' => '!=',
								'value'    => '',
							),
							array(
								'setting'  => 'logo',
								'operator' => '!=',
								'value'    => array(
									'url'  => '',
								),
							),
							array(
								'setting'  => 'logo',
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
					'sticky_logo_info_title' => array(
						'label'       => esc_html__( 'Sticky Header Logo', 'Avada' ),
						'description' => esc_html__( 'Select an image file for your sticky header logo.', 'Avada' ),
						'description' => '',
						'id'          => 'sticky_logo_info_title',
						'icon'        => true,
						'type'        => 'info'
					),
					'sticky_header_logo' => array(
						'label'       => esc_html__( 'Sticky Header Logo', 'Avada' ),
						'description' => esc_html__( 'Select an image file for your sticky header logo.', 'Avada' ),
						'id'          => 'sticky_header_logo',
						'default'     => '',
						'mod'         => 'min',
						'type'        => 'media'
					),
					'sticky_header_logo_retina' => array(
						'label'       => esc_html__( 'Retina Sticky Header Logo', 'Avada' ),
						'description' => esc_html__( 'Select an image file for the retina version of the sticky header logo. It should be exactly 2x the size of the sticky header logo.', 'Avada' ),
						'id'          => 'sticky_header_logo_retina',
						'default'     => '',
						'mod'         => 'min',
						'type'        => 'media',
						'required'    => array(
							array(
								'setting'  => 'sticky_header_logo',
								'operator' => '!=',
								'value'    => '',
							),
							array(
								'setting'  => 'sticky_header_logo',
								'operator' => '!=',
								'value'    => array(
									'url'  => '',
								),
							),
							array(
								'setting'  => 'sticky_header_logo',
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
					'mobile_logo_info_title' => array(
						'label'       => esc_html__( 'Mobile Logo', 'Avada' ),
						'description' => '',
						'id'          => 'mobile_logo_info_title',
						'icon'        => true,
						'type'        => 'info'
					),
					'mobile_logo' => array(
						'label'       => esc_html__( 'Mobile Logo', 'Avada' ),
						'description' => esc_html__( 'Select an image file for your mobile logo.', 'Avada' ),
						'id'          => 'mobile_logo',
						'default'     => '',
						'mod'         => 'min',
						'type'        => 'media'
					),
					'mobile_logo_retina' => array(
						'label'       => esc_html__( 'Retina Mobile Logo', 'Avada' ),
						'description' => esc_html__( 'Select an image file for the retina version of the mobile logo. It should be exactly 2x the size of the mobile logo.', 'Avada' ),
						'id'          => 'mobile_logo_retina',
						'default'     => '',
						'mod'         => 'min',
						'type'        => 'media',
						'required'    => array(
							array(
								'setting'  => 'mobile_logo',
								'operator' => '!=',
								'value'    => '',
							),
							array(
								'setting'  => 'mobile_logo',
								'operator' => '!=',
								'value'    => array(
									'url'  => '',
								),
							),
							array(
								'setting'  => 'mobile_logo',
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
			'favicons' => array(
				'label'       => esc_html__( 'Favicon', 'Avada' ),
				'description' => '',
				'id'          => 'favicons',
				'icon'        => true,
				'position'    => 'start',
				'type'        => 'sub-section',
				'fields'      => array(
					'favicon' => array(
						'label'       => esc_html__( 'Favicon', 'Avada' ),
						'description' => esc_html__( 'Favicon for your website at 16px x 16px.', 'Avada' ),
						'id'          => 'favicon',
						'default'     => '',
						'type'        => 'media'
					),
					'iphone_icon' => array(
						'label'       => esc_html__( 'Apple iPhone Icon Upload', 'Avada' ),
						'description' => esc_html__( 'Favicon for Apple iPhone at 57px x 57px.', 'Avada' ),
						'id'          => 'iphone_icon',
						'default'     => '',
						'type'        => 'media'
					),
					'iphone_icon_retina' => array(
						'label'       => esc_html__( 'Apple iPhone Retina Icon Upload', 'Avada' ),
						'description' => esc_html__( 'Favicon for Apple iPhone Retina Version at 114px x 114px.', 'Avada' ),
						'id'          => 'iphone_icon_retina',
						'default'     => '',
						'type'        => 'media',
						'required'    => array(
							array(
								'setting'  => 'iphone_icon',
								'operator' => '!=',
								'value'    => '',
							),
							array(
								'setting'  => 'iphone_icon',
								'operator' => '!=',
								'value'    => array(
									'url'  => '',
								),
							),
							array(
								'setting'  => 'iphone_icon',
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
					'ipad_icon' => array(
						'label'       => esc_html__( 'Apple iPad Icon Upload', 'Avada' ),
						'description' => esc_html__( 'Favicon for Apple iPad at 72px x 72px.', 'Avada' ),
						'id'          => 'ipad_icon',
						'default'     => '',
						'type'        => 'media'
					),
					'ipad_icon_retina' => array(
						'label'       => esc_html__( 'Apple iPad Retina Icon Upload', 'Avada' ),
						'description' => esc_html__( 'Favicon for Apple iPad Retina Version at 144px x 144px.', 'Avada' ),
						'id'          => 'ipad_icon_retina',
						'default'     => '',
						'type'        => 'media',
						'required'    => array(
							array(
								'setting'  => 'ipad_icon',
								'operator' => '!=',
								'value'    => '',
							),
							array(
								'setting'  => 'ipad_icon',
								'operator' => '!=',
								'value'    => array(
									'url'  => '',
								),
							),
							array(
								'setting'  => 'ipad_icon',
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
		),
	);

	return $sections;

}
