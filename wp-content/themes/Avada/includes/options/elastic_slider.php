<?php

/**
 * Elastic Slider
 *
 * @var  array  	any existing settings
 * @return array 	existing sections + elastic_slider
 *
 */
function avada_options_section_elastic_slider( $sections ) {

	$sections['elastic_slider'] = array(
		'label'    => esc_html__( 'Elastic Slider', 'Avada' ),
		'id'       => 'heading_elastic_slider',
		'priority' => 20,
		'icon'     => 'el-icon-photo-alt',
		'fields'   => array(
			'tfes_disabled_note' => array(
				'label'       => '',
				'description' => '<div class="avada-avadaredux-important-notice">' . __( '<strong>IMPORTANT NOTE:</strong> Elastic Slider is disabled in Advanced > Theme Features section. Please enable it to see the options.', 'Avada' ) . '</div>',
				'id'          => 'tfes_disabled_note',
				'type'        => 'custom',
				'required'    => array(
					array(
						'setting'  => 'status_eslider',
						'operator' => '=',
						'value'    => '0',
					),
				),
			),
			'tfes_dimensions' => array(
				'label'       => esc_html__( 'Elastic Slider Dimensions', 'Avada' ),
				'description' => esc_html__( 'Controls the width and height for the elastic slider.', 'Avada' ),
				'id'          => 'tfes_dimensions',
				'units'       => false,
				'default'     => array(
					'width'   => '100%',
					'height'  => '400px',
				),
				'type'        => 'dimensions',
				'required'    => array(
					array(
						'setting'  => 'status_eslider',
						'operator' => '=',
						'value'    => '1',
					),
				),
			),
			'tfes_animation' => array(
				'label'       => esc_html__( 'Animation Type', 'Avada' ),
				'description' => esc_html__( 'Controls if the elastic slides animate from the sides or center.', 'Avada' ),
				'id'          => 'tfes_animation',
				'default'     => 'sides',
				'type'        => 'radio-buttonset',
				'choices'     => array(
					'sides'  => esc_html__( 'Sides', 'Avada' ),
					'center' => esc_html__( 'Center', 'Avada' ),
				),
				'required'    => array(
					array(
						'setting'  => 'status_eslider',
						'operator' => '=',
						'value'    => '1',
					),
				),
			),
			'tfes_autoplay' => array(
				'label'       => esc_html__( 'Autoplay', 'Avada' ),
				'description' => esc_html__( 'Turn on to autoplay the elastic slides.', 'Avada' ),
				'id'          => 'tfes_autoplay',
				'default'     => '1',
				'type'        => 'switch',
				'required'    => array(
					array(
						'setting'  => 'status_eslider',
						'operator' => '=',
						'value'    => '1',
					),
				),
			),
			'tfes_interval' => array(
				'label'       => esc_html__( 'Slideshow Interval', 'Avada' ),
				'description' => esc_html__( 'Controls how long each elastic slide is visible. ex: 1000 = 1 second.', 'Avada' ),
				'id'          => 'tfes_interval',
				'default'     => '3000',
				'type'        => 'slider',
				'choices'     => array(
					'min'  => '0',
					'max'  => '30000',
					'step' => '50',
				),
				'required'    => array(
					array(
						'setting'  => 'status_eslider',
						'operator' => '=',
						'value'    => '1',
					),
				),
			),
			'tfes_speed' => array(
				'label'       => esc_html__( 'Sliding Speed', 'Avada' ),
				'description' => esc_html__( 'Controls the speed of the elastic slider slideshow. ex: 1000 = 1 second.', 'Avada' ),
				'id'          => 'tfes_speed',
				'default'     => '800',
				'type'        => 'slider',
				'choices'     => array(
					'min'  => '0',
					'max'  => '5000',
					'step' => '50',
				),
				'required'    => array(
					array(
						'setting'  => 'status_eslider',
						'operator' => '=',
						'value'    => '1',
					),
				),
			),
			'tfes_width' => array(
				'label'       => esc_html__( 'Thumbnail Width', 'Avada' ),
				'description' => esc_html__( 'Controls the width of the elastic slider thumbnail images.', 'Avada' ),
				'id'          => 'tfes_width',
				'default'     => '150',
				'type'        => 'slider',
				'choices'     => array(
					'min'  => '0',
					'step' => '1',
					'max'  => '500',
					'edit' => 'yes',
				),
				'required'    => array(
					array(
						'setting'  => 'status_eslider',
						'operator' => '=',
						'value'    => '1',
					),
				),
			),
			'es_title_font_size' => array(
				'label'       => esc_html__( 'Title Font Size', 'Avada' ),
				'description' => esc_html__( 'Controls the font size for elastic slider title.', 'Avada' ),
				'id'          => 'es_title_font_size',
				'default'     => '42px',
				'type'        => 'dimension',
				'required'    => array(
					array(
						'setting'  => 'status_eslider',
						'operator' => '=',
						'value'    => '1',
					),
				),
			),
			'es_caption_font_size' => array(
				'label'       => esc_html__( 'Caption Font Size', 'Avada' ),
				'description' => esc_html__( 'Controls the font size for elastic slider caption.', 'Avada' ),
				'id'          => 'es_caption_font_size',
				'default'     => '20px',
				'type'        => 'dimension',
				'required'    => array(
					array(
						'setting'  => 'status_eslider',
						'operator' => '=',
						'value'    => '1',
					),
				),
			),
			'es_title_color' => array(
				'label'       => esc_html__( 'Title Color', 'Avada' ),
				'description' => esc_html__( 'Controls the color of the elastic slider title.', 'Avada' ),
				'id'          => 'es_title_color',
				'default'     => '#333333',
				'type'        => 'color-alpha',
				'required'    => array(
					array(
						'setting'  => 'status_eslider',
						'operator' => '=',
						'value'    => '1',
					),
				),
			),
			'es_caption_color' => array(
				'label'       => esc_html__( 'Caption Color', 'Avada' ),
				'description' => esc_html__( 'Controls the color of the elastic slider caption.', 'Avada' ),
				'id'          => 'es_caption_color',
				'default'     => '#747474',
				'type'        => 'color-alpha',
				'required'    => array(
					array(
						'setting'  => 'status_eslider',
						'operator' => '=',
						'value'    => '1',
					),
				),
			),
		),
	);

	return $sections;

}
