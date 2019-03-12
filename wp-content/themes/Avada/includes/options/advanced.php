<?php

/**
 * Advanced settings
 *
 * @var  array  	any existing settings
 * @return array 	existing sections + advanced
 *
 */
function avada_options_section_advanced( $sections ) {

	$sections['advanced'] = array(
		'label'    => esc_html__( 'Advanced', 'Avada' ),
		'id'       => 'heading_advanced',
		'is_panel' => true,
		'priority' => 25,
		'icon'     => 'el-icon-puzzle',
		'fields'   => array(
			'tracking_head_body_section' => array(
				'label'       => esc_html__( 'Code Fields (Tracking etc.)', 'Avada' ),
				'id'          => 'tracking_head_body_section',
				'icon'        => true,
				'type'        => 'sub-section',
				'fields'      => array(
					'google_analytics' => array(
						'label'       => esc_html__( 'Tracking Code', 'Avada' ),
						'description' => esc_html__( 'Paste your tracking code here. This will be added into the header template of your theme. Place code inside &lt;script&gt; tags.', 'Avada' ),
						'id'          => 'google_analytics',
						'default'     => '',
						'type'        => 'code',
						'choices'     => array(
							'language' => 'html',
							'height'   => 300,
							'theme'    => 'chrome',
						),
					),
					'space_head' => array(
						'label'       => esc_html__( 'Space before &lt;/head&gt;', 'Avada' ),
						'description' => esc_html__( 'Only accepts javascript code wrapped with &lt;script&gt; tags and HTML markup that is valid inside the &lt;/head&gt; tag.', 'Avada' ),
						'id'          => 'space_head',
						'default'     => '',
						'type'        => 'code',
						'choices'     => array(
							'language' => 'html',
							'height'   => 350,
							'theme'    => 'chrome',
						),
					),
					'space_body' => array(
						'label'       => esc_html__( 'Space before &lt;/body&gt;', 'Avada' ),
						'description' => esc_html__( 'Only accepts javascript code, wrapped with &lt;script&gt; tags and valid HTML markup inside the &lt;/body&gt; tag.', 'Avada' ),
						'id'          => 'space_body',
						'default'     => '',
						'type'        => 'code',
						'choices'     => array(
							'language' => 'html',
							'height'   => 350,
							'theme'    => 'chrome',
						),
					),
				),
			),
			'theme_features_section' => array(
				'label'       => esc_html__( 'Theme Features', 'Avada' ),
				'id'          => 'theme_features_section',
				'icon'        => true,
				'type'        => 'sub-section',
				'fields'      => array(
					'pw_jpeg_quality' => array(
						'label'       => esc_html__( 'WordPress jpeg Quality', 'Avada' ),
						'description' => sprintf( esc_html__( 'Controls the quality of the generated image sizes for every uploaded image. Ranges between 0 and 100 percent. Higher values lead to better image qualities but also higher file sizes. NOTE: After changing this value, please install and run the %s plugin once.', 'Avada' ), '<a href="' . admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin=regenerate-thumbnails&amp;TB_iframe=true&amp;width=830&amp;height=472' ) . '" class="thickbox" title="' . esc_html__( 'Regenerate Thumbnails', 'Avada' ) . '">' . esc_html__( 'Regenerate Thumbnails', 'Avada' ) . '</a>' ),
						'id'          => 'pw_jpeg_quality',
						'default'     => '82',
						'type'        => 'slider',
						'choices'     => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
					),
					'smooth_scrolling' => array(
						'label'       => esc_html__( 'Smooth Scrolling', 'Avada' ),
						'description' => esc_html__( 'Turn on to enable smooth scrolling. This will replace default browser scrollbar with a dark scrollbar.', 'Avada' ),
						'id'          => 'smooth_scrolling',
						'default'     => '0',
						'type'        => 'switch'
					),
					'disable_builder' => array(
						'label'       => esc_html__( 'Fusion Builder', 'Avada' ),
						'description' => esc_html__( 'Turn on to enable the fusion builder on pages/posts.', 'Avada' ),
						'id'          => 'disable_builder',
						'default'     => '1',
						'type'        => 'switch'
					),
					'disable_code_block_encoding' => array(
						'label'       => esc_html__( 'Code Block Encoding', 'Avada' ),
						'description' => esc_html__( 'Turn on to enable encoding in the Fusion Builder code block element.', 'Avada' ),
						'id'          => 'disable_code_block_encoding',
						'default'     => '1',
						'type'        => 'switch'
					),
					'disable_megamenu' => array(
						'label'       => esc_html__( 'Mega Menu', 'Avada' ),
						'description' => esc_html__( 'Turn on to enable Avada\'s mega menu.', 'Avada' ),
						'id'          => 'disable_megamenu',
						'default'     => '1',
						'type'        => 'switch'
					),
					'avada_rev_styles' => array(
						'label'       => esc_html__( 'Avada Styles For Revolution Slider', 'Avada' ),
						'description' => esc_html__( 'Turn on to enable the Avada styles and use the default Revolution Slider styles.', 'Avada' ),
						'id'          => 'avada_rev_styles',
						'default'     => '1',
						'type'        => 'switch'
					),
					'avada_styles_dropdowns' => array(
						'label'       => esc_html__( 'Avada Dropdown Styles', 'Avada' ),
						'description' => esc_html__( 'Turn on to enable the Avada styles for dropdown/select fields site wide. This should be done if you experience any issues with 3rd party plugin dropdowns.', 'Avada' ),
						'id'          => 'avada_styles_dropdowns',
						'default'     => '1',
						'type'        => 'switch'
					),
					'use_animate_css' => array(
						'label'       => esc_html__( 'CSS Animations', 'Avada' ),
						'description' => esc_html__( 'Turn on to enable CSS animations on shortcode items.', 'Avada' ),
						'id'          => 'use_animate_css',
						'default'     => '1',
						'type'        => 'switch'
					),
					'disable_mobile_animate_css' => array(
						'label'       => esc_html__( 'CSS Animations on Mobiles', 'Avada' ),
						'description' => esc_html__( 'Turn on to enable CSS animations on mobiles.', 'Avada' ),
						'id'          => 'disable_mobile_animate_css',
						'default'     => '0',
						'type'        => 'switch'
					),
					'disable_mobile_image_hovers' => array(
						'label'       => esc_html__( 'CSS Image Hover Animations on Mobiles', 'Avada' ),
						'description' => esc_html__( 'Turn on to enable CSS image hover animations on mobiles.', 'Avada' ),
						'id'          => 'disable_mobile_image_hovers',
						'default'     => '1',
						'type'        => 'switch',
					),
					'status_yt' => array(
						'label'       => esc_html__( 'Youtube API Scripts', 'Avada' ),
						'description' => esc_html__( 'Turn on to enable Youtube API scripts.', 'Avada' ),
						'id'          => 'status_yt',
						'default'     => '1',
						'type'        => 'switch'
					),
					'status_vimeo' => array(
						'label'       => esc_html__( 'Vimeo API Scripts', 'Avada' ),
						'description' => esc_html__( 'Turn on to enable Vimeo API scripts.', 'Avada' ),
						'id'          => 'status_vimeo',
						'default'     => '1',
						'type'        => 'switch'
					),
					'status_gmap' => array(
						'label'       => esc_html__( 'Google Map Scripts', 'Avada' ),
						'description' => esc_html__( 'Turn on to enable google map.', 'Avada' ),
						'id'          => 'status_gmap',
						'default'     => '1',
						'type'        => 'switch'
					),
					'status_totop' => array(
						'label'       => esc_html__( 'ToTop Script', 'Avada' ),
						'description' => esc_html__( 'Turn on to enable the ToTop script which adds the scrolling to top functionality.', 'Avada' ),
						'id'          => 'status_totop',
						'default'     => '1',
						'type'        => 'switch'
					),
					'status_totop_mobile' => array(
						'label'       => esc_html__( 'ToTop Script on mobile', 'Avada' ),
						'description' => esc_html__( 'Turn on to enable the ToTop script on mobile devices.', 'Avada' ),
						'id'          => 'status_totop_mobile',
						'default'     => '0',
						'type'        => 'switch'
					),
					'status_fusion_slider' => array(
						'label'       => esc_html__( 'Fusion Slider', 'Avada' ),
						'description' => esc_html__( 'Turn on to enable the fusion slider.', 'Avada' ),
						'id'          => 'status_fusion_slider',
						'default'     => '1',
						'type'        => 'switch'
					),
					'status_eslider' => array(
						'label'       => esc_html__( 'Elastic Slider', 'Avada' ),
						'description' => esc_html__( 'Turn on to enable the elastic slider.', 'Avada' ),
						'id'          => 'status_eslider',
						'default'     => '1',
						'type'        => 'switch'
					),
					'status_fontawesome' => array(
						'label'       => esc_html__( 'FontAwesome', 'Avada' ),
						'description' => esc_html__( 'Turn on to enable font awesome icons.', 'Avada' ),
						'id'          => 'status_fontawesome',
						'default'     => '1',
						'type'        => 'switch'
					),
					'status_opengraph' => array(
						'label'       => esc_html__( 'Open Graph Meta Tags', 'Avada' ),
						'description' => esc_html__( 'Turn on to enable open graph meta tags which is mainly used when sharing pages on social networking sites like Facebook.', 'Avada' ),
						'id'          => 'status_opengraph',
						'default'     => '1',
						'type'        => 'switch'
					),
					'disable_date_rich_snippet_pages' => array(
						'label'       => esc_html__( 'Rich Snippets', 'Avada' ),
						'description' => esc_html__( 'Turn on to enable rich snippets data site wide.', 'Avada' ),
						'id'          => 'disable_date_rich_snippet_pages',
						'default'     => '1',
						'type'        => 'switch'
					),
					'dev_mode' => array(
						'label'       => esc_html__( 'Activate Developers Mode', 'Avada' ),
						'description' => esc_html__( 'By default all the javascript files are combined and minified. Activating developers mode will load non-combined and non-minified javascript files, which is used for development only. This will have an impact on the performance of your site.', 'Avada' ),
						'id'          => 'dev_mode',
						'default'     => '0',
						'type'        => 'switch'
					),
				),
			),
			'dynamic_css_compiler_section' => array(
				'label'       => esc_html__( 'Dynamic CSS', 'Avada' ),
				'id'          => 'dynamic_css_section',
				'icon'        => true,
				'type'        => 'sub-section',
				'fields'      => array(
					'dynamic_css_compiler' => array(
						'label'       => esc_html__( 'CSS Compiler', 'Avada' ),
						'description' => esc_html__( 'Turn on to compile the dynamic CSS into a file. A separate file will be created for each of your pages & posts inside of the uploads/avada-styles folder.', 'Avada' ),
						'id'          => 'dynamic_css_compiler',
						'default'     => '1',
						'type'        => 'switch'
					),
					'dynamic_css_db_caching' => array(
						'label'       => esc_html__( 'Database Caching for Dynamic CSS', 'Avada' ),
						'description' => esc_html__( 'Turn on to enable caching the dynamic CSS in your database.', 'Avada' ),
						'id'          => 'dynamic_css_db_caching',
						'default'     => '0',
						'type'        => 'switch',
					),
					'cache_server_ip' => array(
						'label'       => esc_html__( 'Cache Server IP', 'Avada' ),
						'description' => esc_html__( 'For unique cases where you are using cloud flare and a cache server, ex: varnish cache. Enter your cache server IP to clear the theme options dynamic CSS cache. Consult with your server admin for help.', 'Avada' ),
						'id'          => 'cache_server_ip',
						'default'     => '',
						'type'        => 'text'
					),
				),
			),
		),
	);

	return $sections;

}
