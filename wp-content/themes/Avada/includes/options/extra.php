<?php

/**
 * Extra settings
 *
 * @var  array  	any existing settings
 * @return array 	existing sections + extra
 *
 */
function avada_options_section_extra( $sections ) {

	$sections['extras'] = array(
		'label'    => esc_html__( 'Extra', 'Avada' ),
		'id'       => 'extra_section',
		'priority' => 24,
		'icon'     => 'el-icon-cogs',
		'fields'   => array(
			'misc_options_section' => array(
				'label'       => esc_html__( 'Miscellaneous', 'Avada' ),
				'description' => '',
				'id'          => 'misc_options_section',
				'icon'        => true,
				'type'        => 'sub-section',
				'fields'      => array(
					'sidenav_behavior' => array(
						'label'       => esc_html__( 'Side Navigation Behavior', 'Avada' ),
						'description' => esc_html__( 'Controls if the child pages show on click or hover for the side navigation page template.', 'Avada' ),
						'id'          => 'sidenav_behavior',
						'default'     => 'Hover',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'Hover' => esc_html__( 'Hover', 'Avada' ),
							'Click' => esc_html__( 'Click', 'Avada' ),
						),
					),
					'featured_image_placeholder' => array(
						'label'       => esc_html__( 'Image Placeholders', 'Avada' ),
						'description' => esc_html__( 'Turn on to display a placeholder image for posts that do not have a featured image. This allows the post to display on portfolio archives and related posts/projects carousels.', 'Avada' ),
						'id'          => 'featured_image_placeholder',
						'default'     => '1',
						'type'        => 'switch'
					),
					'excerpt_base' => array(
						'label'       => esc_html__( 'Basis for Excerpt Length', 'Avada' ),
						'description' => esc_html__( 'Controls if the excerpt length is based on words or characters.', 'Avada' ),
						'id'          => 'excerpt_base',
						'default'     => 'Words',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'Words'      => esc_html__( 'Words', 'Avada' ),
							'Characters' => esc_html__( 'Characters', 'Avada' ),
						),
					),
					'disable_excerpts' => array(
						'label'       => esc_html__( 'Excerpt [...] Display', 'Avada' ),
						'description' => esc_html__( 'Turn on to display the read more sign [...] on excerpts throughout the site.', 'Avada' ),
						'id'          => 'disable_excerpts',
						'default'     => '1',
						'type'        => 'switch'
					),
					'link_read_more' => array(
						'label'       => esc_html__( 'Make [...] Link to Single Post Page', 'Avada' ),
						'description' => esc_html__( 'Turn on to have the read more sign [...] on excerpts link to the single post page.', 'Avada' ),
						'id'          => 'link_read_more',
						'default'     => '0',
						'type'        => 'switch'
					),
					'comments_pages' => array(
						'label'       => esc_html__( 'Comments on Pages', 'Avada' ),
						'description' => esc_html__( 'Turn on to allow comments on regular pages.', 'Avada' ),
						'id'          => 'comments_pages',
						'default'     => '0',
						'type'        => 'switch'
					),
					'featured_images_pages' => array(
						'label'       => esc_html__( 'Featured Images on Pages', 'Avada' ),
						'description' => esc_html__( 'Turn on to display featured images on regular pages.', 'Avada' ),
						'id'          => 'featured_images_pages',
						'default'     => '1',
						'type'        => 'switch'
					),
					'faq_featured_image' => array(
						'label'       => esc_html__( 'FAQ Featured Images', 'Avada' ),
						'description' => esc_html__( 'Turn on to display featured images on FAQ archive page.', 'Avada' ),
						'id'          => 'faq_featured_image',
						'default'     => '0',
						'type'        => 'switch'
					),
					'faq_filters' => array(
						'label'       => esc_html__( 'FAQ Filters', 'Avada' ),
						'description' => esc_html__( 'Controls how the filters display for FAQs.', 'Avada' ),
						'id'          => 'faq_filters',
						'default'     => 'yes',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'yes'             => esc_html__( 'Show', 'Avada' ),
							'yes_without_all' => esc_html__( 'Show without "All"', 'Avada' ),
							'no'              => esc_html__( 'Hide', 'Avada' ),
						),
					),
					'nofollow_social_links' => array(
						'label'       => esc_html__( 'Add "nofollow" to social links', 'Avada' ),
						'description' => esc_html__( 'Turn on to add "nofollow" attribute to all social links.', 'Avada' ),
						'id'          => 'nofollow_social_links',
						'default'     => '0',
						'type'        => 'switch'
					),
					'social_icons_new' => array(
						'label'       => esc_html__( 'Open Social Icons in a New Window', 'Avada' ),
						'description' => esc_html__( 'Turn on to allow social icons to open in a new window.', 'Avada' ),
						'id'          => 'social_icons_new',
						'default'     => '1',
						'type'        => 'switch'
					),
				),
			),
			'related_posts_section' => array(
				'label'       => esc_html__( 'Related Posts / Projects', 'Avada' ),
				'description' => '',
				'id'          => 'related_posts_section',
				'type'        => 'sub-section',
				'fields'      => array(
					'related_posts_layout' => array(
						'label'       => esc_html__( 'Related Posts / Projects Layout', 'Avada' ),
						'description' => esc_html__( 'Controls the layout style for related posts and related projects.', 'Avada' ),
						'id'          => 'related_posts_layout',
						'default'     => 'title_on_rollover',
						'type'        => 'select',
						'choices'     => array(
							'title_on_rollover' => esc_html__( 'Title on rollover', 'Avada' ),
							'title_below_image' => esc_html__( 'Title below image', 'Avada' ),
						)
					),
					'number_related_posts' => array(
						'label'       => esc_html__( 'Number of Related Posts / Projects', 'Avada' ),
						'description' => esc_html__( 'Controls the number of related posts and projects that display on a single post.', 'Avada' ),
						'id'          => 'number_related_posts',
						'default'     => '5',
						'type'        => 'slider',
						'choices'     => array(
							'min'  => '0',
							'max'  => '30',
							'step' => '1',
						),
					),
					'related_posts_columns' => array(
						'label'       => esc_html__( 'Related Posts / Projects Maximum Columns', 'Avada' ),
						'description' => esc_html__( 'Controls the number of columns for the related posts and projects layout.', 'Avada' ),
						'id'          => 'related_posts_columns',
						'default'     => 5,
						'type'        => 'slider',
						'choices'     => array(
							'min'  => 1,
							'max'  => 6,
							'step' => 1,
						),
					),
					'related_posts_column_spacing' => array(
						'label'       => esc_html__( 'Related Posts / Projects Column Spacing', 'Avada' ),
						'description' => esc_html__( 'Controls the amount of spacing between columns for the related posts and projects.', 'Avada' ),
						'id'          => 'related_posts_column_spacing',
						'default'     => '44',
						'type'        => 'slider',
						'choices'     => array(
							'min'  => '0',
							'step' => '1',
							'max'  => '300',
							'edit' => 'yes',
						),
					),
					'related_posts_image_size' => array(
						'label'       => esc_html__( 'Related Posts / Projects Image Size', 'Avada' ),
						'description' => esc_html__( 'Controls if the featured image size is fixed (cropped) or auto (full image ratio) for related posts and projects. IMPORTANT: Fixed works best with a standard 940px site width. Auto works best with larger site widths.', 'Avada' ),
						'id'          => 'related_posts_image_size',
						'default'     => 'cropped',
						'type'        => 'select',
						'choices'     => array(
							'cropped' => esc_html__( 'Fixed', 'Avada' ),
							'full'    => esc_html__( 'Auto', 'Avada' ),
						),
					),
					'related_posts_autoplay' => array(
						'label'       => esc_html__( 'Related Posts / Projects Autoplay', 'Avada' ),
						'description' => esc_html__( 'Turn on to autoplay the related posts and project carousel.', 'Avada' ),
						'id'          => 'related_posts_autoplay',
						'default'     => '0',
						'type'        => 'switch'
					),
					'related_posts_speed' => array(
						'label'       => esc_html__( 'Related Posts / Projects Speed', 'Avada' ),
						'description' => esc_html__( 'Controls the speed of related posts and project carousel. ex: 1000 = 1 second.', 'Avada' ),
						'id'          => 'related_posts_speed',
						'default'     => '2500',
						'type'        => 'slider',
						'choices'     => array(
							'min'  => '1000',
							'max'  => '20000',
							'step' => '250',
						),
					),
					'related_posts_navigation' => array(
						'label'       => esc_html__( 'Related Posts / Projects Show Navigation', 'Avada' ),
						'description' => esc_html__( 'Turn on to display navigation arrows on the carousel.', 'Avada' ),
						'id'          => 'related_posts_navigation',
						'default'     => '1',
						'type'        => 'switch'
					),
					'related_posts_swipe' => array(
						'label'       => esc_html__( 'Related Posts / Projects Mouse Scroll', 'Avada' ),
						'description' => esc_html__( 'Turn on to enable mouse drag control on the carousel.', 'Avada' ),
						'id'          => 'related_posts_swipe',
						'default'     => '0',
						'type'        => 'switch'
					),
					'related_posts_swipe_items' => array(
						'label'       => esc_html__( 'Related Posts / Projects Scroll Items', 'Avada' ),
						'description' => esc_html__( 'Controls the number of items that scroll at one time. Set to 0 to scroll the number of visible items.', 'Avada' ),
						'id'          => 'related_posts_swipe_items',
						'default'     => '',
						'type'        => 'slider',
						'choices'     => array(
							'min'  => '0',
							'max'  => '15',
							'step' => '1',
						),
					),
				),
			),
			'rollover_sub_section' => array(
				'label'       => esc_html__( 'Featured Image Rollover', 'Avada' ),
				'description' => '',
				'id'          => 'rollover_sub_section',
				'type'        => 'sub-section',
				'fields'      => array(
					'image_rollover' => array(
						'label'       => esc_html__( 'Image Rollover', 'Avada' ),
						'description' => esc_html__( 'Turn on to display the rollover graphic on blog and portfolio featured images.', 'Avada' ),
						'id'          => 'image_rollover',
						'default'     => '1',
						'type'        => 'switch'
					),
					'image_rollover_direction' => array(
						'label'       => esc_html__( 'Image Rollover Direction', 'Avada' ),
						'description' => esc_html__( 'Controls the direction the rollover starts from.', 'Avada' ),
						'id'          => 'image_rollover_direction',
						'default'     => 'left',
						'type'        => 'select',
						'choices'     => array(
							'fade'            => esc_html__( 'Fade', 'Avada' ),
							'left'            => esc_html__( 'Left', 'Avada' ),
							'right'           => esc_html__( 'Right', 'Avada' ),
							'bottom'          => esc_html__( 'Bottom', 'Avada' ),
							'top'             => esc_html__( 'Top', 'Avada' ),
							'center_horiz'    => esc_html__( 'Center Horizontal', 'Avada' ),
							'center_vertical' => esc_html__( 'Center Vertical', 'Avada' ),
						),
						'required'    => array(
							array(
								'setting'  => 'image_rollover',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'image_rollover_icon_size' => array(
						'label'       => esc_html__( 'Image Rollover Icon Font Size', 'Avada' ),
						'description' => esc_html__( 'Controls the size of the rollover icons.', 'Avada' ),
						'id'          => 'image_rollover_icon_size',
						'default'     => '15px',
						'type'        => 'dimension',
						'required'    => array(
							array(
								'setting'  => 'image_rollover',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'link_image_rollover' => array(
						'label'       => esc_html__( 'Image Rollover Link Icon', 'Avada' ),
						'description' => esc_html__( 'Turn on to display the link icon in the image rollover.', 'Avada' ),
						'id'          => 'link_image_rollover',
						'default'     => '1',
						'type'        => 'switch',
						'required'    => array(
							array(
								'setting'  => 'image_rollover',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'zoom_image_rollover' => array(
						'label'       => esc_html__( 'Image Rollover Zoom Icon', 'Avada' ),
						'description' => esc_html__( 'Turn on to display the zoom icon in the image rollover.', 'Avada' ),
						'id'          => 'zoom_image_rollover',
						'default'     => '1',
						'type'        => 'switch',
						'required'    => array(
							array(
								'setting'  => 'image_rollover',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'title_image_rollover' => array(
						'label'       => esc_html__( 'Image Rollover Title', 'Avada' ),
						'description' => esc_html__( 'Turn on to display the post title in the image rollover.', 'Avada' ),
						'id'          => 'title_image_rollover',
						'default'     => '1',
						'type'        => 'switch',
						'required'    => array(
							array(
								'setting'  => 'image_rollover',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'cats_image_rollover' => array(
						'label'       => esc_html__( 'Image Rollover Categories', 'Avada' ),
						'description' => esc_html__( 'Turn on to display the post categories in the image rollover.', 'Avada' ),
						'id'          => 'cats_image_rollover',
						'default'     => '1',
						'type'        => 'switch',
						'required'    => array(
							array(
								'setting'  => 'image_rollover',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'icon_circle_image_rollover' => array(
						'label'       => esc_html__( 'Image Rollover Icon Circle', 'Avada' ),
						'description' => esc_html__( 'Turn on to display the icon background circle in the image rollover.', 'Avada' ),
						'id'          => 'icon_circle_image_rollover',
						'default'     => '1',
						'type'        => 'switch',
						'required'    => array(
							array(
								'setting'  => 'image_rollover',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'image_gradient_top_color' => array(
						'label'       => esc_html__( 'Image Rollover Gradient Top Color', 'Avada' ),
						'description' => esc_html__( 'Controls the top color of the image rollover background.', 'Avada' ),
						'id'          => 'image_gradient_top_color',
						'type'        => 'color-alpha',
						'default'     => 'rgba(160,206,78,0.8)',
						'required'    => array(
							array(
								'setting'  => 'image_rollover',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'image_gradient_bottom_color' => array(
						'label'       => esc_html__( 'Image Rollover Gradient Bottom Color', 'Avada' ),
						'description' => esc_html__( 'Controls the bottom color of the image rollover background.', 'Avada' ),
						'id'          => 'image_gradient_bottom_color',
						'default'     => '#a0ce4e',
						'type'        => 'color-alpha',
						'required'    => array(
							array(
								'setting'  => 'image_rollover',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'image_rollover_text_color' => array(
						'label'       => esc_html__( 'Image Rollover Element Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of image rollover text and icon circular backgrounds.', 'Avada' ),
						'id'          => 'image_rollover_text_color',
						'default'     => '#333333',
						'type'        => 'color-alpha',
						'required'    => array(
							array(
								'setting'  => 'image_rollover',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'image_rollover_icon_color' => array(
						'label'       => esc_html__( 'Image Rollover Icon Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the icons in the image rollover.', 'Avada' ),
						'id'          => 'image_rollover_icon_color',
						'default'     => '#ffffff',
						'type'        => 'color-alpha',
						'required'    => array(
							array(
								'setting'  => 'image_rollover',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
				),
			),
			'pagination_box_section' => array(
				'label'       => esc_html__( 'Pagination', 'Avada' ),
				'description' => '',
				'id'          => 'pagination_box_section',
				'type'        => 'sub-section',
				'fields'      => array(
					'pagination_important_note_info' => array(
						'label'       => '',
						'description' => '<div class="avada-avadaredux-important-notice">' . __( '<strong>IMPORTANT NOTE:</strong> The options on this tab apply to all pagination throughout the site, including the 3rd party plugins that Avada has design integration with.', 'Avada' ) . '</div>',
						'id'          => 'pagination_important_note_info',
						'type'        => 'custom',
					),
					'pagination_box_padding' => array(
						'label'       => esc_html__( 'Pagination Box Padding', 'Avada' ),
						'description' => esc_html__( 'Controls the padding inside the pagination box.', 'Avada' ),
						'id'          => 'pagination_box_padding',
						'units'		  => false,
						'default'     => array(
							'width'   => '6px',
							'height'  => '2px',
						),
						'type'        => 'dimensions',
					),
					'pagination_text_display' => array(
						'label'       => esc_html__( 'Pagination Text Display', 'Avada' ),
						'description' => esc_html__( 'Turn on to display the "Previous/Next" text.', 'Avada' ),
						'id'          => 'pagination_text_display',
						'default'     => '1',
						'type'        => 'switch',
					),
					'pagination_font_size' => array(
						'label'       => esc_html__( 'Pagination Font Size', 'Avada' ),
						'description' => esc_html__( 'Controls the size of the pagination text.', 'Avada' ),
						'id'          => 'pagination_font_size',
						'default'     => '12px',
						'type'        => 'dimension',
						'required'        => array(
							array(
								'setting'  => 'pagination_text_display',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
				),
			),
			'forms_styling_section' => array(
				'label'       => esc_html__( 'Forms Styling', 'Avada' ),
				'description' => '',
				'id'          => 'forms_styling_section',
				'type'        => 'sub-section',
				'fields'      => array(
					'forms_styling_important_note_info' => array(
						'label'       => '',
						'description' => '<div class="avada-avadaredux-important-notice">' . __( '<strong>IMPORTANT NOTE:</strong> The options on this tab apply to all forms throughout the site, including the 3rd party plugins that Avada has design integration with.', 'Avada' ) . '</div>',
						'id'          => 'forms_styling_important_note_info',
						'type'        => 'custom',
					),
					'form_input_height' => array(
						'label'       => esc_html__( 'Form Input and Select Height', 'Avada' ),
						'description' => esc_html__( 'Controls the height of all search, form input and select fields.', 'Avada' ),
						'id'          => 'form_input_height',
						'default'     => '29px',
						'type'        => 'dimension',
						'choices'     => array( 'px' ),
					),
					'form_bg_color' => array(
						'label'       => esc_html__( 'Form Background Color', 'Avada' ),
						'description' => esc_html__( 'Controls the background color of form fields.', 'Avada' ),
						'id'          => 'form_bg_color',
						'default'     => '#ffffff',
						'type'        => 'color-alpha'
					),
					'form_text_color' => array(
						'label'       => esc_html__( 'Form Text Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the form text.', 'Avada' ),
						'id'          => 'form_text_color',
						'default'     => '#aaa9a9',
						'type'        => 'color-alpha',
					),
					'form_border_color' => array(
						'label'       => esc_html__( 'Form Border Color', 'Avada' ),
						'description' => esc_html__( 'Controls the border color of the form fields.', 'Avada' ),
						'id'          => 'form_border_color',
						'default'     => '#d2d2d2',
						'type'        => 'color-alpha',
					),
				),
			),
			'gridbox_section' => array(
				'label'       => esc_html__( 'Grid Box Styling', 'Avada' ),
				'description' => '',
				'id'          => 'gridbox_section',
				'type'        => 'sub-section',
				'fields'      => array(
					'gridbox_styling_important_note_info' => array(
						'label'       => '',
						'description' => '<div class="avada-avadaredux-important-notice">' . __( '<strong>IMPORTANT NOTE:</strong> The options on this tab apply to grid boxes throughout the site; blog grid and timeline, portfolio boxed layout and WooCommerce boxes.', 'Avada' ) . '</div>',
						'id'          => 'gridbox_styling_important_note_info',
						'type'        => 'custom',
					),
					'timeline_bg_color' => array(
						'label'       => esc_html__( 'Grid Box Color', 'Avada' ),
						'description' => esc_html__( 'Controls the background color for the grid boxes.', 'Avada' ),
						'id'          => 'timeline_bg_color',
						'default'     => 'rgba(255,255,255,0)',
						'type'        => 'color-alpha',
					),
					'timeline_color' => array(
						'label'       => esc_html__( 'Grid Element Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of borders/divider lines/date box/timeline dots and arrows for the grid boxes.', 'Avada' ),
						'id'          => 'timeline_color',
						'default'     => '#ebeaea',
						'type'        => 'color-alpha',
					),
				),
			),
		),
	);

	return $sections;

}
