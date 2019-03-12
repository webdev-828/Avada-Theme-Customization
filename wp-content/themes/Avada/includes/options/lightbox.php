<?php

/**
 * Lightbox
 *
 * @var  array  	any existing settings
 * @return array 	existing sections + lightbox
 *
 */
function avada_options_section_lightbox( $sections ) {

	$sections['lightbox'] = array(
		'label'    => esc_html__( 'Lightbox', 'Avada' ),
		'id'       => 'heading_lightbox',
		'priority' => 21,
		'icon'     => 'el-icon-info-circle',
		'fields'   => array(
			'status_lightbox' => array(
				'label'       => esc_html__( 'Lightbox', 'Avada' ),
				'description' => esc_html__( 'Turn on to enable the lightbox throughout the theme.', 'Avada' ),
				'id'          => 'status_lightbox',
				'default'     => '1',
				'type'        => 'switch'
			),
			'status_lightbox_single' => array(
				'label'       => esc_html__( 'Lightbox On Single Post Pages', 'Avada' ),
				'description' => esc_html__( 'Turn on to enable the lightbox on single blog and portfolio posts.', 'Avada' ),
				'id'          => 'status_lightbox_single',
				'default'     => '1',
				'type'        => 'switch',
				'required'    => array(
					array(
						'setting'  => 'status_lightbox',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'lightbox_behavior' => array(
				'label'       => esc_html__( 'Lightbox Behavior', 'Avada' ),
				'description' => esc_html__( 'Controls what the lightbox displays for single blog and portfolio posts.', 'Avada' ),
				'id'          => 'lightbox_behavior',
				'default'     => 'all',
				'type'        => 'select',
				'choices'     => array(
					'all'        => esc_html__( 'First featured image of every post', 'Avada' ),
					'individual' => esc_html__( 'Only featured images of individual post', 'Avada' ),
				),
				'required'    => array(
					array(
						'setting'  => 'status_lightbox',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'lightbox_skin' => array(
				'label'       => esc_html__( 'Lightbox Skin', 'Avada' ),
				'description' => esc_html__( 'Controls the lightbox skin design.', 'Avada' ),
				'id'          => 'lightbox_skin',
				'default'     => 'metro-white',
				'type'        => 'select',
				'choices'     => array(
					'light'       => esc_html__( 'Light', 'Avada' ),
					'dark'        => esc_html__( 'Dark', 'Avada' ),
					'mac'         => esc_html__( 'Mac', 'Avada' ),
					'metro-black' => esc_html__( 'Metro Black', 'Avada' ),
					'metro-white' => esc_html__( 'Metro White', 'Avada' ),
					'parade'      => esc_html__( 'Parade', 'Avada' ),
					'smooth'      => esc_html__( 'Smooth', 'Avada' ),
				),
				'required'    => array(
					array(
						'setting'  => 'status_lightbox',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'lightbox_path' => array(
				'label'       => esc_html__( 'Thumbnails Position', 'Avada' ),
				'description' => esc_html__( 'Controls the position of the lightbox thumbnails.', 'Avada' ),
				'id'          => 'lightbox_path',
				'default'     => 'vertical',
				'type'        => 'radio-buttonset',
				'choices'     => array(
					'vertical'   => esc_html__( 'Right', 'Avada' ),
					'horizontal' => esc_html__( 'Bottom', 'Avada' ),
				),
				'required'    => array(
					array(
						'setting'  => 'status_lightbox',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'lightbox_animation_speed' => array(
				'label'       => esc_html__( 'Animation Speed', 'Avada' ),
				'description' => esc_html__( 'Controls the animation speed of the lightbox.', 'Avada' ),
				'id'          => 'lightbox_animation_speed',
				'default'     => 'Normal',
				'type'        => 'radio-buttonset',
				'choices'     => array(
					'Fast'   => esc_html__( 'Fast', 'Avada' ),
					'Slow'   => esc_html__( 'Slow', 'Avada' ),
					'Normal' => esc_html__( 'Normal', 'Avada' ),
				),
				'required'    => array(
					array(
						'setting'  => 'status_lightbox',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'lightbox_arrows' => array(
				'label'       => esc_html__( 'Arrows', 'Avada' ),
				'description' => esc_html__( 'Turn on to display arrows in the lightbox', 'Avada' ),
				'id'          => 'lightbox_arrows',
				'default'     => '1',
				'type'        => 'switch',
				'required'    => array(
					array(
						'setting'  => 'status_lightbox',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'lightbox_gallery' => array(
				'label'       => esc_html__( 'Gallery Start/Stop Button', 'Avada' ),
				'description' => esc_html__( 'Turn on to display the gallery start and stop button.', 'Avada' ),
				'id'          => 'lightbox_gallery',
				'default'     => '1',
				'type'        => 'switch',
				'required'    => array(
					array(
						'setting'  => 'status_lightbox',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'lightbox_autoplay' => array(
				'label'       => esc_html__( 'Autoplay the Lightbox Gallery', 'Avada' ),
				'description' => esc_html__( 'Turn on to autoplay the lightbox gallery.', 'Avada' ),
				'id'          => 'lightbox_autoplay',
				'default'     => '0',
				'type'        => 'switch',
				'required'    => array(
					array(
						'setting'  => 'status_lightbox',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'lightbox_slideshow_speed' => array(
				'label'       => esc_html__( 'Slideshow Speed', 'Avada' ),
				'description' => esc_html__( 'Controls the slideshow speed if autoplay is turned on. ex: 1000 = 1 second.', 'Avada' ),
				'id'          => 'lightbox_slideshow_speed',
				'default'     => '5000',
				'type'        => 'slider',
				'choices'     => array(
					'min'  => '1000',
					'max'  => '20000',
					'step' => '50',
				),
				'required'    => array(
					array(
						'setting'  => 'status_lightbox',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'lightbox_opacity' => array(
				'label'       => esc_html__( 'Background Opacity', 'Avada' ),
				'description' => esc_html__( 'Controls the opacity level for the background behind the lightbox.', 'Avada' ),
				'id'          => 'lightbox_opacity',
				'default'     => '0.9',
				'type'        => 'slider',
				'choices'     => array(
					'min'  => '0.1',
					'max'  => '1',
					'step' => '0.01',
				),
				'required'    => array(
					array(
						'setting'  => 'status_lightbox',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'lightbox_title' => array(
				'label'       => esc_html__( 'Title', 'Avada' ),
				'description' => esc_html__( 'Turn on to display the image title in the lightbox.', 'Avada' ),
				'id'          => 'lightbox_title',
				'default'     => '1',
				'type'        => 'switch',
				'required'    => array(
					array(
						'setting'  => 'status_lightbox',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'lightbox_desc' => array(
				'label'       => esc_html__( 'Caption', 'Avada' ),
				'description' => esc_html__( 'Turn on to display the image caption in the lightbox.', 'Avada' ),
				'id'          => 'lightbox_desc',
				'default'     => '1',
				'type'        => 'switch',
				'required'    => array(
					array(
						'setting'  => 'status_lightbox',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'lightbox_social' => array(
				'label'       => esc_html__( 'Social Sharing', 'Avada' ),
				'description' => esc_html__( 'Turn on to display social sharing buttons on lightbox.', 'Avada' ),
				'id'          => 'lightbox_social',
				'default'     => '1',
				'type'        => 'switch',
				'required'    => array(
					array(
						'setting'  => 'status_lightbox',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'lightbox_deeplinking' => array(
				'label'       => esc_html__( 'Deeplinking', 'Avada' ),
				'description' => esc_html__( 'Turn on to deeplink images in the lightbox.', 'Avada' ),
				'id'          => 'lightbox_deeplinking',
				'default'     => '1',
				'type'        => 'switch',
				'required'    => array(
					array(
						'setting'  => 'status_lightbox',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'lightbox_post_images' => array(
				'label'       => esc_html__( 'Show Post Images in Lightbox', 'Avada' ),
				'description' => esc_html__( 'Turn on to display post images in the lightbox that are inside the post content area.', 'Avada' ),
				'id'          => 'lightbox_post_images',
				'default'     => '1',
				'type'        => 'switch',
				'required'    => array(
					array(
						'setting'  => 'status_lightbox',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'lightbox_video_dimensions' => array(
				'label'       => esc_html__( 'Slideshow Video Dimensions', 'Avada' ),
				'description' => esc_html__( 'Controls the width and height for videos inside the lightbox.', 'Avada' ),
				'id'          => 'lightbox_video_dimensions',
				'units'       => false,
				'default'     => array(
					'width'   => '1280px',
					'height'  => '720px',
				),
				'type'        => 'dimensions',
				'required'    => array(
					array(
						'setting'  => 'status_lightbox',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
		),
	);

	return $sections;

}
