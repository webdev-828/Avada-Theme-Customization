<?php

/**
 * Portfolio settings
 *
 * @var  array   any existing settings
 * @return array  existing sections + portfolio
 *
 */
function avada_options_section_portfolio( $sections ) {

	$sections['portfolio'] = array(
		'label'    => esc_html__( 'Portfolio', 'Avada' ),
		'id'       => 'heading_portfolio',
		'priority' => 16,
		'icon'     => 'el-icon-th',
		'class'    => 'hidden-section-heading',
		'fields'   => array(
			'general_portfolio_options_subsection' => array(
				'label'       => esc_html__( 'General Portfolio', 'Avada' ),
				'description' => '',
				'id'          => 'general_portfolio_options_subsection',
				'icon'        => true,
				'type'        => 'sub-section',
				'fields'      => array(
					'general_portfolio_important_note_info' => array(
						'label'       => '',
						'description' => '<div class="avada-avadaredux-important-notice">' . __( '<strong>IMPORTANT NOTE:</strong> The options on this tab only control the portfolio page templates and portfolio archives, not the recent work shortcode. The only options on this tab that work with the recent work shortcode is the Load More Post Button Color.', 'Avada' ) . '</div>',
						'id'          => 'general_portfolio_important_note_info',
						'type'        => 'custom',
					),
					'portfolio_items' => array(
						'label'       => esc_html__( 'Number of Portfolio Items Per Page', 'Avada' ),
						'description' => esc_html__( 'Controls the number of posts that display per page. Enter 0 to display all posts on one page.', 'Avada' ),
						'id'          => 'portfolio_items',
						'default'     => '10',
						'type'        => 'slider',
						'choices'     => array(
							'min'  => '0',
							'max'  => '50',
							'step' => '1',
						),
					),
					'portfolio_archive_layout' => array(
						'label'       => esc_html__( 'Portfolio Archive Layout', 'Avada' ),
						'description' => esc_html__( 'Controls the layout for the portfolio archive pages.', 'Avada' ),
						'id'          => 'portfolio_archive_layout',
						'default'     => 'Portfolio One Column',
						'type'        => 'select',
						'choices' => array(
							'Portfolio One Column'        => esc_html__( 'Portfolio One Column', 'Avada' ),
							'Portfolio Two Column'        => esc_html__( 'Portfolio Two Column', 'Avada' ),
							'Portfolio Three Column'      => esc_html__( 'Portfolio Three Column', 'Avada' ),
							'Portfolio Four Column'       => esc_html__( 'Portfolio Four Column', 'Avada' ),
							'Portfolio Five Column'       => esc_html__( 'Portfolio Five Column', 'Avada' ),
							'Portfolio Six Column'        => esc_html__( 'Portfolio Six Column', 'Avada' ),
							'Portfolio One Column Text'   => esc_html__( 'Portfolio One Column Text', 'Avada' ),
							'Portfolio Two Column Text'   => esc_html__( 'Portfolio Two Column Text', 'Avada' ),
							'Portfolio Three Column Text' => esc_html__( 'Portfolio Three Column Text', 'Avada' ),
							'Portfolio Four Column Text'  => esc_html__( 'Portfolio Four Column Text', 'Avada' ),
							'Portfolio Five Column Text'  => esc_html__( 'Portfolio Five Column Text', 'Avada' ),
							'Portfolio Six Column Text'   => esc_html__( 'Portfolio Six Column Text', 'Avada' ),
							'Portfolio Grid'              => esc_html__( 'Portfolio Grid', 'Avada' ),
						)
					),
					'portfolio_column_spacing' => array(
						'label'       => esc_html__( 'Portfolio Archive Column Spacing', 'Avada' ),
						'description' => esc_html__( 'Controls the column spacing for portfolio archive pages.', 'Avada' ),
						'id'          => 'portfolio_column_spacing',
						'default'     => '12',
						'type'        => 'slider',
						'choices'     => array(
							'min'  => '0',
							'max'  => '300',
							'step' => '1',
						),
					),
					'portfolio_content_length' => array(
						'label'       => esc_html__( 'Portfolio Content Display', 'Avada' ),
						'description' => esc_html__( 'Controls if the portfolio content displays an excerpt or full content for portfolio page templates or portfolio archive pages.', 'Avada' ),
						'id'          => 'portfolio_content_length',
						'default'     => 'Excerpt',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'Excerpt'      => esc_html__( 'Excerpt', 'Avada' ),
							'Full Content' => esc_html__( 'Full Content', 'Avada' ),
						),
					),
					'excerpt_length_portfolio' => array(
						'label'       => esc_html__( 'Excerpt Length', 'Avada' ),
						'description' => esc_html__( 'Controls the number of words in the excerpts for portfolio page templates or portfolio archive pages.', 'Avada' ),
						'id'          => 'excerpt_length_portfolio',
						'default'     => '55',
						'type'        => 'slider',
						'choices'     => array(
							'min'  => '0',
							'max'  => '500',
							'step' => '1',
						),
						'required'    => array(
							array(
								'setting'  => 'portfolio_content_length',
								'operator' => '==',
								'value'    => 'Excerpt',
							),
						),
					),
					'portfolio_strip_html_excerpt' => array(
						'label'       => esc_html__( 'Strip HTML from Excerpt', 'Avada' ),
						'description' => esc_html__( 'Turn on to strip HTML content from the excerpt for portfolio page templates or portfolio archive pages.', 'Avada' ),
						'id'          => 'portfolio_strip_html_excerpt',
						'default'     => '1',
						'type'        => 'switch'
					),
					'grid_pagination_type' => array(
						'label'       => esc_html__( 'Pagination Type', 'Avada' ),
						'description' => esc_html__( 'Controls the pagination type for portfolio page templates or portfolio archive pages.', 'Avada' ),
						'id'          => 'grid_pagination_type',
						'default'     => 'Pagination',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'Pagination'       => esc_html__( 'Pagination', 'Avada' ),
							'Infinite Scroll'  => esc_html__( 'Infinite Scroll', 'Avada' ),
							'load_more_button' => esc_html__( 'Load More Button', 'Avada' ),
						)
					),
					'portfolio_load_more_posts_button_bg_color' => array(
						'label'       => esc_html__( 'Load More Posts Button Color', 'Avada' ),
						'description' => esc_html__( 'Controls the background color of the load more button for ajax post loading. Also works with the recent work shortcode.', 'Avada' ),
						'id'          => 'portfolio_load_more_posts_button_bg_color',
						'default'     => '#ebeaea',
						'type'        => 'color-alpha',
					),
					'portfolio_text_layout' => array(
						'label'       => esc_html__( 'Portfolio Text Layout', 'Avada' ),
						'description' => esc_html__( 'Controls if the portfolio text layouts are boxed or unboxed.', 'Avada' ),
						'id'          => 'portfolio_text_layout',
						'default'     => 'unboxed',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'boxed'   => esc_html__( 'Boxed', 'Avada' ),
							'unboxed' => esc_html__( 'Unboxed', 'Avada' ),
						)
					),
					'portfolio_slug' => array(
						'label'       => esc_html__( 'Portfolio Slug', 'Avada' ),
						'description' => esc_html__( 'The slug name cannot be the same name as your portfolio page or the layout will break. This option changes the permalink when you use the permalink type as %postname%. Make sure to regenerate permalinks.', 'Avada' ),
						'id'          => 'portfolio_slug',
						'default'     => 'portfolio-items',
						'type'        => 'text'
					),
					'portfolio_featured_image_size' => array(
						'label'       => esc_html__( 'Portfolio Featured Image Size', 'Avada' ),
						'description' => esc_html__( 'Controls if the featured image size is fixed (cropped) or auto (full image ratio) for portfolio page templates and portfolio archive pages. IMPORTANT: Fixed works best with a standard 940px site width. Auto works best with larger site widths.', 'Avada' ),
						'id'          => 'portfolio_featured_image_size',
						'default'     => 'cropped',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'cropped' => esc_html__( 'Fixed', 'Avada' ),
							'full'    => esc_html__( 'Auto', 'Avada' ),
						),
					),
				),
			),
			'portfolio_single_post_page_options_subsection' => array(
				'label'       => esc_html__( 'Portfolio Single Post', 'Avada' ),
				'description' => '',
				'id'          => 'portfolio_single_post_page_options_subsection',
				'icon'        => true,
				'type'        => 'sub-section',
				'fields'      => array(
					'portfolio_pn_nav' => array(
						'label'       => esc_html__( 'Previous/Next Pagination', 'Avada' ),
						'description' => esc_html__( 'Turn on to display the previous/next post pagination for single portfolio posts.', 'Avada' ),
						'id'          => 'portfolio_pn_nav',
						'default'     => '1',
						'type'        => 'switch'
					),
					'portfolio_featured_images' => array(
						'label'       => esc_html__( 'Featured Image / Video on Single Post Page', 'Avada' ),
						'description' => esc_html__( 'Turn on to display featured images and videos on single portfolio posts.', 'Avada' ),
						'id'          => 'portfolio_featured_images',
						'default'     => '1',
						'type'        => 'switch'
					),
					'portfolio_disable_first_featured_image' => array(
						'label'       => esc_html__( 'First Featured Image', 'Avada' ),
						'description' => esc_html__( 'Turn on to display the 1st featured image on single portfolio posts.', 'Avada' ),
						'id'          => 'portfolio_disable_first_featured_image',
						'default'     => '1',
						'type'        => 'switch',
						'required'    => array(
							array(
								'setting'  => 'portfolio_featured_images',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'portfolio_featured_image_width' => array(
						'label'       => esc_html__( 'Featured Image Column Size', 'Avada' ),
						'description' => esc_html__( 'Controls if the featured image is half or full width on single portfolio posts.', 'Avada' ),
						'id'          => 'portfolio_featured_image_width',
						'default'     => 'full',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'full' => esc_html__( 'Full Width', 'Avada' ),
							'half' => esc_html__( 'Half Width', 'Avada' ),
						),
						'required'    => array(
							array(
								'setting'  => 'portfolio_featured_images',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'portfolio_width_100' => array(
						'label'       => esc_html__( '100% Width Page', 'Avada' ),
						'description' => esc_html__( 'Turn on to display portfolio posts at 100% browser width according to the window size. Turn off to follow site width.', 'Avada' ),
						'id'          => 'portfolio_width_100',
						'default'     => '0',
						'type'        => 'switch',
					),
					'portfolio_project_desc_title' => array(
						'label'       => esc_html__( 'Project Description Title', 'Avada' ),
						'description' => esc_html__( 'Turn on to show the project description title on single portfolio posts.', 'Avada' ),
						'id'          => 'portfolio_project_desc_title',
						'default'     => '1',
						'type'        => 'switch'
					),
					'portfolio_project_details' => array(
						'label'       => esc_html__( 'Project Details', 'Avada' ),
						'description' => esc_html__( 'Turn on to show the project details title and content on single portfolio posts.', 'Avada' ),
						'id'          => 'portfolio_project_details',
						'default'     => '1',
						'type'        => 'switch'
					),
					'portfolio_link_icon_target' => array(
						'label'       => esc_html__( 'Open Post Links In New Window', 'Avada' ),
						'description' => esc_html__( 'Turn on to open the single post page, project url and copyright url links in a new window..', 'Avada' ),
						'id'          => 'portfolio_link_icon_target',
						'default'     => '0',
						'type'        => 'switch'
					),
					'portfolio_comments' => array(
						'label'       => esc_html__( 'Comments', 'Avada' ),
						'description' => esc_html__( 'Turn on to display comments on single portfolio posts.', 'Avada' ),
						'id'          => 'portfolio_comments',
						'default'     => '0',
						'type'        => 'switch'
					),
					'portfolio_author' => array(
						'label'       => esc_html__( 'Author', 'Avada' ),
						'description' => esc_html__( 'Turn on to display the author name on single portfolio posts.', 'Avada' ),
						'id'          => 'portfolio_author',
						'default'     => '0',
						'type'        => 'switch'
					),
					'portfolio_social_sharing_box' => array(
						'label'       => esc_html__( 'Social Sharing Box', 'Avada' ),
						'description' => esc_html__( 'Turn on to display the social sharing box on single portfolio posts.', 'Avada' ),
						'id'          => 'portfolio_social_sharing_box',
						'default'     => '1',
						'type'        => 'switch'
					),
					'portfolio_related_posts' => array(
						'label'       => esc_html__( 'Related Projects', 'Avada' ),
						'description' => esc_html__( 'Turn on to display related projects on single portfolio posts.', 'Avada' ),
						'id'          => 'portfolio_related_posts',
						'default'     => '1',
						'type'        => 'switch'
					),
				),
			),
		),
	);

	return $sections;

}
