<?php

/**
 * Social Media
 *
 * @var  array  	any existing settings
 * @return array 	existing sections + social_media
 *
 */
function avada_options_section_social_media( $sections ) {

	$sections['social_media'] = array(
		'label'    => esc_html__( 'Social Media', 'Avada' ),
		'id'       => 'heading_social_media',
		'priority' => 18,
		'icon'     => 'el-icon-share-alt',
		'fields'   => array(
			'social_media_icons_section' => array(
				'label'       => esc_html__( 'Social Media Icons', 'Avada' ),
				'id'          => 'social_media_icons_section',
				'icon'        => true,
				'type'        => 'sub-section',
				'fields'      => array(
					'social_media_icons_important_note_info' => array(
						'label'       => '',
						'description' => '<div class="avada-avadaredux-important-notice">' . __( '<strong>IMPORTANT NOTE:</strong> This tab controls the social networks that display in the header and footer. Add the network of your choice along with your unique URL. Each network you wish to display must be added here to show up in the header and footer. These settings do not control the avada social widget, social link shortcode or person shortcode.', 'Avada' ) . '</div>',
						'id'          => 'social_media_icons_important_note_info',
						'type'        => 'custom',
					),
					'social_media_icons' => array(
						'label'      => esc_html__( 'Social Media Links', 'Avada' ),
						'description'=> esc_html__( 'Social media links use a repeater field and allow one network per field. Click the "Add" button to add additional fields.', 'Avada' ),
						'id'         => 'social_media_icons',
						'default'    => array(),
						'type'       => 'repeater',
						'bind_title' => 'icon',
						'fields'     => array(
							'icon' => array(
								'type'        => 'select',
								'description' => esc_html__( 'Select a social network to automatically add its icon', 'Avada' ),
								'default'     => 'none',
								'choices'     => Avada_Data::fusion_social_icons( true, false ),
							),
							'url' => array(
								'type'        => 'text',
								'label'       => esc_html__( 'Link (URL)', 'Avada' ),
								'description' => esc_html__( 'Insert your custom link here', 'Avada' ),
								'default'     => '',
							),
							'custom_title' => array(
								'type'        => 'text',
								'label'       => esc_html__( 'Custom Icon Title', 'Avada' ),
								'description' => esc_html__( 'Insert your custom link here', 'Avada' ),
								'default'     => '',
							),
							'custom_source' => array(
								'type'        => 'media',
								'label'       => esc_html__( 'Link (URL) of the image you want to use as the icon', 'Avada' ),
								'description' => esc_html__( 'Upload your custom icon', 'Avada' ),
								'default'     => '',
							),
						),
					),
				),
			),
			'header_social_icons_options' => array(
				'label'       => esc_html__( 'Header Social Icons', 'Avada' ),
				'description' => '',
				'id'          => 'header_social_icons_options',
				'default'     => '',
				'icon'        => true,
				'type'        => 'sub-section',
				'fields'      => array(
					'header_social_links_font_size' => array(
						'label'       => esc_html__( 'Header Social Icon Font Size', 'Avada' ),
						'description' => esc_html__( 'Controls the font size of the header social icons.', 'Avada' ),
						'id'          => 'header_social_links_font_size',
						'default'     => '16px',
						'type'        => 'dimension',
					),
					'header_social_links_tooltip_placement' => array(
						'label'       => esc_html__( 'Header Social Icon Tooltip Position', 'Avada' ),
						'description' => esc_html__( 'Controls the tooltip position of the header social icons.', 'Avada' ),
						'id'          => 'header_social_links_tooltip_placement',
						'default'     => 'Bottom',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'Top'    => esc_html__( 'Top', 'Avada' ),
							'Right'  => esc_html__( 'Right', 'Avada' ),
							'Bottom' => esc_html__( 'Bottom', 'Avada' ),
							'Left'   => esc_html__( 'Left', 'Avada' ),
							'None'   => esc_html__( 'None', 'Avada' ),
						)
					),
					'header_social_links_color_type' => array(
						'label'       => esc_html__( 'Header Social Icon Color Type', 'Avada' ),
						'description' => esc_html__( 'Custom colors allow you to choose a color for icons and boxes. Brand colors will use the exact brand color of each network for the icons or boxes.', 'Avada' ),
						'id'          => 'header_social_links_color_type',
						'default'     => 'custom',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'custom' => esc_html__( 'Custom Colors', 'Avada' ),
							'brand'  => esc_html__( 'Brand Colors', 'Avada' ),
						),
					),
					'header_social_links_icon_color' => array(
						'label'       => esc_html__( 'Header Social Icon Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the header social icons. This color will be used for all social icons in the header.', 'Avada' ),
						'id'          => 'header_social_links_icon_color',
						'default'     => '#bebdbd',
						'type'        => 'color-alpha',
						'required'    => array(
							array(
								'setting'  => 'header_social_links_color_type',
								'operator' => '==',
								'value'    => 'custom',
							),
						),
					),
					'header_social_links_boxed' => array(
						'label'       => esc_html__( 'Header Social Icons Boxed', 'Avada' ),
						'description' => esc_html__( 'Controls if each icon is displayed in a small box.', 'Avada' ),
						'id'          => 'header_social_links_boxed',
						'default'     => '0',
						'type'        => 'switch',
					),
					'header_social_links_box_color' => array(
						'label'       => esc_html__( 'Header Social Icon Box Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the social icon box.', 'Avada' ),
						'id'          => 'header_social_links_box_color',
						'default'     => '#e8e8e8',
						'type'        => 'color-alpha',
						'required'    => array(
							array(
								'setting'  => 'header_social_links_boxed',
								'operator' => '==',
								'value'    => '1',
							),
							array(
								'setting'  => 'header_social_links_color_type',
								'operator' => '==',
								'value'    => 'custom',
							),
						),
					),
					'header_social_links_boxed_radius' => array(
						'label'       => esc_html__( 'Header Social Icon Boxed Radius', 'Avada' ),
						'description' => esc_html__( 'Controls the box radius', 'Avada' ),
						'id'          => 'header_social_links_boxed_radius',
						'default'     => '4px',
						'type'        => 'dimension',
						'required'    => array(
							array(
								'setting'  => 'header_social_links_boxed',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'header_social_links_boxed_padding' => array(
						'label'       => esc_html__( 'Header Social Icon Boxed Padding', 'Avada' ),
						'description' => esc_html__( 'Controls the interior padding of the box.', 'Avada' ),
						'id'          => 'header_social_links_boxed_padding',
						'default'     => '8px',
						'type'        => 'dimension',
						'required'    => array(
							array(
								'setting'  => 'header_social_links_boxed',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
				),
			),
			'footer_social_icons_options' => array(
				'label'       => esc_html__( 'Footer Social Icons', 'Avada' ),
				'description' => '',
				'id'          => 'footer_social_icons_options',
				'default'     => '',
				'icon'        => true,
				'type'        => 'sub-section',
				'fields'      => array(
					'icons_footer' => array(
						'label'       => esc_html__( 'Display Social Icons In The Footer', 'Avada' ),
						'description' => esc_html__( 'Turn on to display social icons in the footer copyright bar.', 'Avada' ),
						'id'          => 'icons_footer',
						'default'     => '1',
						'type'        => 'switch',
					),
					'footer_social_links_font_size' => array(
						'label'       => esc_html__( 'Footer Social Icon Font Size', 'Avada' ),
						'description' => esc_html__( 'Controls the font size of the footer social icons.', 'Avada' ),
						'id'          => 'footer_social_links_font_size',
						'default'     => '16px',
						'type'        => 'dimension',
						'required'    => array(
							array(
								'setting'  => 'icons_footer',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'footer_social_links_tooltip_placement' => array(
						'label'       => esc_html__( 'Footer Social Icon Tooltip Position', 'Avada' ),
						'description' => esc_html__( 'Controls the tooltip position of the footer social icons.', 'Avada' ),
						'id'          => 'footer_social_links_tooltip_placement',
						'default'     => 'Top',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'Top'    => esc_html__( 'Top', 'Avada' ),
							'Right'  => esc_html__( 'Right', 'Avada' ),
							'Bottom' => esc_html__( 'Bottom', 'Avada' ),
							'Left'   => esc_html__( 'Left', 'Avada' ),
							'None'   => esc_html__( 'None', 'Avada' ),
						),
						'required'    => array(
							array(
								'setting'  => 'icons_footer',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'footer_social_links_color_type' => array(
						'label'       => esc_html__( 'Footer Social Icon Color Type', 'Avada' ),
						'description' => esc_html__( 'Custom colors allow you to choose a color for icons and boxes. Brand colors will use the exact brand color of each network for the icons or boxes.', 'Avada' ),
						'id'          => 'footer_social_links_color_type',
						'default'     => 'custom',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'custom' => esc_html__( 'Custom Colors', 'Avada' ),
							'brand'  => esc_html__( 'Brand Colors', 'Avada' ),
						),
						'required'    => array(
							array(
								'setting'  => 'icons_footer',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'footer_social_links_icon_color' => array(
						'label'       => esc_html__( 'Footer Social Icon Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the footer social icons. This color will be used for all social icons in the footer.', 'Avada' ),
						'id'          => 'footer_social_links_icon_color',
						'type'        => 'color-alpha',
						'default'     => '#46494a',
						'required'    => array(
							array(
								'setting'  => 'icons_footer',
								'operator' => '==',
								'value'    => '1',
							),
							array(
								'setting'  => 'footer_social_links_color_type',
								'operator' => '==',
								'value'    => 'custom',
							)
						),
					),
					'footer_social_links_boxed' => array(
						'label'       => esc_html__( 'Footer Social Icons Boxed', 'Avada' ),
						'description' => esc_html__( 'Controls if each icon is displayed in a small box.', 'Avada' ),
						'id'          => 'footer_social_links_boxed',
						'default'     => '0',
						'type'        => 'switch',
						'required'    => array(
							array(
								'setting'  => 'icons_footer',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'footer_social_links_box_color' => array(
						'label'       => esc_html__( 'Footer Social Icon Box Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the social icon box.', 'Avada' ),
						'id'          => 'footer_social_links_box_color',
						'default'     => '#222222',
						'type'        => 'color-alpha',
						'required'    => array(
							array(
								'setting'  => 'icons_footer',
								'operator' => '==',
								'value'    => '1',
							),
							array(
								'setting'  => 'footer_social_links_boxed',
								'operator' => '==',
								'value'    => '1',
							),
							array(
								'setting'  => 'footer_social_links_color_type',
								'operator' => '==',
								'value'    => 'custom',
							)
						),
					),
					'footer_social_links_boxed_radius' => array(
						'label'       => esc_html__( 'Footer Social Icon Boxed Radius', 'Avada' ),
						'description' => esc_html__( 'Controls the box radius.', 'Avada' ),
						'id'          => 'footer_social_links_boxed_radius',
						'default'     => '4px',
						'type'        => 'dimension',
						'required'    => array(
							array(
								'setting'  => 'icons_footer',
								'operator' => '==',
								'value'    => '1',
							),
							array(
								'setting'  => 'footer_social_links_boxed',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'footer_social_links_boxed_padding' => array(
						'label'       => esc_html__( 'Footer Social Icon Boxed Padding', 'Avada' ),
						'description' => esc_html__( 'Controls the interior padding of the box.', 'Avada' ),
						'id'          => 'footer_social_links_boxed_padding',
						'default'     => '8px',
						'type'        => 'dimension',
						'required'    => array(
							array(
								'setting'  => 'icons_footer',
								'operator' => '==',
								'value'    => '1',
							),
							array(
								'setting'  => 'footer_social_links_boxed',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
				),
			),
			'heading_social_sharing_box' => array(
				'label'       => esc_html__( 'Social Sharing Box', 'Avada' ),
				'id'          => 'heading_social_sharing_box',
				'icon'        => true,
				'type'        => 'sub-section',
				'fields'      => array(
					'sharing_social_tagline' => array(
						'label'       => esc_html__( 'Sharing Box Tagline', 'Avada' ),
						'description' => esc_html__( 'Insert a tagline for the social sharing boxes.', 'Avada' ),
						'id'          => 'sharing_social_tagline',
						'default'     => esc_html__( 'Share This Story, Choose Your Platform!', 'Avada' ),
						'type'        => 'text',
					),
					'sharing_box_tagline_text_color' => array(
						'label'       => esc_html__( 'Sharing Box Tagline Text Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the tagline text in the social sharing boxes.', 'Avada' ),
						'id'          => 'sharing_box_tagline_text_color',
						'default'     => '#333333',
						'type'        => 'color'
					),
					'social_bg_color' => array(
						'label'       => esc_html__( 'Sharing Box Background Color', 'Avada' ),
						'description' => esc_html__( 'Controls the background color of the social sharing boxes.', 'Avada' ),
						'id'          => 'social_bg_color',
						'default'     => '#f6f6f6',
						'type'        => 'color-alpha',
					),
					'social_share_box_icon_info' => array(
						'label'       => esc_html__( 'Social Sharing Box Icons', 'Avada' ),
						'description' => '',
						'id'          => 'social_share_box_icon_info',
						'icon'        => true,
						'type'        => 'info'
					),
					'sharing_social_links_font_size' => array(
						'label'       => esc_html__( 'Sharing Box Icon Font Size', 'Avada' ),
						'description' => esc_html__( 'Controls the font size of the social icons in the social sharing boxes.', 'Avada' ),
						'id'          => 'sharing_social_links_font_size',
						'default'     => '16px',
						'type'        => 'dimension',
					),
					'sharing_social_links_tooltip_placement' => array(
						'label'       => esc_html__( 'Sharing Box Icons Tooltip Position', 'Avada' ),
						'description' => esc_html__( 'Controls the tooltip position of the social icons in the social sharing boxes.', 'Avada' ),
						'id'          => 'sharing_social_links_tooltip_placement',
						'default'     => 'Top',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'Top'    => esc_html__( 'Top', 'Avada' ),
							'Right'  => esc_html__( 'Right', 'Avada' ),
							'Bottom' => esc_html__( 'Bottom', 'Avada' ),
							'Left'   => esc_html__( 'Left', 'Avada' ),
							'None'   => esc_html__( 'None', 'Avada' ),
						)
					),
					'sharing_social_links_color_type' => array(
						'label'       => esc_html__( 'Sharing Box Icon Color Type', 'Avada' ),
						'description' => esc_html__( 'Custom colors allow you to choose a color for icons and boxes. Brand colors will use the exact brand color of each network for the icons or boxes.', 'Avada' ),
						'id'          => 'sharing_social_links_color_type',
						'default'     => 'custom',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'custom' => esc_html__( 'Custom Colors', 'Avada' ),
							'brand'  => esc_html__( 'Brand Colors', 'Avada' ),
						),
					),
					'sharing_social_links_icon_color' => array(
						'label'       => esc_html__( 'Sharing Box Icon Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the social icons in the social sharing boxes. This color will be used for all social icons.', 'Avada' ),
						'id'          => 'sharing_social_links_icon_color',
						'default'     => '#bebdbd',
						'type'        => 'color',
						'required'    => array(
							array(
								'setting'  => 'sharing_social_links_color_type',
								'operator' => '==',
								'value'    => 'custom',
							),
						),
					),
					'sharing_social_links_boxed' => array(
						'label'       => esc_html__( 'Sharing Box Icons Boxed', 'Avada' ),
						'description' => esc_html__( 'Controls if each social icon is displayed in a small box.', 'Avada' ),
						'id'          => 'sharing_social_links_boxed',
						'default'     => '0',
						'type'        => 'switch',
					),
					'sharing_social_links_box_color' => array(
						'label'       => esc_html__( 'Sharing Box Icon Box Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the social icon box.', 'Avada' ),
						'id'          => 'sharing_social_links_box_color',
						'default'     => '#e8e8e8',
						'type'        => 'color-alpha',
						'required'    => array(
							array(
								'setting'  => 'sharing_social_links_boxed',
								'operator' => '==',
								'value'    => '1',
							),
							array(
								'setting'  => 'sharing_social_links_color_type',
								'operator' => '==',
								'value'    => 'custom',
							),
						),
					),
					'sharing_social_links_boxed_radius' => array(
						'label'       => esc_html__( 'Sharing Box Icon Boxed Radius', 'Avada' ),
						'description' => esc_html__( 'Controls the box radius of the social icon box.', 'Avada' ),
						'id'          => 'sharing_social_links_boxed_radius',
						'default'     => '4px',
						'type'        => 'dimension',
						'required'    => array(
							array(
								'setting'  => 'sharing_social_links_boxed',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'sharing_social_links_boxed_padding' => array(
						'label'       => esc_html__( 'Sharing Box Icons Boxed Padding', 'Avada' ),
						'description' => esc_html__( 'Controls the interior padding of the social icon box.', 'Avada' ),
						'id'          => 'sharing_social_links_boxed_padding',
						'default'     => '8px',
						'type'        => 'dimension',
						'required'    => array(
							array(
								'setting'  => 'sharing_social_links_boxed',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'social_share_box_links_title' => array(
						'label'       => esc_html__( 'Sharing Box Links', 'Avada' ),
						'description' => '',
						'id'          => 'social_share_box_links_title',
						'icon'        => true,
						'type'        => 'info'
					),
					'sharing_facebook' => array(
						'label'       => esc_html__( 'Facebook', 'Avada' ),
						'description' => sprintf( esc_html__( 'Turn on to display %s in the social share box.', 'Avada' ), esc_html__( 'Facebook', 'Avada' ) ),
						'id'          => 'sharing_facebook',
						'default'     => '1',
						'type'        => 'toggle',
					),
					'sharing_twitter' => array(
						'label'       => esc_html__( 'Twitter', 'Avada' ),
						'description' => sprintf( esc_html__( 'Turn on to display %s in the social share box.', 'Avada' ), esc_html__( 'Twitter', 'Avada' ) ),
						'id'          => 'sharing_twitter',
						'default'     => '1',
						'type'        => 'toggle',
					),
					'sharing_reddit' => array(
						'label'       => esc_html__( 'Reddit', 'Avada' ),
						'description' => sprintf( esc_html__( 'Turn on to display %s in the social share box.', 'Avada' ), esc_html__( 'Reddit', 'Avada' ) ),
						'id'          => 'sharing_reddit',
						'default'     => '1',
						'type'        => 'toggle',
					),
					'sharing_linkedin' => array(
						'label'       => esc_html__( 'LinkedIn', 'Avada' ),
						'description' => sprintf( esc_html__( 'Turn on to display %s in the social share box.', 'Avada' ), esc_html__( 'LinkedIn', 'Avada' ) ),
						'id'          => 'sharing_linkedin',
						'default'     => '1',
						'type'        => 'toggle',
					),
					'sharing_google' => array(
						'label'       => esc_html__( 'Google+', 'Avada' ),
						'description' => sprintf( esc_html__( 'Turn on to display %s in the social share box.', 'Avada' ), esc_html__( 'Google+', 'Avada' ) ),
						'id'          => 'sharing_google',
						'default'     => '1',
						'type'        => 'toggle',
					),
					'sharing_tumblr' => array(
						'label'       => esc_html__( 'Tumblr', 'Avada' ),
						'description' => sprintf( esc_html__( 'Turn on to display %s in the social share box.', 'Avada' ), esc_html__( 'Tumblr', 'Avada' ) ),
						'id'          => 'sharing_tumblr',
						'default'     => '1',
						'type'        => 'toggle',
					),
					'sharing_pinterest' => array(
						'label'       => esc_html__( 'Pinterest', 'Avada' ),
						'description' => sprintf( esc_html__( 'Turn on to display %s in the social share box.', 'Avada' ), esc_html__( 'Pinterest', 'Avada' ) ),
						'id'          => 'sharing_pinterest',
						'default'     => '1',
						'type'        => 'toggle',
					),
					'sharing_vk' => array(
						'label'       => esc_html__( 'VK', 'Avada' ),
						'description' => sprintf( esc_html__( 'Turn on to display %s in the social share box.', 'Avada' ), esc_html__( 'VK', 'Avada' ) ),
						'id'          => 'sharing_vk',
						'default'     => '1',
						'type'        => 'toggle',
					),
					'sharing_email' => array(
						'label'       => esc_html__( 'Email', 'Avada' ),
						'description' => sprintf( esc_html__( 'Turn on to display %s in the social share box.', 'Avada' ), esc_html__( 'Email', 'Avada' ) ),
						'id'          => 'sharing_email',
						'default'     => '1',
						'type'        => 'toggle'
					),
				),
			),
		),
	);

	return $sections;

}
