<?php

/**
 * Slideshows settings
 *
 * @var  array   any existing settings
 * @return array  existing sections + slideshows
 *
 */
function avada_options_section_slideshows( $sections ) {

	$sections['slideshows'] = array(
		'label'    => esc_html__( 'Slideshows', 'Avada' ),
		'id'       => 'heading_slideshows',
		'priority' => 19,
		'icon'     => 'el-icon-photo-alt',
		'fields'   => array(
			'posts_slideshow_number' => array(
				'label'       => esc_html__( 'Posts Slideshow Images', 'Avada' ),
				'description' => esc_html__( 'Controls the number of featured image boxes for blog/portfolio posts.', 'Avada' ),
				'id'          => 'posts_slideshow_number',
				'default'     => '5',
				'type'        => 'slider',
				'choices'     => array(
					'min'  => '1',
					'max'  => '30',
					'step' => '1',
				),
			),
			'slideshow_autoplay' => array(
				'label'       => esc_html__( 'Autoplay', 'Avada' ),
				'description' => esc_html__( 'Turn on to autoplay the slideshows.', 'Avada' ),
				'id'          => 'slideshow_autoplay',
				'default'     => '1',
				'type'        => 'switch'
			),
			'slideshow_smooth_height' => array(
				'label'       => esc_html__( 'Smooth Height', 'Avada' ),
				'description' => esc_html__( 'Turn on to enable smooth height on slideshows when using images with different heights. Please note, smooth height is disabled on blog grid layout.', 'Avada' ),
				'id'          => 'slideshow_smooth_height',
				'default'     => '0',
				'type'        => 'switch'
			),
			'slideshow_speed' => array(
				'label'       => esc_html__( 'Slideshow Speed', 'Avada' ),
				'description' => esc_html__( 'Controls the speed of slideshows for the [slider] shortcode and sliders within posts. ex: 1000 = 1 second.', 'Avada' ),
				'id'          => 'slideshow_speed',
				'default'     => '7000',
				'type'        => 'slider',
				'choices'     => array(
					'min'  => '100',
					'max'  => '20000',
					'step' => '50',
				),
			),
			'pagination_video_slide' => array(
				'label'       => esc_html__( 'Pagination Circles Below Video Slides', 'Avada' ),
				'description' => esc_html__( 'Turn on to show pagination circles below a video slide for the [slider] shortcode. Turn off to hide them on video slides.', 'Avada' ),
				'id'          => 'pagination_video_slide',
				'default'     => '0',
				'type'        => 'switch'
			),

			'slider_nav_box_dimensions' => array(
				'label'       => esc_html__( 'Navigation Box Dimensions', 'Avada' ),
				'description' => esc_html__( 'Controls the width and height of the navigation box.', 'Avada' ),
				'id'          => 'slider_nav_box_dimensions',
				'units'		  => false,
				'default'     => array(
					'width'   => '30px',
					'height'  => '30px',
				),
				'type'        => 'dimensions',
			),
			'slider_arrow_size' => array(
				'label'       => esc_html__( 'Navigation Arrow Size', 'Avada' ),
				'description' => esc_html__( 'Controls the font size of the navigation arrow.', 'Avada' ),
				'id'          => 'slider_arrow_size',
				'default'     => '14px',
				'type'        => 'dimension',
			),
		),
	);

	return $sections;

}
